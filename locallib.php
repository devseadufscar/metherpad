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
 * This file contains the definition for the library class for metherpad submission plugin
 *
 * This class provides all the functionality for the new assign module.
 *
 * @package assignsubmission_metherpad
 * @copyright 2018 Gidenilson Santiago {gidenilson@gmail.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
// File area for online text submission assignment.
define('ASSIGNSUBMISSION_METHERPAD_FILEAREA', 'assignsubmission_metherpad');

/**
 * library class for metherpad submission plugin extending submission plugin base class
 *
 * @package assignsubmission_metherpad
 * @copyright 2018 Gidenilson Santiago {gidenilson@gmail.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class assign_submission_metherpad extends assign_submission_plugin
{

    /**
     * Get the name of the metherpad submission plugin
     * @return string
     */
    public function get_name()
    {
        return get_string('metherpad', 'assignsubmission_metherpad');
    }


    /**
     * Get metherpad submission information from the database
     *
     * @param  int $submissionid
     * @return mixed
     */
    private function get_metherpad_submission($submissionid)
    {
        global $DB;

        return $DB->get_record('assignsubmission_metherpad', array('submission' => $submissionid));
    }

    /**
     * Get the settings for metherpad submission plugin
     *
     * @param MoodleQuickForm $mform The form to add elements to
     * @return void
     */
    public function get_settings(MoodleQuickForm $mform)
    {
        global $CFG, $COURSE;


        $defaulturlpad = $this->get_config('urlpad') == 0 ? '' : $this->get_config('urlpad');
        $defaulturlpadenabled = $this->get_config('urlpadenabled');


    }

    /**
     * Save the settings for metherpad submission plugin
     *
     * @param stdClass $data
     * @return bool
     */
    public function save_settings(stdClass $data)
    {
        return true;
    }

    /**
     * Add form elements for settings
     *
     * @param mixed $submission can be null
     * @param MoodleQuickForm $mform
     * @param stdClass $data
     * @return true if elements were added to the form
     */
    public function get_form_elements($submission, MoodleQuickForm $mform, stdClass $data)
    {


        if (!isset($data->metherpad)) {
            $data->metherpad = '';
        }
        if (!isset($data->metherpad)) {

            $data->metherpadformat = editors_get_preferred_format();
        }

        if ($submission) {
            $metherpadsubmission = $this->get_metherpad_submission($submission->id);
            if ($metherpadsubmission) {

                $data->metherpad = $metherpadsubmission->metherpad;
                $data->metherpadformat = $metherpadsubmission->onlineformat;
            }

        }

        $mform->addElement('hidden', 'reply', 'yes');
        $mform->addElement('html', $this->get_etherpad_iframe());

        return true;
    }

    /**
     * Save data to the database and trigger plagiarism plugin,
     * if enabled, to scan the uploaded content via events trigger
     *
     * @param stdClass $submission
     * @param stdClass $data
     * @return bool
     */
    public function save(stdClass $submission, stdClass $data)
    {
        return true;
    }

    /**
     * Return a list of the text fields that can be imported/exported by this plugin
     *
     * @return array An array of field names and descriptions. (name=>description, ...)
     */
    public function get_editor_fields()
    {
        return array('metherpad' => get_string('pluginname', 'assignsubmission_metherpad'));
    }

    /**
     * Get the saved text content from the editor
     *
     * @param string $name
     * @param int $submissionid
     * @return string
     */
    public function get_editor_text($name, $submissionid)
    {
        if ($name == 'metherpad') {
            $metherpadsubmission = $this->get_metherpad_submission($submissionid);
            if ($metherpadsubmission) {
                return $metherpadsubmission->metherpad;
            }
        }

        return '';
    }

    /**
     * Get the content format for the editor
     *
     * @param string $name
     * @param int $submissionid
     * @return int
     */
 /*
    public function get_editor_format($name, $submissionid)
    {
        if ($name == 'metherpad') {
            $metherpadsubmission = $this->get_metherpad_submission($submissionid);
            if ($metherpadsubmission) {
                return $metherpadsubmission->onlineformat;
            }
        }

        return 0;
    }
*/

    /**
     * Display metherpad word count in the submission status table
     *
     * @param stdClass $submission
     * @param bool $showviewlink - If the summary has been truncated set this to true
     * @return string
     */
    public function view_summary(stdClass $submission, & $showviewlink)
    {
       $showviewlink = true;

    }



    /**
     * Display the saved text content from the editor in the view table
     *
     * @param stdClass $submission
     * @return string
     */
    public function view(stdClass $submission)
    {
       return $this->get_etherpad_iframe();

        return $result;
    }

    /**
     * Return true if this plugin can upgrade an old Moodle 2.2 assignment of this type and version.
     *
     * @param string $type old assignment subtype
     * @param int $version old assignment version
     * @return bool True if upgrade is possible
     */
    public function can_upgrade($type, $version)
    {

        if ($type == 'online' && $version >= 2014110400) {
            return true;
        }
        return false;
    }


    /**
     * Upgrade the settings from the old assignment to the new plugin based one
     *
     * @param context $oldcontext - the database for the old assignment context
     * @param stdClass $oldassignment - the database for the old assignment instance
     * @param string $log record log events here
     * @return bool Was it a success?
     */
    public function upgrade_settings(context $oldcontext, stdClass $oldassignment, & $log)
    {
        // No settings to upgrade.
        return true;
    }

    /**
     * Upgrade the submission from the old assignment to the new one
     *
     * @param context $oldcontext - the database for the old assignment context
     * @param stdClass $oldassignment The data record for the old assignment
     * @param stdClass $oldsubmission The data record for the old submission
     * @param stdClass $submission The data record for the new submission
     * @param string $log Record upgrade messages in the log
     * @return bool true or false - false will trigger a rollback
     */
    public function upgrade(context $oldcontext,
                            stdClass $oldassignment,
                            stdClass $oldsubmission,
                            stdClass $submission,
                            & $log)
    {
        global $DB;

        $metherpadsubmission = new stdClass();
        $metherpadsubmission->metherpad = $oldsubmission->data1;
        $metherpadsubmission->onlineformat = $oldsubmission->data2;

        $metherpadsubmission->submission = $submission->id;
        $metherpadsubmission->assignment = $this->assignment->get_instance()->id;

        if ($metherpadsubmission->metherpad === null) {
            $metherpadsubmission->metherpad = '';
        }

        if ($metherpadsubmission->onlineformat === null) {
            $metherpadsubmission->onlineformat = editors_get_preferred_format();
        }

        if (!$DB->insert_record('assignsubmission_metherpad', $metherpadsubmission) > 0) {
            $log .= get_string('couldnotconvertsubmission', 'mod_assign', $submission->userid);
            return false;
        }

        /* Now copy the area files.
        $this->assignment->copy_area_files_for_upgrade($oldcontext->id,
            'mod_assignment',
            'submission',
            $oldsubmission->id,
            $this->assignment->get_context()->id,
            'assignsubmission_metherpad',
            ASSIGNSUBMISSION_METHERPAD_FILEAREA,
            $submission->id);
        return true;*/
    }

    /**
     * Formatting for log info
     *
     * @param stdClass $submission The new submission
     * @return string
     */
    public function format_for_log(stdClass $submission)
    {
        return true;

    }

    /**
     * The assignment has been deleted - cleanup
     *
     * @return bool
     */
    public function delete_instance()
    {
        global $DB;
        $DB->delete_records('assignsubmission_metherpad',
            array('assignment' => $this->assignment->get_instance()->id));

        return true;
    }

    /**
     * No text is set for this plugin
     *
     * @param stdClass $submission
     * @return bool
     */
    public function is_empty(stdClass $submission)
    {
        return false;

    }

    /**
     * Determine if a submission is empty
     *
     * This is distinct from is_empty in that it is intended to be used to
     * determine if a submission made before saving is empty.
     *
     * @param stdClass $data The submission data
     * @return bool
     */
    public function submission_is_empty(stdClass $data)
    {
        return false;

    }

    /**
     * Get file areas returns a list of areas this plugin stores files
     * @return array - An array of fileareas (keys) and descriptions (values)
     */
    public function get_file_areas()
    {
        return array(ASSIGNSUBMISSION_METHERPAD_FILEAREA => $this->get_name());
    }

    /**
     * Copy the student's submission from a previous submission. Used when a student opts to base their resubmission
     * on the last submission.
     * @param stdClass $sourcesubmission
     * @param stdClass $destsubmission
     */
    public function copy_submission(stdClass $sourcesubmission, stdClass $destsubmission)
    {
        global $DB;

        // Copy the files across (attached via the text editor).
        $contextid = $this->assignment->get_context()->id;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'assignsubmission_metherpad',
            ASSIGNSUBMISSION_METHERPAD_FILEAREA, $sourcesubmission->id, 'id', false);
        foreach ($files as $file) {
            $fieldupdates = array('itemid' => $destsubmission->id);
            $fs->create_file_from_storedfile($fieldupdates, $file);
        }

        // Copy the assignsubmission_metherpad record.
        $metherpadsubmission = $this->get_metherpad_submission($sourcesubmission->id);
        if ($metherpadsubmission) {
            unset($metherpadsubmission->id);
            $metherpadsubmission->submission = $destsubmission->id;
            $DB->insert_record('assignsubmission_metherpad', $metherpadsubmission);
        }
        return true;
    }

    /**
     * Return a description of external params suitable for uploading an metherpad submission from a webservice.
     *
     * @return external_description|null
     */
    public function get_external_parameters()
    {
        $editorparams = array('text' => new external_value(PARAM_RAW, 'The text for this submission.'),
            'format' => new external_value(PARAM_INT, 'The format for this submission'),
            'itemid' => new external_value(PARAM_INT, 'The draft area id for files attached to the submission'));
        $editorstructure = new external_single_structure($editorparams, 'Editor structure', VALUE_OPTIONAL);
        return array('metherpad_editor' => $editorstructure);
    }



    /**
     * Return the plugin configs for external functions.
     *
     * @return array the list of settings
     * @since Moodle 3.2
     */
    public function get_config_for_external()
    {
        return (array)$this->get_config();
    }

    /**
     * Return therpad url
     *
     * @return string
     * @since Moodle 3.3
     */
    private function get_etherpad_iframe()
    {
        global $USER, $CFG, $DB, $cm;
        require_once "lib/etherpad-lite/Client.php";


        $config = get_config("metherpad");



        $instance = new EtherpadLite\Client($config->apikey, $config->url . '/api');

        $author_id = $USER->id;
        $author_name = "{$USER->firstname} {$USER->lastname}";
        $author_color = $this->random_color();

        $author = $instance->createAuthorIfNotExistsFor($author_id, $author_name);

        $group = $instance->createGroupIfNotExistsFor(25);
        try {
            $pad = $instance->createGroupPad($group->groupID, "metherpad.{$cm->id}");

        } catch (Exception $e) {
        };


        $instance->setPublicStatus($group->groupID . "$" . "metherpad.{$cm->id}", true);

        $session = $instance->createSession($group->groupID, $author->authorID, time() + 10000);

        setcookie("sessionID", $session->sessionID);

        $src = "{$config->url}/p/" . $group->groupID . "%24" . "metherpad.{$cm->id}?userName={$author_name}&userColor=#{$author_color}";


        return '<iframe style="width:100%; height:500px"  src="' . $src . '"></iframe>';
    }


    private function random_color() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT).str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

    }

}