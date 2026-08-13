#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apiGetSpaces();
timeout=0
cid=0

- 步骤 1：apiGetSpaces 不产生 dao 错误 @0
- 步骤 2：apiGetSpaces 默认返回值类型为 object @object
- 步骤 3：apiGetSpaces 默认页码为 1 @1
- 步骤 4：自定义分页大小时 pageSize 为 5 @5
- 步骤 5：自定义分页时返回值类型仍为 object @object

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();
$customPager = (object)array('pageID' => 1, 'recPerPage' => 5);

r($gitfoxTest->apiGetSpacesErrorTest(array())) && p() && e('0');
r($gitfoxTest->apiGetSpacesTypeTest(array())) && p() && e('object');
r($gitfoxTest->apiGetSpacesTest(array())) && p('pager:page') && e('1');
r($gitfoxTest->apiGetSpacesTest(array(), $customPager)) && p('pager:pageSize') && e('5');
r($gitfoxTest->apiGetSpacesTypeTest(array(), $customPager)) && p() && e('object');
