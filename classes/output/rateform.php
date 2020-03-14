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
 * Rate form.
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix <pierre.duverneix@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_rate_course\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use stdClass;

/**
 * Rate form.
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix <pierre.duverneix@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rateform implements renderable, templatable {

    /**
     * Core function used to initialize the form.
     * @param int $courseid
     */
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Private function used to querry the rating.
     * @param int $courseid
     */
    private static function get_my_ratting($courseid) {
        global $DB, $USER;

        if ($myrating = $DB->get_field('block_rate_course', 'rating', ['course' => $courseid, 'userid' => $USER->id])) {
            return $myrating;
        }
        return '';
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $myrating = self::get_my_ratting($this->courseid);
        $israted = false;
        if ($myrating) {
            $israted = true;
        }

        return [
            'israted' => $israted,
            'myrating' => $myrating,
            'courseid' => $this->courseid
        ];
    }
}
