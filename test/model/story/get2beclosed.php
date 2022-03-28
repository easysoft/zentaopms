<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->get2BeClosed();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('story');
$toBeClosed1 = $tester->story->get2BeClosed(1, 0, array(), 'story', 'id_desc');
$toBeClosed2 = $tester->story->get2BeClosed(2, 0, array(), 'story', 'id_asc');

r() && p() && e();
