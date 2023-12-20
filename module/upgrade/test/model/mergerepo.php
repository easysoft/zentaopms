#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->mergeRepo();
cid=1

- 更新id为1的repo的product字段为1,2，测试是否更新成功 @1,2
- 更新id为2的repo的product字段为3,4，测试是否更新成功 @3,4

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('repo')->gen(2);

$products = array('1,2', '3,4');
$repoList = array(array(1), array(2));

$upgrade = new upgradeTest();

$upgrade->mergeRepo($repoList[0], $products[0]);
$upgrade->mergeRepo($repoList[1], $products[1]);

global $tester;

$repo1 = $tester->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoList[0][0])->fetch();
$repo2 = $tester->dao->select('*')->from(TABLE_REPO)->where('id')->eq($repoList[1][0])->fetch();

r($repo1) && p('product', '|') && e('1,2');  //更新id为1的repo的product字段为1,2，测试是否更新成功
r($repo2) && p('product', '|') && e('3,4');  //更新id为2的repo的product字段为3,4，测试是否更新成功
