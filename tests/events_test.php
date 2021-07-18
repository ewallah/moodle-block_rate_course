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
 * Events tests for block_rate_course
 *
 * @package    block_rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event tests for block_rate_course
 *
 * @package    block_rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class block_rate_course_event_testcase extends advanced_testcase {

    /**
     * Test course deleted event.
     */
    public function test_course_delete_event() {
        global $DB;
        $this->resetAfterTest(true);
        $dg = $this->getDataGenerator();
        $this->setAdminUser();
        $user = $dg->create_user();
        $course = $dg->create_course(['idnumber' => 'EWA001']);
        $context = context_course::instance($course->id);

        // Create a rate.
        $DB->insert_record('block_rate_course', ['course' => $course->id, 'userid' => $user->id, 'rating' => 3]);
        $this->assertEquals(1, $DB->count_records('block_rate_course'));

        // Trigger a course deleted event.
        $event = \core\event\course_deleted::create([
            'objectid' => $course->id,
            'context' => $context,
            'other' => [
                'shortname' => $course->shortname,
                'fullname' => $course->fullname,
                'idnumber' => $course->idnumber]]);
        $event->add_record_snapshot('course', $course);
        $event->trigger();
        $this->assertEquals(0, $DB->count_records('block_rate_course'));
    }
}
