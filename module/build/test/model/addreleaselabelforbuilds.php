#!/usr/bin/env php
<?php

/**

title=测试 buildModel::addReleaseLabelForBuilds();
timeout=0
cid=0

- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是1, $normalBuilds  @3
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是1, $buildsWithRelease 第0条的value属性 @1
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是2, $buildsProduct2  @2
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是1, array  @0
- 执行buildTest模块的addReleaseLabelForBuildsTest方法，参数是999, $unknownProductBuilds  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/build.unittest.class.php';

zenData('build')->gen(5);
zenData('release')->gen(5);

su('admin');

$buildTest = new buildTest();

// 步骤1：正常产品ID和版本数组，验证返回数量
$normalBuilds = array(1 => '版本1', 2 => '版本2', 3 => '版本3');
r($buildTest->addReleaseLabelForBuildsTest(1, $normalBuilds)) && p() && e('3');

// 步骤2：有发布关联的版本，验证第0个元素的value值
$buildsWithRelease = array(1 => '版本1', 2 => '版本2');
r($buildTest->addReleaseLabelForBuildsTest(1, $buildsWithRelease)) && p('0:value') && e('1');

// 步骤3：不同产品ID处理，验证返回数量
$buildsProduct2 = array(4 => '版本4', 5 => '版本5');
r($buildTest->addReleaseLabelForBuildsTest(2, $buildsProduct2)) && p() && e('2');

// 步骤4：空版本数组输入，验证返回数量
r($buildTest->addReleaseLabelForBuildsTest(1, array())) && p() && e('0');

// 步骤5：不存在的产品ID，验证返回数量
$unknownProductBuilds = array(99 => 'Unknown_Build');
r($buildTest->addReleaseLabelForBuildsTest(999, $unknownProductBuilds)) && p() && e('1');