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
 * liketeacher block.
 *
 * @package    block_liketeacher
 * @copyright  2017 Dony Ariesta <donyariesta.rin@gmail.com>
 * @author     Dony Ariesta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 require_once($CFG->dirroot . '/blocks/liketeacher/locallib.php');
class block_liketeacher extends block_base {

    private $renderer;
    private $locallib;

    /**
     * Initializes class member variables.
     */
    public function init() {
        global $PAGE;
        $this->renderer = $PAGE->get_renderer('block_liketeacher');
        $this->locallib = new block_liketeacher_manager();

        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_liketeacher');
        $this->toplimit = $this->showtop = $this->showlikedcounter = $this->showfavorite = 0;
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {
        global $USER,$CFG,$DB,$ADMIN;
        $userid = optional_param('id', 0, PARAM_INT);
        $userid = $userid ? $userid : $USER->id;

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();



        $text = html_writer::start_tag('div', array('class'=>'liketeacher'));
        if($this->showfavorite){
            if($userid == $USER->id){
                $teachers = $this->locallib->get_all_enrolled_courses_teachers($userid);
                $text .= $this->renderer->selectteacher($teachers);
            }
            $likedList = $this->locallib->get_all_liked_teachers($userid);
            $text .= $this->renderer->likedTeacherList($likedList, $userid == $USER->id);
        }

        if($this->showlikedcounter){
            $totalLike = $DB->count_records('block_liketeacher', array('votetype'=>'like','candidateid'=>$userid));
            $text .= $this->renderer->countLike($totalLike);
        }

        if($this->showtop){
            $limit = $this->toplimit ? $this->toplimit : 10;
            $likedTeacher = $this->locallib->get_most_liked_teachers($limit);
            $text .= $this->renderer->mostLikedTeacherList($likedTeacher);
        }

        $text .= html_writer::end_tag('div');
        $this->content->text = $text;
        $this->content->footer = '';
        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediatly after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_liketeacher');
        } else {
            $this->title = $this->config->title;
        }

        $this->showfavorite = empty($this->config->showfavorite) ? 0 : $this->config->showfavorite;
        $this->showlikedcounter = empty($this->config->showlikedcounter) ? 0 : $this->config->showlikedcounter;
        $this->showtop = empty($this->config->showtop) ? 0 : $this->config->showtop;
        $this->toplimit = empty($this->config->toplimit) ? 0 : $this->config->toplimit;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return array(
            'user' => true,
            'profile' => true,
        );
    }

    /**
     * Allow multiple instances in a single course?
     *
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple() {
        return true;
    }
}
