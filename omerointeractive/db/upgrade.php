<?php

// Copyright (c) 2015-2016, CRS4
//
// Permission is hereby granted, free of charge, to any person obtaining a copy of
// this software and associated documentation files (the "Software"), to deal in
// the Software without restriction, including without limitation the rights to
// use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
// the Software, and to permit persons to whom the Software is furnished to do so,
// subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
// FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
// COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
// IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
// CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

/**
 * Upgrade manager for the qtype_omerointeractive module.
 *
 * @package    qtype
 * @package    qtype_omerointeractive
 * @copyright  2015-2016 CRS4
 * @licence    https://opensource.org/licenses/mit-license.php MIT licence
 */

defined('MOODLE_INTERNAL') || die();


/**
 * Upgrade code for the multiple choice question type.
 * @param int $oldversion the version we are upgrading from.
 * @return bool
 */
function xmldb_qtype_omerointeractive_upgrade($oldversion)
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
            $questions = $DB->get_records("qtype_omeinteractive_options");
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
                if (!$DB->update_record("qtype_omeinteractive_options", $question)) {
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
        upgrade_plugin_savepoint(true, 2015112400, 'qtype', 'omerointeractive');
    }

    if ($oldversion < 2016012101) {

        $table_name = "qtype_omeinteractive_options";
        $transaction = $DB->start_delegated_transaction();

        try {

            if (!$dbman->field_exists($table_name, "focusablerois")) {
                $DB->execute("ALTER TABLE  mdl_qtype_omeinteractive_options ADD focusablerois LONGTEXT NOT NULL");
            }

            // Find duplicate rows before they break the 2013092304 step below.
            $questions = $DB->get_records("qtype_omeinteractive_options");
            foreach ($questions as $question) {
                $question->focusablerois = "";

                // try to update the record
                if (!$DB->update_record("qtype_omeinteractive_options", $question)) {
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
        upgrade_plugin_savepoint(true, 2016012101, 'qtype', 'omerointeractive');
    }

    return true;
}
