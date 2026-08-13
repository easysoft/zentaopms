#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getPage();
timeout=0
cid=0

- 步骤 1：getPage(null) 返回空数组 @0
- 步骤 2：自定义页码和分页大小正确 @3,50
- 步骤 3：缺少 recPerPage 时使用默认分页大小 @5,20
- 步骤 4：空对象时使用默认分页信息 @1,20
- 步骤 5：结果里包含 page 字段 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->getPageCountTest(null)) && p() && e('0');
r($gitfoxTest->getPageTest((object)array('pageID' => 3, 'recPerPage' => 50))) && p('page,pageSize') && e('3,50');
r($gitfoxTest->getPageTest((object)array('pageID' => 5))) && p('page,pageSize') && e('5,20');
r($gitfoxTest->getPageTest(new stdclass())) && p('page,pageSize') && e('1,20');
r($gitfoxTest->getPageHasPageTest((object)array('pageID' => 3, 'recPerPage' => 50))) && p() && e('1');
