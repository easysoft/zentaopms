#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::getBugList();
timeout=0
cid=17987

- 步骤1：测试传入空bugIdList @0
- 步骤2：测试传入有效bugIdList获取bug列表第4条的title属性 @Bug5
- 步骤3：测试带orderBy参数的查询第0条的id属性 @3
- 步骤4：测试type参数为left的查询第2条的title属性 @Bug3
- 步骤5：测试不存在的bugIdList查询 @0
- 步骤6：测试单个bugId查询第0条的title属性 @Bug1
- 步骤7：测试大量bugId查询返回的记录数量 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$bug = zenData('bug');
$bug->id->range('1-20');
$bug->product->range('1-5');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10,Bug11,Bug12,Bug13,Bug14,Bug15,Bug16,Bug17,Bug18,Bug19,Bug20');
$bug->severity->range('1-4');
$bug->status->range('active{5},resolved{5},closed{5},active{5}');
$bug->openedBy->range('admin,user1,user2');
$bug->deleted->range('0');
$bug->gen(20);

zenData('user')->gen(5);
su('admin');

$releaseTest = new releaseTest();

r($releaseTest->getBugListTest('')) && p() && e('0'); // 步骤1：测试传入空bugIdList
r($releaseTest->getBugListTest('1,2,3,4,5')) && p('4:title') && e('Bug5'); // 步骤2：测试传入有效bugIdList获取bug列表
r($releaseTest->getBugListTest('1,2,3', 'id_desc')) && p('0:id') && e('3'); // 步骤3：测试带orderBy参数的查询
r($releaseTest->getBugListTest('1,2,3', '', null, 'left')) && p('2:title') && e('Bug3'); // 步骤4：测试type参数为left的查询
r($releaseTest->getBugListTest('100,200,300')) && p() && e('0'); // 步骤5：测试不存在的bugIdList查询
r($releaseTest->getBugListTest('1')) && p('0:title') && e('Bug1'); // 步骤6：测试单个bugId查询
r(count($releaseTest->getBugListTest('1,2,3,4,5,6,7,8,9,10'))) && p() && e('10'); // 步骤7：测试大量bugId查询返回的记录数量