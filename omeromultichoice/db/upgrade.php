<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Multiple choice question type upgrade code.
 *
 * @package    qtype
 * @subpackage omeromultichoice
 * @copyright  1999 onwards Martin Dougiamas {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later //FIXME: check the licence
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Upgrade code for the multiple choice question type.
 * @param int $oldversion the version we are upgrading from.
 * @return bool
 */
function xmldb_qtype_omeromultichoice_upgrade($oldversion)
{
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Moodle v2.9.0 release upgrade line.
    // Put any upgrade step following this.

    // Version older than 2015111100 (i.e., releases with codename: boar or rhino)
    if ($oldversion < 2015112400) {

        $transaction = $DB->start_delegated_transaction();

        try {

            // Find duplicate rows before they break the 2013092304 step below.
            $questions = $DB->get_records("qtype_omemultichoice_options");
            foreach ($questions as $question) {

                $match = null;
                preg_match('/\/([0-9]+)\?/', $question->omeroimageurl, $match);

                if (!$match)
                    throw new Exception("Unable to detect the image_id");

                // image_id
                $image_id = $match[1];

                // Parse parameters
                $query = parse_url($question->omeroimageurl, PHP_URL_QUERY);
                parse_str($query, $params);

                // /omero-image-repository/1?id=1&t=1&z=1&zm=12.5171094891799&x=0.7097536029753603&y=0.3882659228265923
                $question->omeroimageurl = "/omero-image-repository/$image_id?id=$image_id";
                foreach ($params as $name => $value) {
                    // update url parameters
                    if (strcmp("id", $name) !== 0)
                        $question->omeroimageurl .= "&$name=$value";
                }

                // try to update the record
                if (!$DB->update_record("qtype_omemultichoice_options", $question)) {
                    throw new Exception("Error during question update: " . $question->id);
                }
            }

            // Assuming that all updates are OK!!!.
            $transaction->allow_commit();

        } catch (Exception $e) {
            // abort the current transaction
            $transaction->rollback($e);
            error_log($e->getMessage());
            return false;
        }

        // Shortanswer savepoint reached.
        upgrade_plugin_savepoint(true, 2015112400, 'qtype', 'omeromultichoice');
    }


    if ($oldversion < 2015121700) {

        $table_name = "qtype_omemultichoice_options";
        $transaction = $DB->start_delegated_transaction();

        try {

            if (!$dbman->field_exists($table_name, "omeroimagelocked")) {
                $DB->execute("ALTER TABLE  mdl_qtype_omemultichoice_options ADD omeroimagelocked TINYINT(1) NOT NULL DEFAULT 0");
            }

            if (!$dbman->field_exists($table_name, "omeroimageproperties")) {
                $DB->execute("ALTER TABLE  mdl_qtype_omemultichoice_options ADD omeroimageproperties LONGTEXT NULL DEFAULT NULL");
            }

            if ($dbman->field_exists($table_name, "answertype")) {
                $DB->execute("ALTER TABLE  mdl_qtype_omemultichoice_options DROP answertype");
            }

            // Find duplicate rows before they break the 2013092304 step below.
            $questions = $DB->get_records("qtype_omemultichoice_options");
            foreach ($questions as $question) {

                $match = null;
                preg_match('/\/([0-9]+)(\?.*)?/', $question->omeroimageurl, $match);

                if (!$match)
                    throw new Exception("Unable to detect the image_id");

                // image_id
                $image_id = $match[1];
                $params = null;

                // Parse parameters
                $query = parse_url($question->omeroimageurl, PHP_URL_QUERY);
                parse_str($query, $params);

                $image_properties = null;
                if ($params && count($params) > 0) {
                    $image_properties = array(
                        "id" => isset($params["id"]) ? (int)$params["id"] : $image_id,
                        "center" => array(
                            "x" => isset($params["x"]) ? (double)$params["x"] : 0.0,
                            "y" => isset($params["y"]) ? (double)$params["y"] : 0.0,
                        ),
                        "t" => 1,
                        "z" => 1,
                        "zoom_level" => isset($params["zm"]) ? (double)$params["zm"] : 0.0
                    );

                    $image_properties = json_encode($image_properties);
                }

                $question->omeroimageurl = "/omero-image-repository/$image_id";
                $question->omeroimagelocked = 0;
                if ($image_properties)
                    $question->omeroimageproperties = $image_properties;

                // try to update the record
                if (!$DB->update_record("qtype_omemultichoice_options", $question)) {
                    throw new Exception("Error during question update: " . $question->id);
                }
            }

            // Assuming that all updates are OK!!!.
            $transaction->allow_commit();

        } catch (Exception $e) {
            // abort the current transaction
            $transaction->rollback($e);
            error_log($e->getMessage());
            return false;
        }

        // Shortanswer savepoint reached.
        upgrade_plugin_savepoint(true, 2015121700, 'qtype', 'omeromultichoice');
    }

    if ($oldversion < 2016012101) {

        $table_name = "qtype_omemultichoice_options";
        $transaction = $DB->start_delegated_transaction();

        try {

            if (!$dbman->field_exists($table_name, "focusablerois")) {
                $DB->execute("ALTER TABLE  mdl_qtype_omemultichoice_options ADD focusablerois LONGTEXT NOT NULL");
            }

            // Find duplicate rows before they break the 2013092304 step below.
            $questions = $DB->get_records("qtype_omemultichoice_options");
            foreach ($questions as $question) {
                $question->focusablerois = "";

                // try to update the record
                if (!$DB->update_record("qtype_omemultichoice_options", $question)) {
                    throw new Exception("Error during question update: " . $question->id);
                }
            }

            // Assuming that all updates are OK!!!.
            $transaction->allow_commit();

        } catch (Exception $e) {
            // abort the current transaction
            $transaction->rollback($e);
            error_log($e->getMessage());
            return false;
        }

        // Shortanswer savepoint reached.
        upgrade_plugin_savepoint(true, 2016012101, 'qtype', 'omeromultichoice');
    }

    return true;
}