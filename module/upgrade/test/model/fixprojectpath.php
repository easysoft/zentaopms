#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->fixProjectPath();
cid=1

- 测试项目的路径是否被成功修复 @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

zdTable('project')->config('project_for_fix_path')->gen(5);

$programList = array(1, 2, 3, 4, 5);

$upgrade->fixProjectPath($programList[0]);
$upgrade->fixProjectPath($programList[1]);
$upgrade->fixProjectPath($programList[2]);
$upgrade->fixProjectPath($programList[3]);
$upgrade->fixProjectPath($programList[4]);

global $tester;
$projectList = $tester->dao->select('id, path')->from(TABLE_PROJECT)->fetchPairs('id');

$check = true;
foreach($programList as $programID)
{
    if(!$projectList[$programID])
    {
        $check = false;
        break;
    }

    if($projectList[$programID] !== sprintf(",%s,%s,", $programID, $programID))
    {
        $check = false;
        break;
    }
}

r($check) && p('') && e('1');  //测试项目的路径是否被成功修复
