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
 * block liketeacher locallib.
 *
 * @package    block_liketeacher
 * @copyright  2017 Dony Ariesta <donyariesta.rin@gmail.com>
 * @author     Dony Ariesta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_liketeacher_manager {

    function get_all_enrolled_courses_teachers($userid, $notInVote='like') {
        global $DB;
        $params['contextlevel'] = CONTEXT_COURSE;
        $params['archetype'] = 'editingteacher';

        $sql = "
        SELECT u.id, u.firstname,u.lastname, u.email FROM
        (
        	SELECT u.userid,ra.roleid
        	FROM (
        		SELECT ue.userid,ctx.id
        		FROM (
        			SELECT DISTINCT e.courseid FROM
        			( SELECT enrolid FROM {user_enrolments} WHERE userid = {$userid} ) u
        			JOIN {enrol} e ON u.enrolid = e.id
        		) c
        		JOIN {enrol} e ON c.courseid = e.courseid
        		JOIN {context} ctx ON (ctx.instanceid = c.courseid AND ctx.contextlevel = :contextlevel)
        		JOIN {user_enrolments} ue ON (e.id = ue.enrolid AND ue.userid != {$userid})
        	) u
        	JOIN {role_assignments} ra ON ra.userid = u.userid and ra.contextid = u.id
        	JOIN {role} r ON ra.roleid = r.id AND r.archetype = :archetype
        ) t

        JOIN {user} u ON (
        	t.userid = u.id
            AND u.id NOT IN (
        		SELECT candidateid FROM mdl_block_liketeacher WHERE voterid = {$userid} AND votetype='{$notInVote}'
        	)
        )

        ";
        $teachers = $DB->get_records_sql($sql, $params);
        return $teachers;
    }
    function get_all_liked_teachers($userid) {
        global $DB;

        $sql = "
        SELECT u.id, u.firstname,u.lastname, u.email,lt.id as ltid FROM
        {block_liketeacher} lt
        JOIN {user} u ON lt.candidateid = u.id
        WHERE lt.voterid = {$userid}
        AND lt.votetype = 'like'
        ";
        $list = $DB->get_records_sql($sql);
        return $list;
    }

    function get_all_not_top_teachers() {
        global $DB;
        $params['archetype'] = array('archetype'=>'editingteacher');
        $sql = "
        SELECT u.id, u.firstname, u.lastname, u.email
        FROM (
        	SELECT DISTINCT ra.userid
        	FROM {role_assignments} ra
        	JOIN {role} r ON ra.roleid = r.id AND r.archetype = :archetype
        ) t
        JOIN {user} u ON u.id = t.userid AND u.id NOT IN (SELECT id FROM {block_liketeacher_topbyadmin})
        ";
        $list = $DB->get_records_sql($sql, $params);
        return $list;
    }

    function get_most_liked_teachers($limit = 10) {
        global $DB;

        $sql = "
        SELECT u.id, u.firstname, u.lastname, u.email
        FROM (SELECT count(id) as counter,candidateid FROM {block_liketeacher} LIMIT $limit) lt
        JOIN {user} u ON lt.candidateid = u.id
        ORDER BY lt.counter DESC
        ";
        $list = $DB->get_records_sql($sql);
        return $list;
    }



}
