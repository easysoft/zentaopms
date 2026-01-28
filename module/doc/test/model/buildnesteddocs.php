#!/usr/bin/env php
<?php

/**

title=测试 docModel->buildNestedDocs();
cid=16047

- 测试获取我的文档1第1条的text属性 @我的文档1
- 测试获取我的草稿文档6第6条的text属性 @我的草稿文档6
- 测试获取自定义文档11第11条的text属性 @自定义文档11
- 测试获取自定义草稿文档16第16条的text属性 @自定义草稿文档16
- 测试获取项目文档21第21条的text属性 @项目文档21
- 测试获取项目草稿文档26第26条的text属性 @项目草稿文档26

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('module')->loadYaml('module')->gen(3);
zenData('doclib')->loadYaml('doclib')->gen(30);
zenData('doc')->loadYaml('doc')->gen(50);
zenData('user')->gen(5);

$docTester = new docModelTest();
r($docTester->buildNestedDocsTest()) && p('1:text')  && e('我的文档1');        // 测试获取我的文档1
r($docTester->buildNestedDocsTest()) && p('6:text')  && e('我的草稿文档6');    // 测试获取我的草稿文档6
r($docTester->buildNestedDocsTest()) && p('11:text') && e('自定义文档11');     // 测试获取自定义文档11
r($docTester->buildNestedDocsTest()) && p('16:text') && e('自定义草稿文档16'); // 测试获取自定义草稿文档16
r($docTester->buildNestedDocsTest()) && p('21:text') && e('项目文档21');       // 测试获取项目文档21
r($docTester->buildNestedDocsTest()) && p('26:text') && e('项目草稿文档26');   // 测试获取项目草稿文档26
