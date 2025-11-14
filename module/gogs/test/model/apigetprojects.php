#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetProjects();
timeout=0
cid=16686

- 执行gogsTest模块的apiGetProjectsTest方法，参数是1  @0
- 执行gogsTest模块的apiGetProjectsTest方法，参数是999  @0
- 执行gogsTest模块的apiGetProjectsTest方法，参数是5
 - 第0条的id属性 @1
 - 第0条的full_name属性 @easycorp/unittest
- 执行gogsTest模块的apiGetProjectsTest方法  @0
- 执行gogsTest模块的apiGetProjectsTest方法，参数是-1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gogs.unittest.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->loadYaml('oauth')->gen(5);

su('admin');

$gogsTest = new gogsTest();

r($gogsTest->apiGetProjectsTest(1)) && p() && e('0');
r($gogsTest->apiGetProjectsTest(999)) && p() && e('0');
r($gogsTest->apiGetProjectsTest(5)) && p('0:id,full_name') && e('1,easycorp/unittest');
r($gogsTest->apiGetProjectsTest(0)) && p() && e('0');
r($gogsTest->apiGetProjectsTest(-1)) && p() && e('0');