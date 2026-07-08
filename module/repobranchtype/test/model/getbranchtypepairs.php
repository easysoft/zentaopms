#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

/**

title=测试 repoModel->getList();
timeout=0
cid=0

- 获取代码库1的分支类型键值对属性1 @branch_type1
- 获取代码库1的分支类型数量 @1
- 获取代码库2的分支类型键值对属性2 @branch_type2
- 获取代码库3的分支类型键值对属性3 @branch_type3
- 获取代码库4的分支类型数量 @1
*/

zenData('repo')->gen(10);
zenData('ops_branch_type')->gen(10);

$repo = new repobranchtypeTest();
r($repo->getBranchTypePairsTest(1))        && p('1') && e('branch_type1'); // 获取代码库1的分支类型键值对
r(count($repo->getBranchTypePairsTest(1))) && p()    && e('1');            // 获取代码库1的分支类型数量
r($repo->getBranchTypePairsTest(2))        && p('2') && e('branch_type2'); // 获取代码库2的分支类型键值对
r($repo->getBranchTypePairsTest(3))        && p('3') && e('branch_type3'); // 获取代码库3的分支类型键值对
r(count($repo->getBranchTypePairsTest(4))) && p()    && e('1');            // 获取代码库4的分支类型数量
