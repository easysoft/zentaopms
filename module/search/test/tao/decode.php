#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

/**

title=测试 searchTao::decode();
timeout=0
cid=18325

- 步骤1：测试空字符串 @~~
- 步骤2：测试不在字典中的字符串 @hello
- 步骤3：测试包含空格的字符串替换 @测试字
- 步骤4：测试字典中存在的键 @测
- 步骤5：测试不存在的键 @999

*/

// 准备测试数据 - 直接插入带空格的键来匹配decode方法的查询逻辑
global $tester;
$tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();
$tester->dao->insert(TABLE_SEARCHDICT)->data(array('key' => 1, 'value' => '测'))->exec();
$tester->dao->insert(TABLE_SEARCHDICT)->data(array('key' => 2, 'value' => '试'))->exec();
$tester->dao->insert(TABLE_SEARCHDICT)->data(array('key' => 3, 'value' => '字'))->exec();
$tester->dao->insert(TABLE_SEARCHDICT)->data(array('key' => 124, 'value' => '|'))->exec(); // 124是'|'的ASCII码

$search = new searchTest();

r($search->decodeTest('')) && p() && e('~~'); // 步骤1：测试空字符串
r($search->decodeTest('hello')) && p() && e('hello'); // 步骤2：测试不在字典中的字符串
r($search->decodeTest('1 2 3')) && p() && e('测试字'); // 步骤3：测试包含空格的字符串替换
r($search->decodeTest('1')) && p() && e('测'); // 步骤4：测试字典中存在的键
r($search->decodeTest('999')) && p() && e('999'); // 步骤5：测试不存在的键