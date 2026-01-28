#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);
zenData('product')->gen(10);

zenData('bug')->loadYaml('bug_resolve')->gen(7);
zenData('project')->loadYaml('project_resolve')->gen(10);
zenData('kanbanregion')->loadYaml('kanbanregion_resolve')->gen(1);
zenData('kanbanlane')->loadYaml('kanbanlane_resolve')->gen(1);
zenData('kanbancolumn')->loadYaml('kanbancolumn_resolve')->gen(9);
zenData('kanbancell')->loadYaml('kanbancell_resolve')->gen(9);

su('admin');

/**

title=bugModel->resolve();
timeout=0
cid=15405

- 测试解决原因为设计如此的bug
 - 属性resolution @bydesign
 - 属性assignedTo @user99
- 测试解决原因为重复bug 有重复bugID的bug
 - 属性resolution @duplicate
 - 属性assignedTo @user99
 - 属性duplicateBug @1
- 测试解决原因为解决 有解决版本的bug
 - 属性resolution @fixed
 - 属性assignedTo @user99
 - 属性resolvedBuild @1
- 测试解决原因为设计如此的bug 传入output
 - 属性resolution @bydesign
 - 属性assignedTo @user99
- 测试解决原因为重复bug 有重复bugID的bug 传入output @重复Bug不存在。
- 测试解决原因为解决 有解决版本的bug 传入output
 - 属性resolution @fixed
 - 属性assignedTo @user99
 - 属性resolvedBuild @1
- 测试解决原因为空的bug @『解决方案』不能为空。
- 测试解决原因为重复bug 无重复bugID的bug @『重复Bug』不能为空。
- 测试解决原因为解决 无解决版本的bug @『解决版本』不能为空。

*/

$bugIdList = array(1, 2, 3, 4, 5, 6, 7);

/* Normal condition. */
$bydesignBug  = array('resolution' => 'bydesign');
$duplicateBug = array('resolution' => 'duplicate', 'duplicateBug' => 1);
$fixedBug     = array('resolution' => 'fixed', 'resolvedBuild' => 1);

/* Error condition. */
$emptyResulution   = array('resolution' => '');
$empthDuplicateBug = array('resolution' => 'duplicate');
$emptyFixedBug     = array('resolution' => 'fixed');

$output = array('fromColID' => 1, 'toColID' => 2, 'fromLaneID' => 1, 'toLaneID' => 1);

$bug = new bugModelTest();
r($bug->resolveTest($bugIdList[0], $bydesignBug))  && p('resolution,assignedTo')               && e('bydesign,user99');    // 测试解决原因为设计如此的bug
r($bug->resolveTest($bugIdList[1], $duplicateBug)) && p('resolution,assignedTo,duplicateBug')  && e('duplicate,user99,1'); // 测试解决原因为重复bug 有重复bugID的bug
r($bug->resolveTest($bugIdList[2], $fixedBug))     && p('resolution,assignedTo,resolvedBuild') && e('fixed,user99,1');     // 测试解决原因为解决 有解决版本的bug

r($bug->resolveTest($bugIdList[3], $bydesignBug, $output))  && p('resolution,assignedTo') && e('bydesign,user99'); // 测试解决原因为设计如此的bug 传入output
r($bug->resolveTest($bugIdList[4], $duplicateBug, $output)) && p('') && e('重复Bug不存在。'); // 测试解决原因为重复bug 有重复bugID的bug 传入output
r($bug->resolveTest($bugIdList[5], $fixedBug, $output))     && p('resolution,assignedTo,resolvedBuild') && e('fixed,user99,1'); // 测试解决原因为解决 有解决版本的bug 传入output

r($bug->resolveTest($bugIdList[6], $emptyResulution))   && p() && e('『解决方案』不能为空。'); // 测试解决原因为空的bug
r($bug->resolveTest($bugIdList[6], $empthDuplicateBug)) && p() && e('『重复Bug』不能为空。');  // 测试解决原因为重复bug 无重复bugID的bug
r($bug->resolveTest($bugIdList[6], $emptyFixedBug))     && p() && e('『解决版本』不能为空。'); // 测试解决原因为解决 无解决版本的bug