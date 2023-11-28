#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 dataviewModel::mergeFields();
timeout=0
cid=1

- 测试合并字段。
 - 第0条的id属性 @id
 - 第0条的title属性 @title
 - 第1条的id属性 @bug
 - 第1条的title属性 @bug

*/
global $tester;
$tester->loadModel('dataview');

r($tester->dataview->mergeFields(array('id', 'title'), array('zt_bug.id', 'zt_bug.title'), array('zt_bug' => 'bug'))) && p('0:id;0:title;1:id;1:title')  && e('id,title,bug,bug');  //测试合并字段。
