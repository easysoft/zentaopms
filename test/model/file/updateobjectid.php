#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/file.class.php';
su('admin');

/**

title=测试 fileModel->updateObjectID();
cid=1
pid=1

测试更新 id 1 的objectID 为 101 objectType 为 bug >> 1,101,bug
测试更新 id 2 3 的objectID 为 102 objectType 为 story >> 2,102,story;3,102,story
测试更新 id 4 5 的objectID 为 103 objectType 为 task >> 4,103,task;5,103,task
测试更新 id 6 7 8 的objectID 为 104 objectType 为 traincourse >> 6,104,traincourse;7,104,traincourse;8,104,traincourse
测试更新 id 9 10 11 的objectID 为 105 objectType 为 testcase >> 9,105,testcase;10,105,testcase;11,105,testcase

*/
$uid = '98390890341';
$albums1 = array('used' => array($uid => array('1')));
$albums2 = array('used' => array($uid => array('2', '3')));
$albums3 = array('used' => array($uid => array('4', '5')));
$albums4 = array('used' => array($uid => array('6', '7', '8')));
$albums5 = array('used' => array($uid => array('9', '10', '11')));

$objectID   = array(101, 102, 103, 104, 105);
$objectType = array('bug', 'story', 'task', 'traincourse', 'testcase');

$file = new fileTest();

r($file->updateObjectIDTest($uid, $objectID[0], $objectType[0], $albums1)) && p('1:id,objectID,objectType')                                                     && e('1,101,bug');                                             // 测试更新 id 1 的objectID 为 101 objectType 为 bug
r($file->updateObjectIDTest($uid, $objectID[1], $objectType[1], $albums2)) && p('2:id,objectID,objectType;3:id,objectID,objectType')                            && e('2,102,story;3,102,story');                               // 测试更新 id 2 3 的objectID 为 102 objectType 为 story
r($file->updateObjectIDTest($uid, $objectID[2], $objectType[2], $albums3)) && p('4:id,objectID,objectType;5:id,objectID,objectType')                            && e('4,103,task;5,103,task');                                 // 测试更新 id 4 5 的objectID 为 103 objectType 为 task
r($file->updateObjectIDTest($uid, $objectID[3], $objectType[3], $albums4)) && p('6:id,objectID,objectType;7:id,objectID,objectType;8:id,objectID,objectType')   && e('6,104,traincourse;7,104,traincourse;8,104,traincourse'); // 测试更新 id 6 7 8 的objectID 为 104 objectType 为 traincourse
r($file->updateObjectIDTest($uid, $objectID[4], $objectType[4], $albums5)) && p('9:id,objectID,objectType;10:id,objectID,objectType;11:id,objectID,objectType') && e('9,105,testcase;10,105,testcase;11,105,testcase');        // 测试更新 id 9 10 11 的objectID 为 105 objectType 为 testcase
