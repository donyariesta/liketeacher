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
 * block liketeacher renderer.
 *
 * @package    block_liketeacher
 * @copyright  2017 Dony Ariesta <donyariesta.rin@gmail.com>
 * @author     Dony Ariesta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_liketeacher_renderer extends plugin_renderer_base {

    public function countLike($totalLike) {
        $html = '';
        $html .= html_writer::start_tag('div' );
        $html .= get_string('countlike', 'block_liketeacher', (object)['count' => $totalLike]);
        $html .= html_writer::end_tag('div');
        return $html;
    }

    public function likedTeacherList($lists, $allowDelete) {
        global $OUTPUT,$CFG;
        $html = '';
        $html .= '<ul>';
        $uploadURL = new moodle_url('/blocks/liketeacher/givevote.php');
        foreach($lists as $list){
            $html .= '<li>';
            $html .= html_writer::start_tag('a', array('href' => new moodle_url('/user/profile.php', array('id'=>$list->id))));
            $html .= trim($list->firstname .' '. $list->lastname);
            $html .= html_writer::end_tag('a');
            if($allowDelete){
                $html .= html_writer::start_tag('span', array('class'=>'removeliked', 'style'=>"float: right;"));
                $html .= html_writer::start_tag('a', array('href' => '#', 'id' => 'like-delete-'.$list->ltid,'class'=>'btncntrl'));
                $html .= get_string('removeliked', 'block_liketeacher');
                $html .= html_writer::end_tag('a');
                $html .= html_writer::end_tag('span');

            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= html_writer::script(false, new moodle_url($CFG->wwwroot . '/blocks/liketeacher/scripts/main.js'));
        $html .= html_writer::script("
            bindElementBySelector('.liketeacher .removeliked a.btncntrl','click',function(e){
                e.preventDefault();
                var ltid = e.target.id.replace('like-delete-','');
                var fd = new FormData();
                fd.append('ltid', ltid);
                fd.append('votetype', 'remove-like');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '$uploadURL', true);
                xhr.onload = function(){
                    var json = JSON.parse(xhr.response);
                    if(json.status == 'ok'){window.location.href='';}
                }
                xhr.send(fd);
            });
        ");
        return $html;
    }

    public function mostLikedTeacherList($lists) {
        $html = '';
        $html .= '<ul>';
        foreach($lists as $list){
            $html .= '<li>';
            $html .= html_writer::start_tag('a', array('href' => new moodle_url('/user/profile.php', array('id'=>$list->id))));
            $html .= trim($list->firstname .' '. $list->lastname);
            $html .= html_writer::end_tag('a');
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function selectteacher($teachers) {
        global $CFG, $PAGE;
        $pagetype = $PAGE->pagetype;
        $uploadURL = new moodle_url('/blocks/liketeacher/givevote.php');
        $optTeachers = array();
        foreach($teachers as $teacher){
            $optTeachers[$teacher->id] = trim($teacher->firstname.' '.$teacher->lastname);
        }
        $html = '';

        $html .= html_writer::start_tag('form', array('class'=>'selectteacher'));
        $html .= html_writer::select($optTeachers, 'Select Teacher');
        $html .= html_writer::tag('button', get_string('addliked', 'block_liketeacher'),  null);
        $html .= html_writer::end_tag('form');

        $html .= html_writer::script(false, new moodle_url($CFG->wwwroot . '/blocks/liketeacher/scripts/main.js'));
        $html .= html_writer::script("
            bindElementBySelector('.liketeacher .selectteacher','submit',function(e){
                e.preventDefault();
                var parent = findParentBySelector(e.target, '.liketeacher');
                var selectTag = parent.querySelector('.selectteacher select');

                if(selectTag.value != ''){
                    var fd = new FormData();
                    fd.append('candidateid', selectTag.value);
                    fd.append('page', '$pagetype');
                    fd.append('votetype', 'like');
                    fd.append('vote', 1);
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '$uploadURL', true);
                    xhr.onload = function(){
                        var json = JSON.parse(xhr.response);
                        if(json.status == 'ok'){window.location.href='';}
                    }
                    xhr.send(fd);
                }
            });
        ");
        return $html;
    }



}
