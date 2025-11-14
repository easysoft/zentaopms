#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=bugModel->getBugs2Link();
timeout=0
cid=15358

- 获取和 bug1 相同产品下的 bug 列表，第一个 bug 的标题是 BUG9第0条的title属性 @BUG9
- 获取和 bug1 相同产品下的 bug 列表，第二个 bug 的标题是 BUG7第1条的title属性 @BUG7
- 获取和 bug1 相同产品下并且不包含 bug9 的 bug 列表，第一个 bug 的标题是 BUG7第0条的title属性 @BUG7
- 搜索和 bug2 相同产品下的 bug 列表，第一个 bug 的标题是 BUG10第0条的title属性 @BUG10
- 搜索和 bug2 相同产品下并且不包含 bug4 的 bug 列表，第一个 bug 的标题是 BUG8第0条的title属性 @BUG8

*/

function initData()
{
    $bug = zenData('bug');
    $bug->id->range('1-10');
    $bug->product->range('1,2');
    $bug->branch->range('0,1');
    $bug->project->range('0,2');
    $bug->execution->range('0,3');
    $bug->module->range('1,0');
    $bug->title->prefix("BUG")->range('1-10');
    $bug->status->range("resolved,active,closed");
    $bug->plan->range('1,0');
    $bug->assignedTo->range('admin');
    $bug->openedBy->range('admin');
    $bug->resolvedBy->range('admin');
    $bug->confirmed->range('0,1');
    $bug->resolution->range('postponed,fixed');
    $bug->openedBuild->range('trunk');
    $bug->gen(10);
}

initData();

$bugIdList   = array(1,2);
$bySearch    = array(false, true);
$excludeBugs = array('', '9', '10');

global $tester;
$bug = $tester->loadModel('bug');
r(array_values($bug->getBugs2Link($bugIdList[0], $bySearch[0], $excludeBugs[0]))) && p('0:title') && e('BUG9');  //获取和 bug1 相同产品下的 bug 列表，第一个 bug 的标题是 BUG9
r(array_values($bug->getBugs2Link($bugIdList[0], $bySearch[0], $excludeBugs[0]))) && p('1:title') && e('BUG7');  //获取和 bug1 相同产品下的 bug 列表，第二个 bug 的标题是 BUG7
r(array_values($bug->getBugs2Link($bugIdList[0], $bySearch[0], $excludeBugs[1]))) && p('0:title') && e('BUG7');  //获取和 bug1 相同产品下并且不包含 bug9 的 bug 列表，第一个 bug 的标题是 BUG7
r(array_values($bug->getBugs2Link($bugIdList[1], $bySearch[1], $excludeBugs[0]))) && p('0:title') && e('BUG10'); //搜索和 bug2 相同产品下的 bug 列表，第一个 bug 的标题是 BUG10
r(array_values($bug->getBugs2Link($bugIdList[1], $bySearch[1], $excludeBugs[2]))) && p('0:title') && e('BUG8');  //搜索和 bug2 相同产品下并且不包含 bug4 的 bug 列表，第一个 bug 的标题是 BUG8
