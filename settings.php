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
 * This file defines the admin settings for this plugin
 *
 * @package   assignsubmission_metherpad
 * @copyright 2018 Gidenilson Santiago {gidenilson@gmail.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$settings->add(new admin_setting_configcheckbox('assignsubmission_metherpad/default',  new lang_string('default', 'assignsubmission_metherpad'),  new lang_string('default_help', 'assignsubmission_metherpad'), 0));

$settings->add(new admin_setting_configtext('metherpad/url', get_string('url', 'assignsubmission_metherpad'),  get_string('urldesc', 'assignsubmission_metherpad'), 'https://192.168.0.1:9001', PARAM_RAW,40));

$settings->add(new admin_setting_configtext('metherpad/apikey', get_string('apikey', 'assignsubmission_metherpad'),  get_string('apikeydesc', 'assignsubmission_metherpad'), get_string('apikeydesc','assignsubmission_metherpad' ), PARAM_RAW,40));

