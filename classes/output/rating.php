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
 * Block rate course rating.
 *
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix <pierre.duverneix@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

namespace block_rate_course\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Block rate course rating.
 *
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix <pierre.duverneix@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class rating implements renderable, templatable {

    /**
     * Core function used to initialize the rating.
     * @param int $courseid
     */
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $DB;
        $sql = "SELECT AVG(rating) AS avg FROM {block_rate_course} WHERE course = $this->courseid";
        $avg = -1;
        if ($avgrec = $DB->get_record_sql($sql)) {
            $avg = $avgrec->avg * 2;  // Double it for half star scores.
            // Now round it up or down.
            $avg = round($avg);
        }
        $starscount = $avg / 2;
        $parts = explode('.', (string) $starscount);
        $count = intval($parts[0]);
        $half = isset($parts[1]) ? true : false;
        $stars = [];
        for ($i = 0; $i < $count; $i++) {
            array_push($stars, $i);
        }
        $cnt = $DB->count_records('block_rate_course', ['course' => $this->courseid]);
        return [
            'rating' => $starscount,
            'stars' => $stars,
            'half' => $half,
            'all' => $cnt
        ];
    }
}
