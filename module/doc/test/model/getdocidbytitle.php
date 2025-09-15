#!/usr/bin/env php
<?php

/**

title=测试 docModel::getDocIdByTitle();
timeout=0
cid=0

- 步骤1：正常情况，有效参数 @1
- 步骤2：空标题 @0
- 步骤3：无效originPageID @0
- 步骤4：不存在的标题 @0
- 步骤5：无关联记录的查找 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备
$doc = zenData('doc');
$doc->id->range('1-10');
$doc->lib->range('1{3}, 2{3}, 3{2}, 4{2}');
$doc->title->range('用户手册{2}, 开发文档{2}, 测试文档{2}, API文档{2}, 产品需求{1}, 技术文档{1}');
$doc->status->range('normal{8}, draft{2}');
$doc->deleted->range('0{10}');
$doc->gen(10);

// 创建Confluence临时关联表的测试数据
global $tester;
$tester->dbh->exec("CREATE TEMPORARY TABLE IF NOT EXISTS `confluencetmprelation` (
    `AID` int(11) NOT NULL,
    `BID` int(11) NOT NULL,
    `AType` varchar(30) NOT NULL DEFAULT '',
    `BType` varchar(30) NOT NULL DEFAULT '',
    PRIMARY KEY (`AID`, `BID`, `AType`, `BType`)
)");

// 插入关联数据
$tester->dbh->exec("INSERT INTO `confluencetmprelation` (`AID`, `BID`, `AType`, `BType`) VALUES 
    (100, 1, 'confluence', 'zdoc'),
    (200, 2, 'confluence', 'zdoc'),
    (300, 3, 'confluence', 'zdoc'),
    (400, 4, 'confluence', 'zdoc'),
    (1, 1, 'zdoc', 'zdoc'),
    (2, 2, 'zdoc', 'zdoc'),
    (3, 3, 'zdoc', 'zdoc')
");

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$docTest = new docTest();

// 5. 测试步骤（必须包含至少5个测试步骤）
r($docTest->getDocIdByTitleTest(100, '用户手册')) && p() && e('1'); // 步骤1：正常情况，有效参数
r($docTest->getDocIdByTitleTest(200, '')) && p() && e('0'); // 步骤2：空标题
r($docTest->getDocIdByTitleTest(999, '用户手册')) && p() && e('0'); // 步骤3：无效originPageID
r($docTest->getDocIdByTitleTest(100, '不存在的文档')) && p() && e('0'); // 步骤4：不存在的标题
r($docTest->getDocIdByTitleTest(300, '用户手册')) && p() && e('0'); // 步骤5：无关联记录的查找