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
class backup_rate_course_block_structure_step extends backup_block_structure_step {

    /**
     * Define the structure of the backup workflow.
     *
     * @return structure
     */
    protected function define_structure() {
        // Define each element separated.
        $ratecourse = new backup_nested_element('rate_course');
        $items = new backup_nested_element('items');
        $ratecourse->add_child($items);

        // Build the tree.
        $item = new backup_nested_element('item', ['id'], ['course', 'userid', 'rating']);
        $items->add_child($item);

        $item->set_source_table('block_rate_course', ['course' => backup::VAR_COURSEID]);
        $item->annotate_ids('user', 'userid');
        return $this->prepare_block_structure($ratecourse);
    }
}
