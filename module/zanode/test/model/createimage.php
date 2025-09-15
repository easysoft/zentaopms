#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 zanodeModel::createImage();
timeout=0
cid=0

- 执行zanode模块的createImageTest方法，参数是1, $imageData  @0
- 执行zanode模块的createImageTest方法，参数是999, $imageData  @0
- 执行zanode模块的createImageTest方法，参数是1, $emptyImageData  @0
- 执行zanode模块的createImageTest方法，参数是0, $imageData  @0
- 执行zanode模块的createImageTest方法，参数是-1, $imageData  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zanode.unittest.class.php';

zenData('user')->gen(5);
zenData('host')->loadYaml('host')->gen(10);

su('admin');

$zanode = new zanodeTest();

$imageData = new stdClass();
$imageData->name = 'test-image';

$emptyImageData = new stdClass();
$emptyImageData->name = '';

r($zanode->createImageTest(1, $imageData)) && p() && e('0');
r($zanode->createImageTest(999, $imageData)) && p() && e('0');
r($zanode->createImageTest(1, $emptyImageData)) && p() && e('0');
r($zanode->createImageTest(0, $imageData)) && p() && e('0');
r($zanode->createImageTest(-1, $imageData)) && p() && e('0');