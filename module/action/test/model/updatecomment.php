#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__, 4) . '/file/test/lib/model.class.php';
su('admin');

zenData('action')->gen(2);
zenData('actionrecent')->gen(0);
zenData('file')->gen(1);

/**

title=测试 actionModel->updateComment();
timeout=0
cid=14935

- 测试更新action 1的备注, 备注被成功更新为备注1属性comment @备注1
- 测试更新action 2的备注, 备注被成功更新为备注2属性comment @备注2
- 查看更新后的文件
 - 属性objectType @story
 - 属性objectID @2
 - 属性name @文件标题1

*/

$actionIDList = array('1', '2');
$commentList  = array('备注1', '备注2', '<p><img src="uupdatecomment.php?m=file&amp;f=read&amp;t=jpeg&amp;fileID=1"></p>');
$uidList      = array('', uniqid());

$action = new actionModelTest();
$file   = new fileModelTest();

r($action->updateCommentTest($actionIDList[0], $commentList[0], $uidList[0])) && p('comment')  && e('备注1'); // 测试更新action 1的备注, 备注被成功更新为备注1
r($action->updateCommentTest($actionIDList[1], $commentList[1], $uidList[1])) && p('comment')  && e('备注2'); // 测试更新action 2的备注, 备注被成功更新为备注2
r($file->getByIdTest(1)) && p('objectType;objectID;name') && e('story;2;文件标题1');  // 查看更新后的文件
