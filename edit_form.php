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
 * Form for editing liketeacher block instances.
 *
 * @package     block_liketeacher
 * @copyright   2017 Dony Ariesta <donyariesta.rin@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Form for editing block_liketeacher block instances.
 *
 * @package    block_liketeacher
 * @copyright  2017 Dony Ariesta <donyariesta.rin@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot . '/blocks/liketeacher/locallib.php');
class block_liketeacher_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for block_liketeacher.
     */
    protected function specific_definition($mform) {
        $yesNoOpt = array(
            get_string('no', 'block_liketeacher'),
            get_string('yes', 'block_liketeacher')
        );
        $locallib = new block_liketeacher_manager();
        // Section header title.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_liketeacher'));
        $mform->setDefault('config_title', 'Liked Teacher');
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('select', 'config_showfavorite', get_string('blockshowfavorite', 'block_liketeacher'), $yesNoOpt);
        $mform->setDefault('config_showfavorite', 0);
        $mform->setType('config_showfavorite', PARAM_INT);

        $mform->addElement('select', 'config_showlikedcounter', get_string('blockshowliked', 'block_liketeacher'),$yesNoOpt);
        $mform->setDefault('config_showlikedcounter', 0);
        $mform->setType('config_showlikedcounter', PARAM_INT);

        $mform->addElement('select', 'config_showtop', get_string('blockshowtop', 'block_liketeacher'),$yesNoOpt);
        $mform->setDefault('config_showtop', 0);
        $mform->setType('config_showtop', PARAM_INT);

        $mform->addElement('text', 'config_toplimit', get_string('toplimit', 'block_liketeacher'));
        $mform->setDefault('config_toplimit', 0);
        $mform->setType('config_toplimit', PARAM_INT);

        // $mform->addElement('select', 'top_teacher', get_string('forumtype', 'forum'), $FORUM_TYPES, $attributes);
        //
        // $teachers = $locallib->get_all_not_top_teachers();
        // $optTeacher =
        // if($teachers){
        //     foreach($teachers as $teacher){
        //
        //     }
        // }
        // $html = "
        //   <table  class=' generaltable generalbox boxaligncenter' cellspacing='0'>
        //     <tr>
        //       <td id='existingcell'>
        //           <p><label for='removeselect'>Top Teacher</label></p>
        //
        //       </td>
        //       <td id='buttonscell'>
        //           <div id='addcontrols'>
        //               <input name='add' id='add' type='submit'  title='add' /><br />
        //           </div>
        //
        //           <div id='removecontrols'>
        //               <input name='remove' id='remove' type='submit' title='remove' />
        //           </div>
        //       </td>
        //       <td id='potentialcell'>
        //           <p><label for='addselect'>Teacher</label></p>
        //
        //       </td>
        //     </tr>
        //   </table>
        //
        // ";
        // $mform->addElement('html', $html);
        // Please keep in mind that all elements defined here must start with 'config_'.

    }
}
