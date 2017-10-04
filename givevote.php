<?php

require_once(__DIR__ . '/../../config.php');

$vote = new stdClass();
$vote->candidateid = isset($_POST['candidateid']) ? $_POST['candidateid'] : NULL;
$vote->votetype = isset($_POST['votetype']) ? $_POST['votetype'] : NULL;
$vote->voterid = $USER->id;

switch($_POST['votetype']){
    case 'like':
        $DB->delete_records('block_liketeacher', (array) $vote);
        $vote->page = $_POST['page'];
        $vote->vote = $_POST['vote'];
        $DB->insert_record('block_liketeacher', $vote);
    break;
    case 'remove-like':
        $delete = $DB->delete_records('block_liketeacher', array('id'=> $_POST['ltid']));
    break;
}

ajax_check_captured_output();
echo json_encode(array('status'=>'ok'));
