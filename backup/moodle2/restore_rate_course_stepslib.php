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
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define the complete structure for the backup, with file and id annotations
 *
 * @package    block_rate_course
 * @copyright  2012 Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class restore_rate_course_block_structure_step extends restore_structure_step {

    /**
     * Define the structure of the restore workflow.
     *
     * @return array
     */
    protected function define_structure() {
        return [
            new restore_path_element('block', '/block/rate_course/items'),
            new restore_path_element('item', '/block/rate_course/items/item')];
    }

    /**
     * Define the process of the block restore workflow.
     *
     * @param stdClass $data
     */
    public function process_block($data) {
    }

    /**
     * Define the process of the item restore workflow.
     *
     * @param stdClass $item
     */
    public function process_item($item) {
        global $DB;
        $item['course'] = $this->task->get_courseid();
        if ($existing = $DB->get_field('block_rate_course', 'id', ['course' => $item['course'], 'userid' => $item['userid']])) {
            $item['id'] = $existing;
            $DB->update_record('block_rate_course', $item);
        } else {
            unset($item['id']);
            $DB->insert_record('block_rate_course', $item);
        }
    }
}
