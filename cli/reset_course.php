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
 * Scritpt to reset rated course.
 *
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix - Fondation UNIT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

$long = ['help' => false, 'courseid' => false];
$short = ['h' => 'help', 'c' => 'courseid'];

list($options, $unrecognized) = cli_get_params($long, $short);

if ($unrecognized) {
    $unrecognized = implode("\n  ", $unrecognized);
    cli_error(get_string('cliunknowoption', 'admin', $unrecognized));
}

if ($options['courseid'] > 0) {
    $course = get_course($options['courseid']);
    cli_heading("Resetting course: $course->fullname");
    $event = \core\event\course_deleted::create([
        'objectid' => $course->id,
        'context' => context_course::instance($course->id),
        'other' => ['shortname' => $course->shortname, 'fullname' => $course->fullname, 'idnumber' => $course->idnumber]]);
    \block_rate_course_observer::course_deleted($event);
} else {
    $help = "Reset course rates


        Options:
        -h, --help            Print out this help
        -c, --courseid            Course id
        Example:
        \$sudo -u www-data /usr/bin/php blocks/rate_course/cli/reset_course.php -c=2
        \$sudo -u www-data /usr/bin/php blocks/rate_course/cli/reset_course.php --courseid=2
";

    echo $help;
}
