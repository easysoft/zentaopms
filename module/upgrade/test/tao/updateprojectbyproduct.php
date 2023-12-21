#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeTao->updateProjectByProduct()。
cid=1

- 测试根据产品迁移数据至id为1的项目下是否成功。@1
- 测试根据产品迁移数据至id为1的项目下是否成功。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$types = array('bug', 'testreport', 'testsuite', 'release');

foreach ($types as $type) zdTable($type)->config($type . '_mergedata')->gen(4);

$upgrade = new upgradeTest();

$projectIDList = array(1, 2);
$productIDList = array(array(1, 2), array(3, 4));

$upgrade->updateProjectByProduct($projectIDList[0], $productIDList[0]);

function checkResult()
{
    global $tester;
    $bugs        = $tester->dao->select('*')->from(TABLE_BUG)->where('project')->eq(1)->fetchAll();
    $testreports = $tester->dao->select('*')->from(TABLE_TESTREPORT)->where('project')->eq(1)->fetchAll();
    $testsuites  = $tester->dao->select('*')->from(TABLE_TESTSUITE)->where('project')->eq(1)->fetchAll();
    $releases    = $tester->dao->select('*')->from(TABLE_RELEASE)->where('project')->eq(1)->fetchAll();

    if(count($bugs) !== 2) return false;
    if(array_filter($bugs, function($bug){return $bug->project != 1;})) return false;

    if(count($testreports) !== 2) return false;
    if(array_filter($testreports, function($testreport){return $testreport->project != 1;})) return false;

    if(count($testsuites) !== 2) return false;
    if(array_filter($testsuites, function($testsuite){return $testsuite->project != 1;})) return false;

    if(count($releases) !== 2) return false;
    if(array_filter($releases, function($release){return $release->project != 1;})) return false;

    return true;
}

r(checkResult()) && p('') && e(1);  //测试根据产品迁移数据至id为1的项目下是否成功。


$upgrade->updateProjectByProduct($projectIDList[0], $productIDList[0]);
r(checkResult()) && p('') && e(2);  //测试根据产品迁移数据至id为1的项目下是否成功。
