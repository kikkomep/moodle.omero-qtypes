/**
 * Created by kikkomep on 11/25/15.
 */
{
    Y.M.editor_atto.Editor.init({
        "elementid": "id_nuovo-text",
        "content_css": "http:\/\/10.211.55.39:4789\/moodle\/theme\/styles.php\/clean\/1448471023\/editor",
        "contextid": 24,
        "autosaveEnabled": true,
        "autosaveFrequency": "60",
        "language": "en",
        "directionality": "ltr",
        "filepickeroptions": [],
        "plugins": [{
            "group": "collapse",
            "plugins": [{"name": "collapse", "params": [], "showgroups": "5"}]
        }, {
            "group": "style1",
            "plugins": [{"name": "title", "params": []}, {"name": "bold", "params": []}, {
                "name": "italic",
                "params": []
            }]
        }, {
            "group": "list",
            "plugins": [{"name": "unorderedlist", "params": []}, {"name": "orderedlist", "params": []}]
        }, {"group": "links", "plugins": [{"name": "link", "params": []}]}, {
            "group": "files",
            "plugins": [{"name": "image", "params": []}, {"name": "media", "params": []}, {
                "name": "managefiles",
                "params": [],
                "disabled": true,
                "area": [],
                "usercontext": null
            }]
        }, {
            "group": "style2",
            "plugins": [{"name": "underline", "params": []}, {"name": "strike", "params": []}, {
                "name": "subscript",
                "params": []
            }, {"name": "superscript", "params": []}]
        }, {"group": "align", "plugins": [{"name": "align", "params": []}]}, {
            "group": "indent",
            "plugins": [{"name": "indent", "params": []}]
        }, {
            "group": "insert",
            "plugins": [{
                "name": "equation",
                "params": [],
                "texfilteractive": true,
                "contextid": 24,
                "library": {
                    "group1": {
                        "groupname": "librarygroup1",
                        "elements": "\n\\cdot\n\\times\n\\ast\n\\div\n\\diamond\n\\pm\n\\mp\n\\oplus\n\\ominus\n\\otimes\n\\oslash\n\\odot\n\\circ\n\\bullet\n\\asymp\n\\equiv\n\\subseteq\n\\supseteq\n\\leq\n\\geq\n\\preceq\n\\succeq\n\\sim\n\\simeq\n\\approx\n\\subset\n\\supset\n\\ll\n\\gg\n\\prec\n\\succ\n\\infty\n\\in\n\\ni\n\\forall\n\\exists\n\\neq\n"
                    },
                    "group2": {
                        "groupname": "librarygroup2",
                        "elements": "\n\\leftarrow\n\\rightarrow\n\\uparrow\n\\downarrow\n\\leftrightarrow\n\\nearrow\n\\searrow\n\\swarrow\n\\nwarrow\n\\Leftarrow\n\\Rightarrow\n\\Uparrow\n\\Downarrow\n\\Leftrightarrow\n"
                    },
                    "group3": {
                        "groupname": "librarygroup3",
                        "elements": "\n\\alpha\n\\beta\n\\gamma\n\\delta\n\\epsilon\n\\zeta\n\\eta\n\\theta\n\\iota\n\\kappa\n\\lambda\n\\mu\n\\nu\n\\xi\n\\pi\n\\rho\n\\sigma\n\\tau\n\\upsilon\n\\phi\n\\chi\n\\psi\n\\omega\n\\Gamma\n\\Delta\n\\Theta\n\\Lambda\n\\Xi\n\\Pi\n\\Sigma\n\\Upsilon\n\\Phi\n\\Psi\n\\Omega\n"
                    },
                    "group4": {
                        "groupname": "librarygroup4",
                        "elements": "\n\\sum{a,b}\n\\int_{a}^{b}{c}\n\\iint_{a}^{b}{c}\n\\iiint_{a}^{b}{c}\n\\oint{a}\n(a)\n[a]\n\\lbrace{a}\\rbrace\n\\left| \\begin{matrix} a_1 & a_2 \\ a_3 & a_4 \\end{matrix} \\right|\n"
                    }
                },
                "texdocsurl": "http:\/\/docs.moodle.org\/29\/en\/Using_TeX_Notation"
            }, {"name": "charmap", "params": []}, {"name": "table", "params": []}, {"name": "clear", "params": []}]
        }, {"group": "undo", "plugins": [{"name": "undo", "params": []}]}, {
            "group": "accessibility",
            "plugins": [{"name": "accessibilitychecker", "params": []}, {"name": "accessibilityhelper", "params": []}]
        }, {"group": "other", "plugins": [{"name": "html", "params": []}]}],
        "pageHash": "0f1d5201f7bcd676556a9b1eb41a2f6380ef156a"
    });
}



