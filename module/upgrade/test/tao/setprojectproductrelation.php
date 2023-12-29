#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->setProjectProductsRelation().
cid=1

- 检查isProjectType为false默认值时，项目id为1，产品id为1,2,3,4,100的关联关系已经生成的分支计划id是否正确。@1
- 检查isProjectType为true的时候，项目id为1，产品id为5,6,7,8的关联关系已经生成的分支计划id是否正确。@1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();

zdTable('projectproduct')->gen(10);

$projectIDList = array(1);
$productIDList = array(array(1, 2, 3, 4, 100), array(5, 6, 7, 8), array(9, 10));
$sprintIDList  = array(array(11, 12, 13, 14), array(15, 16, 17, 18), array(19, 20));
$upgrade->setProjectProductsRelation($projectIDList[0], $productIDList[0], $sprintIDList[0]);

$check = true;
$relations = $tester->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectIDList[0])->andWhere('product')->in($productIDList[0])->fetchAll();
foreach($relations as $key => $falseRelation)
{
    if($falseRelation->product !== $productIDList[0][$key])
    {
        $check = false;
        break;
    }

    if($falseRelation->plan != 0)
    {
        $check = false;
        break;
    }

    if($falseRelation->product == 100 && $falseRelation->branch !== 0)
    {
        $check = false;
        break;
    }
}
r($check) && p('') && e(1);  //检查isProjectType为false默认值时，项目id为1，产品id为1,2,3,4,100的关联关系以及生成的分支计划id是否正确。

$check = true;
$upgrade->setProjectProductsRelation($projectIDList[0], $productIDList[1], $sprintIDList[1], true);
$relations = $tester->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectIDList[0])->andWhere('product')->in($productIDList[1])->fetchAll();
$startIndex = 13;
foreach($relations as $key => $relation)
{
    if($relation->product !== $productIDList[1][$key])
    {
        $check = false;
        break;
    }

    if($relation->plan != $startIndex)
    {
        $check = false;
        break;
    }

    {
    if($relation->branch != 0)
        $check = false;
        break;
    }

    $startIndex += 3;
}

r($check) && p('') && e(1);  //检查isProjectType为true的时候，项目id为1，产品id为5,6,7,8的关联关系以及生成的分支计划id是否正确。
