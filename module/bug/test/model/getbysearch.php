#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=bugModel->getBySearch();
timeout=0
cid=15361

- 获取产品1 下的全部 bug 列表，查看数量是否正确 @5
- 获取产品2 下的全部 bug 列表，查看数量是否正确 @5
- 获取产品1,2 下的全部 bug 列表，查看数量是否正确 @10
- 获取不存在的产品下的全部 bug 列表，查看数量是否正确 @0
- 获取产品1 分支0 下的全部 bug 列表，查看数量是否正确 @5
- 获取产品1 分支0 下的 bug 列表，查看数量是否正确 @0
- 获取产品1 不存在的分支下的全部 bug 列表，查看数量是否正确 @0
- 获取产品1 不存在的分支下的全部 bug 列表，查看数量是否正确 @0
- 获取产品1 项目2 下的全部 bug 列表，查看数量是否正确 @5
- 获取产品1 不存在项目下的全部 bug 列表，查看数量是否正确 @5
- 获取产品1 下的不包含 bug1 的 bug 列表，查看数量是否正确 @4
- 获取产品1 下不包含 bug1 bug2 的全部 bug 列表，查看数量是否正确 @3
- 获取产品1 下的 bug 列表，查看第一个 bug 的名称是否正确第1条的title属性 @BUG1
- 获取产品2 下的 bug 列表，查看第一个 bug 的名称是否正确第2条的title属性 @BUG2
- 获取产品1,2 下的 bug 列表，查看第一个和第二个 bug 的名称是否正确
 - 第1条的title属性 @BUG1
 - 第2条的title属性 @BUG2

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

$productIdList = array(array(1), array(2), array(1,2), array(1000001));
$branch        = array('all', 0, 1, 1000001);
$projectID     = array(0, 2, 1000001);
$excludeBugs   = array('', '1', '1,3');

global $tester;
$bug = $tester->loadModel('bug');

r(count($bug->getBySearch('bug', $productIdList[0], $branch[0], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('5');  //获取产品1 下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[1], $branch[0], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('5');  //获取产品2 下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[2], $branch[0], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('10'); //获取产品1,2 下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[3], $branch[0], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('0');  //获取不存在的产品下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[1], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('5');  //获取产品1 分支0 下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[2], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('0');  //获取产品1 分支0 下的 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[3], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('0');  //获取产品1 不存在的分支下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[3], $projectID[0], 0, 0, $excludeBugs[0]))) && p('') && e('0');  //获取产品1 不存在的分支下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[0], $projectID[1], 0, 0, $excludeBugs[0]))) && p('') && e('5');  //获取产品1 项目2 下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[0], $projectID[2], 0, 0, $excludeBugs[0]))) && p('') && e('5');  //获取产品1 不存在项目下的全部 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[0], $projectID[0], 0, 0, $excludeBugs[1]))) && p('') && e('4');  //获取产品1 下的不包含 bug1 的 bug 列表，查看数量是否正确
r(count($bug->getBySearch('bug', $productIdList[0], $branch[0], $projectID[0], 0, 0, $excludeBugs[2]))) && p('') && e('3');  //获取产品1 下不包含 bug1 bug2 的全部 bug 列表，查看数量是否正确

r($bug->getBySearch('bug', $productIdList[0], $branch[0], $projectID[0], 0, 0, $excludeBugs[0])) && p('1:title')              && e('BUG1');      //获取产品1 下的 bug 列表，查看第一个 bug 的名称是否正确
r($bug->getBySearch('bug', $productIdList[1], $branch[0], $projectID[0], 0, 0, $excludeBugs[0])) && p('2:title')              && e('BUG2');      //获取产品2 下的 bug 列表，查看第一个 bug 的名称是否正确
r($bug->getBySearch('bug', $productIdList[2], $branch[0], $projectID[0], 0, 0, $excludeBugs[0])) && p('1:title;2:title', ';') && e('BUG1;BUG2'); //获取产品1,2 下的 bug 列表，查看第一个和第二个 bug 的名称是否正确