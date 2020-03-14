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
 * Other tests for block_rate_course.
 *
 * @package    block_rate_course
 * @category   test
 * @copyright  2018 Renaat Debleu <rdebleu@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for block_rate_course
 *
 * @package    block_rate_course
 * @category   test
 * @copyright  2018 Renaat Debleu <rdebleu@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_rate_course_other_testcase extends advanced_testcase {

    /**
     * Test basic block.
     */
    public function test_block_basic() {
        $this->resetAfterTest(true);
        $dg = $this->getDataGenerator();
        $this->setAdminUser();
        $course = $dg->create_course();
        $user = $dg->create_user();
        $dg->enrol_user($user->id, $course->id);
        $ctx = context_course::instance($course->id);
        $page = new moodle_page();
        $page->set_context(context_course::instance($course->id));
        $page->set_pagelayout('course');
        $course->format = course_get_format($course)->get_format();
        $page->set_pagetype('course-view-' . $course->format);
        $page->blocks->load_blocks();
        $page->blocks->add_block_at_end_of_default_region('rate_course');
        $blockmanager = new block_manager($page);
        $blockmanager->load_blocks();
        $result = core_block_external::get_course_blocks($course->id);
        $result = external_api::clean_returnvalue(core_block_external::get_course_blocks_returns(), $result);
        $this->assertCount(1, $result['blocks']);
        $this->assertEquals('rate_course', $result['blocks'][0]['name']);
    }

    /**
     * Test backup restore block.
     */
    public function test_block_backup() {
        global $CFG, $DB, $USER;
        $this->resetAfterTest(true);
        require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
        require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');
        $dg = $this->getDataGenerator();
        $this->setAdminUser();
        $course = $dg->create_course();
        $user = $dg->create_user();
        $dg->enrol_user($user->id, $course->id);
        $ctx = context_course::instance($course->id);
        $DB->insert_record('block_rate_course', ['course' => $course->id, 'userid' => $user->id, 'rating' => 3]);

        $newcourseid = restore_dbops::create_new_course('Tmp', 'tmp', 1);
        $bc = new backup_controller(backup::TYPE_1COURSE, $course->id, backup::FORMAT_MOODLE, backup::INTERACTIVE_NO,
                backup::MODE_IMPORT, $USER->id);
        $backupid = $bc->get_backupid();
        $backupbasepath = $bc->get_plan()->get_basepath();
        $bc->execute_plan();
        $bc->destroy();
        $rc = new restore_controller($backupid, $newcourseid, backup::INTERACTIVE_NO,
                    backup::MODE_IMPORT, $USER->id, backup::TARGET_CURRENT_ADDING);
        $rc->execute_precheck();
        $rc->execute_plan();
        unset($bc);
        $rc->destroy();
        unset($rc);
        // TODO: Not working.
        $this->assertEquals(1, $DB->count_records('block_rate_course'));
    }


    /**
     * Test backup restore block.
     */
    public function test_block_rate() {
        $this->resetAfterTest(true);
        $brc = new \block_rate_course();
        $this->assertCount(2, $brc->applicable_formats());
        $this->assertFalse($brc->instance_allow_multiple());
    }
}