#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::deleteRelated();
timeout=0
cid=17986

- 执行releaseTest模块的deleteRelatedTest方法，参数是1, 'story', 1  @1
- 执行releaseTest模块的deleteRelatedTest方法，参数是2, 'bug', '1, 2, 3'  @0
- 执行releaseTest模块的deleteRelatedTest方法，参数是3, 'leftBug', [4, 5, 6]  @0
- 执行releaseTest模块的deleteRelatedTest方法，参数是4, 'story', ''  @0
- 执行releaseTest模块的deleteRelatedTest方法，参数是5, 'bug', []  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('release')->gen(10);
zenData('releaserelated')->loadYaml('releaserelated', false, 2)->gen(20);

su('admin');

$releaseTest = new releaseModelTest();

r($releaseTest->deleteRelatedTest(1, 'story', 1)) && p() && e('1');
r($releaseTest->deleteRelatedTest(2, 'bug', '1,2,3')) && p() && e('0');
r($releaseTest->deleteRelatedTest(3, 'leftBug', [4, 5, 6])) && p() && e('0');
r($releaseTest->deleteRelatedTest(4, 'story', '')) && p() && e('0');
r($releaseTest->deleteRelatedTest(5, 'bug', [])) && p() && e('0');