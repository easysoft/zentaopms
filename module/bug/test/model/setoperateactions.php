#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('admin');

/**

title=bugTao->setOperateActions();
timeout=0
cid=1

- 列表页面的确认操作按钮第confirm条的icon属性 @icon-ok

- 列表页面的解决操作按钮第resolve条的icon属性 @icon-checked

- 列表页面的关闭操作按钮第close条的icon属性 @icon-off

- 列表页面的编辑操作按钮第edit条的icon属性 @icon-edit

- 列表页面的复制操作按钮第copy条的icon属性 @icon-copy

*/

$bug = new bugTest();
r($bug->setOperateActionsTest('browse')) && p('confirm:icon') && e('icon-ok');      //列表页面的确认操作按钮
r($bug->setOperateActionsTest('browse')) && p('resolve:icon') && e('icon-checked'); //列表页面的解决操作按钮
r($bug->setOperateActionsTest('browse')) && p('close:icon')   && e('icon-off');     //列表页面的关闭操作按钮
r($bug->setOperateActionsTest('browse')) && p('edit:icon')    && e('icon-edit');    //列表页面的编辑操作按钮
r($bug->setOperateActionsTest('browse')) && p('copy:icon')    && e('icon-copy');    //列表页面的复制操作按钮