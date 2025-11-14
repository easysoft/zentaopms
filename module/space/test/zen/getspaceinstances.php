#!/usr/bin/env php
<?php

/**

title=测试 spaceZen->getSpaceInstances();
cid=18400

- 测试 browseType='' 时的返回结果 @1
- 测试 browseType='all' 时的返回结果 @1
- 测试 browseType='running' 时的返回结果 @1
- 测试 browseType='aksjdfj' 时的返回结果 @1
- 测试 browseType='abc' 时的返回结果 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/spacezen.unittest.class.php';

zenData('user')->gen(5);
zenData('space')->loadYaml('space')->gen(5);
zenData('instance')->loadYaml('instance')->gen(10);
zenData('pipeline')->loadYaml('pipeline')->gen(5);

$spaceZenTester = new spaceZenTest();
r($spaceZenTester->getSpaceInstancesZenTest()) && p() && e('1');
r($spaceZenTester->getSpaceInstancesZenTest('all')) && p() && e('1');
r($spaceZenTester->getSpaceInstancesZenTest('running')) && p() && e('1');
r($spaceZenTester->getSpaceInstancesZenTest('aksjdfj')) && p() && e('1');
r($spaceZenTester->getSpaceInstancesZenTest('abc')) && p() && e('1');
