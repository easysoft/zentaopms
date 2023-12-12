#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/job.class.php';
su('admin');

/**

title=jobModel->getList();
timeout=0
cid=1

- 测试获取列表的个数 @5
- 测试获取列表某个job的名称信息第1条的name属性 @这是一个Job1
- 测试获取版本库为2列表的个数 @1
- 测试获取版本库为2的列表某个job的名称信息第2条的name属性 @这是一个Job2
- 测试获取jenkins类型列表的个数 @3
- 测试获取jenkins类型列表某个job的名称信息第5条的name属性 @这是一个Job5

*/

zdTable('job')->gen(5);

$job  = new jobTest();
$list        = $job->getListTest();
$repoList    = $job->getListTest(2);
$jenkinsList = $job->getListTest(0, 'id_desc', null, 'jenkins');

r(count($list))        && p()         && e('5');             //测试获取列表的个数
r($list)               && p('1:name') && e('这是一个Job1');  //测试获取列表某个job的名称信息
r(count($repoList))    && p()         && e('1');             //测试获取版本库为2列表的个数
r($repoList)           && p('2:name') && e('这是一个Job2');  //测试获取版本库为2的列表某个job的名称信息
r(count($jenkinsList)) && p()         && e('3');             //测试获取jenkins类型列表的个数
r($jenkinsList)        && p('5:name') && e('这是一个Job5');  //测试获取jenkins类型列表某个job的名称信息