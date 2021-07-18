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
 * External rate course.
 *
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix - Fondation UNIT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . "/externallib.php");

/**
 * External rate course.
 *
 * @package    block_rate_course
 * @copyright  2019 Pierre Duverneix - Fondation UNIT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Code was rewritten for Moodle 3.7+ by Renaat Debleu.
 * @copyright 2020 Renaat debleu <info@eWallah.net>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */
class block_rate_course_external extends external_api {

    /**
     * Describes the parameters for set_status.
     *
     * @return external_function_parameters
     * @since  Moodle 3.4
     */
    public static function set_rating_parameters() {
        return new external_function_parameters(
            ['courseid' => new external_value(PARAM_INT, 'The course ID'),
             'rating' => new external_value(PARAM_INT, 'The rating value')]);
    }

    /**
     * Set rating.
     *
     * @param int $courseid The id of the course
     * @param int $rating
     * @return bool
     */
    public static function set_rating($courseid, $rating) {
        global $DB, $USER;

        // Parameters validation.
        $params = self::validate_parameters(self::set_rating_parameters(), ['courseid' => $courseid, 'rating' => $rating]);

        if ($rating = $DB->get_record('block_rate_course', ['userid' => $USER->id, 'course' => $params['courseid']])) {
            $data = new \stdClass();
            $data->id = $rating->id;
            $data->course = $params['courseid'];
            $data->userid = $USER->id;
            $data->rating = $params['rating'];
            $DB->update_record('block_rate_course', $data);
        } else {
            $DB->insert_record('block_rate_course', [
                'course' => $params['courseid'],
                'userid' => $USER->id,
                'rating' => $params['rating']]);
        }
        return true;
    }

    /**
     * Returns description of method result value.
     *
     * @return external_description
     * @since Moodle 3.4
     */
    public static function set_rating_returns() {
        return new external_value(PARAM_BOOL, 'The user rating status.');
    }
}
