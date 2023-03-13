#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';
su('admin');

/**

title=bugModel->batchConfirm();
cid=1
pid=1

批量确认1 3 4 51 81 >> ,1,1,1,1,1
批量确认2 7 >> ,1,1

*/

$bugIDlist1 = array('1','3','4','51','81');

$bugIDlist2 = array('2', '7');

$bug = new bugTest();
r($bug->batchConfirmTest($bugIDlist1))  && p() && e(',1,1,1,1,1'); // 批量确认1 3 4 51 81
r($bug->batchConfirmTest($bugIDlist2))  && p() && e(',1,1');       // 批量确认2 7
