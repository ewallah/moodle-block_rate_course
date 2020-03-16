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
 * Privacy tests for block_rate_course.
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

use \core_privacy\tests\provider_testcase;
use \block_rate_course\privacy\provider;

/**
 * Unit tests for block_rate_course/classes/privacy/policy
 *
 * @package    block_rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class block_rate_course_privacy_testcase extends provider_testcase {

    /** @var int A user. */
    protected $user;

    /**
     * Basic setup for these tests.
     */
    public function setUp() {
        global $DB;
        $this->resetAfterTest(true);
        $this->user = self::getDataGenerator()->create_user();
        $DB->insert_record('block_rate_course', ['course' => 1, 'userid' => $this->user->id, 'rating' => 3]);
    }

    /**
     * Test returning metadata.
     */
    public function test_get_metadata() {
        $collection = new \core_privacy\local\metadata\collection('block_rate_course');
        $collection = provider::get_metadata($collection);
        $this->assertNotEmpty($collection);
    }

    /**
     * Test getting the context for the user ID related to this plugin.
     */
    public function test_get_contexts_for_userid() {
        $contextlist = provider::get_contexts_for_userid($this->user->id);
        $this->assertCount(1, $contextlist);
    }

    /**
     * Check the exporting of rate for a user.
     */
    public function test_export_rates() {
        $context = context_user::instance($this->user->id);
        $this->export_context_data_for_user($this->user->id, $context, 'block_rate_course');
        $writer = \core_privacy\local\request\writer::with_context($context);
        $this->assertTrue($writer->has_any_data());
        $this->export_all_data_for_user($this->user->id, 'block_rate_course');
        $writer = \core_privacy\local\request\writer::with_context($context);
        $this->assertTrue($writer->has_any_data());
    }

    /**
     * Tests the deletion of all rates.
     */
    public function test_delete_rates_for_all_users_in_context() {
        $context = context_user::instance($this->user->id);
        $contextlist = provider::get_contexts_for_userid($this->user->id);
        $this->assertCount(1, $contextlist);
        $list = new core_privacy\tests\request\approved_contextlist($this->user, 'block_rate_course', [$context]);
        $this->assertNotEmpty($list);
        \block_rate_course\privacy\provider::delete_data_for_all_users_in_context($context);
        $contextlist = provider::get_contexts_for_userid($this->user->id);
        $this->assertCount(0, $contextlist);
        $list = new core_privacy\tests\request\approved_contextlist($this->user, 'block_rate_course', [$context]);
        $this->assertCount(1, $list);
    }

    /**
     * Tests deletion of rates for a specified user.
     */
    public function test_delete_rates_for_user() {
        $context = context_user::instance($this->user->id);
        $contextlist = provider::get_contexts_for_userid($this->user->id);
        $this->assertCount(1, $contextlist);
        $list = new core_privacy\tests\request\approved_contextlist($this->user, 'block_rate_course', [$context]);
        $this->assertNotEmpty($list);
        \block_rate_course\privacy\provider::delete_data_for_user($list);
        $contextlist = provider::get_contexts_for_userid($this->user->id);
        $this->assertCount(0, $contextlist);
        $list = new core_privacy\tests\request\approved_contextlist($this->user, 'block_rate_course', [$context]);
        $this->assertNotEmpty($list);
        $this->export_context_data_for_user($this->user->id, $context, 'block_rate_course');
        $writer = \core_privacy\local\request\writer::with_context($context);
        $this->assertFalse($writer->has_any_data());
    }

    /**
     * Tests new functions.
     */
    public function test_other_contexts_or_functions() {
        $context = context_course::instance(1);
        $userlist = new \core_privacy\local\request\userlist($context, 'block_rate_course');
        provider::get_users_in_context($userlist);
        $this->assertCount(0, $userlist);

        $context = context_user::instance($this->user->id);
        $userlist = new \core_privacy\local\request\userlist($context, 'block_rate_course');
        provider::get_users_in_context($userlist);
        $this->assertCount(1, $userlist);

        $approved = new \core_privacy\local\request\approved_userlist($context, 'block_rate_course', [$this->user->id]);
        provider::delete_data_for_users($approved);
        $userlist = new \core_privacy\local\request\userlist($context, 'block_rate_course');
        provider::get_users_in_context($userlist);
        $this->assertCount(0, $userlist);
    }
}