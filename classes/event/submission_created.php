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
 * The assignsubmission_metherpad submission_created event.
 *
 * @package    assignsubmission_metherpad
 * @copyright  2018 Gidenilson Santigo {gidenilson@gmail.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace assignsubmission_metherpad\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The assignsubmission_metherpad submission_created event class.
 *
 * @property-read array $other {
 *      Extra information about the event.
 *
 *      - int metherpadwordcount: Word count of the metherpad submission.
 * }
 *
 * @package    assignsubmission_metherpad
 * @since      Moodle 3.2
 * @copyright  2018 Gidenilson Santigo {gidenilson@gmail.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class submission_created extends \mod_assign\event\submission_created {

    /**
     * Init method.
     */
    protected function init() {
        parent::init();
        $this->data['objecttable'] = 'assignsubmission_metherpad';
    }

    /**
     * Returns non-localised description of what happened.
     *
     * @return string
     */
    public function get_description() {
        $descriptionstring = "The user with id '$this->userid' created an metherpad submission with " .
            "'{$this->other['metherpadwordcount']}' words in the assignment with course module id " .
            "'$this->contextinstanceid'";
        if (!empty($this->other['groupid'])) {
            $descriptionstring .= " for the group with id '{$this->other['groupid']}'.";
        } else {
            $descriptionstring .= ".";
        }

        return $descriptionstring;
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {
        parent::validate_data();
        if (!isset($this->other['metherpadwordcount'])) {
            throw new \coding_exception('The \'metherpadwordcount\' value must be set in other.');
        }
    }

    public static function get_objectid_mapping() {
        // No mapping available for 'assignsubmission_metherpad'.
        return array('db' => 'assignsubmission_metherpad', 'restore' => \core\event\base::NOT_MAPPED);
    }
}
