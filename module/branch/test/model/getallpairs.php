#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/branch.unittest.class.php';

zenData('product')->loadYaml('product')->gen(10);
zenData('branch')->loadYaml('branch')->gen(10);
su('admin');

/**

title=测试 branchModel->getAllPairs();
timeout=0
cid=0

- 测试获取全部分支名个数 @11
- 测试获取没有主干的分支名个数 @10
- 测试获取没有产品名的分支名个数 @11
- 测试获取没有主干也没有产品名的分支名个数 @10
- 测试获取不包含主干的分支名
 -  @~~
 - 属性1 @产品6/分支1
- 测试获取不包含产品名称的分支名
 -  @主干
 - 属性1 @分支1
- 测试获取既不包含产品名称也不包含主干的分支名
 -  @~~
 - 属性1 @分支1

*/

$params = array('noempty', 'noproductname', 'noempty,noproductname');
$branch = new branchTest();

r(count($branch->getAllPairsTest()))           && p()      && e('11');             // 测试获取全部分支名个数
r(count($branch->getAllPairsTest($params[0]))) && p()      && e('10');             // 测试获取没有主干的分支名个数
r(count($branch->getAllPairsTest($params[1]))) && p()      && e('11');             // 测试获取没有产品名的分支名个数
r(count($branch->getAllPairsTest($params[2]))) && p()      && e('10');             // 测试获取没有主干也没有产品名的分支名个数
r($branch->getAllPairsTest($params[0]))        && p('0,1') && e('~~,产品6/分支1'); // 测试获取不包含主干的分支名
r($branch->getAllPairsTest($params[1]))        && p('0,1') && e('主干,分支1');     // 测试获取不包含产品名称的分支名
r($branch->getAllPairsTest($params[2]))        && p('0,1') && e('~~,分支1');       // 测试获取既不包含产品名称也不包含主干的分支名
