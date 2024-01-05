#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
include dirname(__FILE__, 4) . '/file/test/file.class.php';
su('admin');

zdTable('action')->gen(2);
zdTable('file')->gen(1);

/**

title=测试 actionModel->updateComment();
timeout=0
cid=1

- 测试更新action 1的备注, 备注被成功更新为备注1属性comment @备注1
- 测试更新action 2的备注, 备注被成功更新为备注2属性comment @备注2
- 测试文件是否更新成功, 文件的objectType和objectID被更新为story和2
 - 属性objectType @story
 - 属性objectID @2

*/

$actionIDList = array('1', '2');
$commentList  = array('备注1', '备注2', '<p><img src="uupdatecomment.php?m=file&amp;f=read&amp;t=jpeg&amp;fileID=1"></p>');
$uidList      = array('', uniqid());

$action = new actionTest();
$file   = new fileTest();

r($action->updateCommentTest($actionIDList[0], $commentList[0], $uidList[0])) && p('comment')             && e('备注1');    // 测试更新action 1的备注, 备注被成功更新为备注1
r($action->updateCommentTest($actionIDList[1], $commentList[1], $uidList[1])) && p('comment')             && e('备注2');    // 测试更新action 2的备注, 备注被成功更新为备注2
r($file->getByIdTest(1))                                                      && p('objectType;objectID') && e('story;2');  //测试文件是否更新成功, 文件的objectType和objectID被更新为story和2