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
 * This file keeps track of upgrades to the moodec enrolment plugin
 *
 * @package    enrol_moodec
 * @copyright  2012 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_enrol_moodec_upgrade($oldversion) {
    global $DB, $OUTPUT;

    $dbman = $DB->get_manager();

    // Moodle v2.3.0 release upgrade line
    // Put any upgrade step following this

    if ($oldversion < 2012100702) {
        // Set default expiry threshold to 1 day.
        $DB->execute("UPDATE {enrol} SET expirythreshold = 86400 WHERE enrol = 'moodec' AND expirythreshold = 0");
        upgrade_plugin_savepoint(true, 2012100702, 'enrol', 'moodec');
    }

    if ($oldversion < 2012101400) {
        // Delete obsoleted settings, now using expiry* prefix to make them more consistent.
        unset_config('notifylast', 'enrol_moodec');
        unset_config('notifyhour', 'enrol_moodec');
        upgrade_plugin_savepoint(true, 2012101400, 'enrol', 'moodec');
    }

    // Put any upgrade step for subsequent Moodle releases here.

    return true;
}
