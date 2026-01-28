#!/usr/bin/env php
<?php

/**

title=测试 docModel->getDocTree();
cid=16087

- 测试libiID=0时，获取文档结构 @0
- 测试libiID=11时，获取文档结构 @/:我的文档1,我的文档2;模块2:我的草稿文档7,我的草稿文档8;模块1:我的草稿文档6;

- 测试libiID=13时，获取文档结构 @/:我的文档1,我的文档2;模块3:;

- 测试libiID=14时，获取文档结构 @/:我的文档1,我的文档2;

- 测试libiID为不存在的ID时，获取文档结构 @/:我的文档1,我的文档2;

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->loadYaml('module')->gen(3);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);

$libIds = array(0, 11, 13, 14, 31);

$docTester = new docModelTest();
r($docTester->getDocTreeTest($libIds[0])) && p() && e('0');                                                                            // 测试libiID=0时，获取文档结构
r($docTester->getDocTreeTest($libIds[1])) && p() && e('/:我的文档1,我的文档2;模块2:我的草稿文档7,我的草稿文档8;模块1:我的草稿文档6;'); // 测试libiID=11时，获取文档结构
r($docTester->getDocTreeTest($libIds[2])) && p() && e('/:我的文档1,我的文档2;模块3:;');                                                // 测试libiID=13时，获取文档结构
r($docTester->getDocTreeTest($libIds[3])) && p() && e('/:我的文档1,我的文档2;');                                                       // 测试libiID=14时，获取文档结构
r($docTester->getDocTreeTest($libIds[4])) && p() && e('/:我的文档1,我的文档2;');                                                       // 测试libiID为不存在的ID时，获取文档结构
