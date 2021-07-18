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

require_once($CFG->dirroot . '/blocks/rate_course/backup/moodle2/backup_rate_course_stepslib.php');

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
class backup_rate_course_block_task extends backup_block_task {

    /**
     * Define the settings of the backup.
     *
     */
    protected function define_my_settings() {
    }

    /**
     * Define the steps of the backup.
     *
     */
    protected function define_my_steps() {
        $this->add_step(new backup_rate_course_block_structure_step('rate_course_structure', 'rate_course.xml'));
    }

    /**
     * Define the file areas of the backup.
     *
     * @return array
     */
    public function get_fileareas() {
        return [];
    }

    /**
     * Define the encoded attributes of the backup.
     *
     * @return array
     */
    public function get_configdata_encoded_attributes() {
        return [];
    }

    /**
     * Define the encoded content.
     *
     * @param string $content
     * @return string
     */
    public static function encode_content_links($content) {
        return $content; // No special encoding of links.
    }
}
