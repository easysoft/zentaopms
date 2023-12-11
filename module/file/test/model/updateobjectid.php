#!/usr/bin/env php
<?php
/**

title=测试 fileModel->updateObjectID();
cid=0

- 测试更新 id 1 的objectID 为 101 objectType 为 bug
 - 第1条的id属性 @1
 - 第1条的objectID属性 @101
 - 第1条的objectType属性 @bug
 - 第1条的extra属性 @editor
- 测试更新 id 2 的objectID 为 102 objectType 为 story
 - 第2条的id属性 @2
 - 第2条的objectID属性 @102
 - 第2条的objectType属性 @story
 - 第2条的extra属性 @editor
- 测试更新 id 5 的objectID 为 103 objectType 为 task
 - 第5条的id属性 @5
 - 第5条的objectID属性 @103
 - 第5条的objectType属性 @task
 - 第5条的extra属性 @editor
- 测试更新 id 6 的objectID 为 104 objectType 为 traincourse
 - 第6条的id属性 @6
 - 第6条的objectID属性 @104
 - 第6条的objectType属性 @traincourse
 - 第6条的extra属性 @editor
- 测试更新 id 9 的objectID 为 105 objectType 为 testcase
 - 第9条的id属性 @9
 - 第9条的objectID属性 @105
 - 第9条的objectType属性 @testcase
 - 第9条的extra属性 @editor

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

zdTable('file')->gen(20);

$uid = '98390890341';
$albums1 = array('used' => array($uid => array('1')));
$albums2 = array('used' => array($uid => array('2', '3')));
$albums3 = array('used' => array($uid => array('4', '5')));
$albums4 = array('used' => array($uid => array('6', '7', '8')));
$albums5 = array('used' => array($uid => array('9', '10', '11')));

$objectID   = array(101, 102, 103, 104, 105);
$objectType = array('bug', 'story', 'task', 'traincourse', 'testcase');

$file = new fileTest();

r($file->updateObjectIDTest($uid, $objectID[0], $objectType[0], $albums1)) && p('1:id,objectID,objectType,extra') && e('1,101,bug,editor');         // 测试更新 id 1 的objectID 为 101 objectType 为 bug
r($file->updateObjectIDTest($uid, $objectID[1], $objectType[1], $albums2)) && p('2:id,objectID,objectType,extra') && e('2,102,story,editor');       // 测试更新 id 2 的objectID 为 102 objectType 为 story
r($file->updateObjectIDTest($uid, $objectID[2], $objectType[2], $albums3)) && p('5:id,objectID,objectType,extra') && e('5,103,task,editor');        // 测试更新 id 5 的objectID 为 103 objectType 为 task
r($file->updateObjectIDTest($uid, $objectID[3], $objectType[3], $albums4)) && p('6:id,objectID,objectType,extra') && e('6,104,traincourse,editor'); // 测试更新 id 6 的objectID 为 104 objectType 为 traincourse
r($file->updateObjectIDTest($uid, $objectID[4], $objectType[4], $albums5)) && p('9:id,objectID,objectType,extra') && e('9,105,testcase,editor');    // 测试更新 id 9 的objectID 为 105 objectType 为 testcase
