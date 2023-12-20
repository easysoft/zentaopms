#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->addCreateAction4Story();
cid=1

- 判断是否生成了创建的历史记录，并且判断生成的数据是否正确。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

zdTable('story')->gen(10);
zdTable('action')->config('action')->gen(10);

$versionList = array();

$upgrade->addCreateAction4StoryTest('18_5');

global $tester;

$actionList = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectType')->eq('story')->andWhere('action')->eq('opened')->fetchAll();

$check = true;
foreach ($actionList as $action)
{
    $originAction = $tester->dao->select('*')->from(TABLE_ACTION)->where('objectID')->eq($action->objectID)->andWhere('objectType')->eq('story')->andWhere('action')->eq('linked2release')->fetch();
    if(!$originAction)
    {
        $check = false;
        break;
    }
    if(strtotime($originAction->date) - strtotime($action->date) !== 1)
    {
        $check = false;
        break;
    }
}

r($check) && p('') && e(1);  //判断是否生成了创建的历史记录，并且判断生成的数据是否正确。
