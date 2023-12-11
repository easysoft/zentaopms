#!/usr/bin/env php
<?php

/**

title=测试 fileModel->saveExportTemplate();
cid=0

- 测试新建 公共 bug 模板
 - 属性title @新建的公共bug模板
 - 属性public @1
- 测试新建 私有 bug 模板
 - 属性title @新建的私有bug模板
 - 属性public @0
- 测试新建 公共 task 模板
 - 属性title @新建的公共task模板
 - 属性public @1
- 测试新建 私有 task 模板
 - 属性title @新建的私有task模板
 - 属性public @0
- 测试新建 重名 模板 @『模板名称』已经有『新建的公共bug模板』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/file.class.php';
su('admin');

zdTable('usertpl')->gen(0);
$module = array('bug', 'task');

$file1 = new stdclass();
$file1->title   = '新建的公共bug模板';
$file1->content = array('id', 'product', 'branch', 'module', 'project', 'execution', 'story', 'task', 'title', 'keywords', 'severity', 'pri', 'type', 'os', 'browser', 'steps', 'status', 'deadline', 'activatedCount', 'confirmed', 'mailto', 'openedBy', 'openedDate', 'openedBuild', 'assignedTo', 'assignedDate', 'resolvedBy', 'resolution', 'resolvedBuild', 'resolvedDate', 'closedBy', 'closedDate', 'duplicateBug', 'linkBug', 'case', 'lastEditedBy', 'lastEditedDate', 'files', 'identify');
$file1->public  = 1;

$file2 = new stdclass();
$file2->title   = '新建的私有bug模板';
$file2->content = array('id', 'product', 'branch', 'module', 'project', 'execution', 'story', 'task', 'title', 'keywords', );
$file2->public  = 0;

$file3 = new stdclass();
$file3->title   = '新建的公共task模板';
$file3->content = array('id', 'product', 'type', 'status', 'deadline', 'openedBy', 'openedDate', 'openedBuild', 'assignedTo', 'assignedDate', 'closedBy', 'closedDate', 'duplicateBug', 'linkBug', 'case', 'lastEditedBy', 'lastEditedDate');
$file3->public  = 1;

$file4 = new stdclass();
$file4->title   = '新建的私有task模板';
$file4->content = array('id', 'product', 'type', 'status', 'deadline', 'lastEditedBy', 'lastEditedDate');
$file4->public  = 0;

$file5 = new stdclass();
$file5->title   = '新建的公共bug模板';
$file5->content = array('id', 'product', 'branch', 'module', 'project', 'execution', 'story', 'task', 'title', 'keywords', 'severity', 'pri', 'type', 'os', 'browser', 'steps', 'status', 'deadline', 'activatedCount', 'confirmed', 'mailto', 'openedBy', 'openedDate', 'openedBuild', 'assignedTo', 'assignedDate', 'resolvedBy', 'resolution', 'resolvedBuild', 'resolvedDate', 'closedBy', 'closedDate', 'duplicateBug', 'linkBug', 'case', 'lastEditedBy', 'lastEditedDate', 'files', 'identify');
$file5->public  = 1;

$file = new fileTest();

r($file->saveExportTemplateTest($module[0], $file1)) && p('title,public') && e('新建的公共bug模板,1');  // 测试新建 公共 bug 模板
r($file->saveExportTemplateTest($module[0], $file2)) && p('title,public') && e('新建的私有bug模板,0');  // 测试新建 私有 bug 模板
r($file->saveExportTemplateTest($module[1], $file3)) && p('title,public') && e('新建的公共task模板,1'); // 测试新建 公共 task 模板
r($file->saveExportTemplateTest($module[1], $file4)) && p('title,public') && e('新建的私有task模板,0'); // 测试新建 私有 task 模板
r($file->saveExportTemplateTest($module[0], $file5)) && p()               && e('『模板名称』已经有『新建的公共bug模板』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试新建 重名 模板
