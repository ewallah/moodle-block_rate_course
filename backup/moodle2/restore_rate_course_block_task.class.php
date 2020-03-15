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
 * Rate course block backup
 *
 * @package    block_rate_course
 * @copyright  2012 Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/blocks/rate_course/backup/moodle2/restore_rate_course_stepslib.php');

/**
 * Rate course block backup
 *
 * @package    block_rate_course
 * @copyright  2012 Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_rate_course_block_task extends restore_block_task {

    /**
     * Define the settings of the restore.
     *
     */
    protected function define_my_settings() {
    }

    /**
     * Define the steps of the restore.
     *
     */
    protected function define_my_steps() {
        $this->add_step(new restore_rate_course_block_structure_step('rate_course_structure', 'rate_course.xml'));
    }

    /**
     * Define the file areas of the restore.
     *
     * @return array
     */
    public function get_fileareas() {
        return [];
    }

    /**
     * Define the encoded attributes of the restore.
     *
     * @return array
     */
    public function get_configdata_encoded_attributes() {
        return [];
    }

    /**
     * Define the decode contents of the restore.
     *
     * @return array
     */
    static public function define_decode_contents() {
        return [];
    }

    /**
     * Define the decode rules of the restore.
     *
     * @return array
     */
    static public function define_decode_rules() {
        return [];
    }
}
