#!/usr/bin/env php
<?php

/**

title=测试 myTao::fetchRequirementsBySearch();
timeout=0
cid=17308

- 执行myTest模块的fetchRequirementsBySearchTest方法，参数是"t1.deleted = '0'", 'contribute', 'id_desc', null, array  @3
- 执行myTest模块的fetchRequirementsBySearchTest方法，参数是"t1.deleted = '0'", 'assigned', 'id_desc', null, array  @3
- 执行myTest模块的fetchRequirementsBySearchTest方法，参数是"1 = 1", 'contribute', 'id_desc', null, array  @3
- 执行myTest模块的fetchRequirementsBySearchTest方法，参数是"t1.deleted = '0'", 'contribute', 'id_desc', null, array  @4
- 执行myTest模块的fetchRequirementsBySearchTest方法，参数是"t1.deleted = '0'", 'contribute', 'priOrder_desc', null, array  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('story')->loadYaml('story_fetchrequirementsbysearch', false, 2)->gen(10);
zenData('product')->loadYaml('product_fetchrequirementsbysearch', false, 2)->gen(3);
zenData('storyreview')->loadYaml('storyreview_fetchrequirementsbysearch', false, 2)->gen(10);

su('admin');

$myTest = new myTest();

r($myTest->fetchRequirementsBySearchTest("t1.deleted = '0'", 'contribute', 'id_desc', null, array())) && p() && e(3);
r($myTest->fetchRequirementsBySearchTest("t1.deleted = '0'", 'assigned', 'id_desc', null, array())) && p() && e(3);
r($myTest->fetchRequirementsBySearchTest("1 = 1", 'contribute', 'id_desc', null, array())) && p() && e(3);
r($myTest->fetchRequirementsBySearchTest("t1.deleted = '0'", 'contribute', 'id_desc', null, array(1, 2, 3))) && p() && e(4);
r($myTest->fetchRequirementsBySearchTest("t1.deleted = '0'", 'contribute', 'priOrder_desc', null, array())) && p() && e(3);