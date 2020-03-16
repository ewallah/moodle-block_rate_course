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
 * The Rate course block
 *
 * @package    block_rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was Rewritten for Moodle 2.X By Atar + Plus LTD for Comverse LTD.
 * @copyright &copy; 2011 Comverse LTD.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * Code was Rewritten for Moodle 3.4 and sup by Pierre Duverneix.
 * @copyright 2019 Pierre Duverneix.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die;

/**
 * The Rate course block
 *
 * @package    block_rate_course
 * @copyright  2009 Jenny Gray
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class block_rate_course extends block_list {

    /**
     * Core function used to initialize the block.
     */
    public function init() {
        $this->title = get_string('courserating', 'block_rate_course');
    }

    /**
     * Set the applicable formats for this block to all
     * @return array
     */
    public function applicable_formats() {
        return ['course' => true, 'course-view' => true];
    }

    /**
     * All multiple instances of this block
     * @return bool Returns false
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Gets the content for this block
     * return string
     */
    public function get_content() {
        global $COURSE, $DB, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->items = [];
        $this->content->icons = [];

        if ($DB->count_records('block_rate_course', ['course' => $COURSE->id, 'userid' => $USER->id]) === 0) {
            $description = '<div class="alert alert-info alert-dismissible fade show" role="alert">';
            $description .= get_string('intro', 'block_rate_course');
            $description .= '<button type="button" class="close" data-dismiss="alert" aria-label="x">';
            $description .= '<span aria-hidden="true">&times;</span></button></div>';
            $this->content->items[] = $description;
        }
        $form = new \block_rate_course\output\rateform($COURSE->id);
        $renderer = $this->page->get_renderer('block_rate_course');
        $this->content->items[] = $renderer->render($form);
        $rating = new \block_rate_course\output\rating($COURSE->id);
        $renderer = $this->page->get_renderer('block_rate_course');
        // Output current rating.
        $this->content->footer = '<div class="text-center">'.$renderer->render($rating).'</div>';
        return $this->content;
    }
}
