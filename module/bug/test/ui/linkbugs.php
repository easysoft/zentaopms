#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/linkbugs.ui.class.php';

/**

title=关联bug测试
timeout=0
cid=1

- 关联bug测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @关联bug成功

*/

zenData('product')->loadYaml('product')->gen(1);
$bug = zenData('bug');
$bug->project->range('0');
$bug->product->range('1');
$bug->module->range('0');
$bug->execution->range('0');
$bug->openedBuild->range('trunk');
$bug->assignedTo->range('admin');
$bug->gen(3);

$product = array();
$product['productID'] = 1;

$bugs = zenData('bug')->dao->select('id, title')->from(TABLE_BUG)->fetchAll();

$tester = new linkBugsTester();
r($tester->linkBugs($product, $bugs[0], array_slice($bugs,1))) && p('status,message') && e('SUCCESS,关联bug成功'); //关联bug测试
$tester->closeBrowser();
