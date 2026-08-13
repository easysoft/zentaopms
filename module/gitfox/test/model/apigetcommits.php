#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::apigetcommits();
timeout=0
cid=0

- 执行gitfoxTest模块的apiGetCommitsErrorTest方法，参数是1, array  @0
- 执行gitfoxTest模块的apiGetCommitsTest方法，参数是1, array  @0
- 执行gitfoxTest模块的apiGetCommitsTypeTest方法，参数是1, array  @bool
- 执行gitfoxTest模块的apiGetCommitsCountTest方法，参数是1, array  @0
- 执行gitfoxTest模块的apiGetCommitsTest方法，参数是1, array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$gitfoxTest = new gitfoxModelTest();

r($gitfoxTest->apiGetCommitsErrorTest(1, array())) && p() && e('0');
r($gitfoxTest->apiGetCommitsTest(1, array())) && p() && e('0');
r($gitfoxTest->apiGetCommitsTypeTest(1, array())) && p() && e('bool');
r($gitfoxTest->apiGetCommitsCountTest(1, array())) && p() && e('0');
r($gitfoxTest->apiGetCommitsTest(1, array('page' => 1))) && p() && e('0');