#!/usr/bin/env php
<?php

/**

title=测试 searchTao::decode();
timeout=0
cid=0

- 步骤1：单个 unicode 编码会被转换为中文 @你
- 步骤2：多个 unicode 编码会按顺序拼接 @你好
- 步骤3：包含分隔符时会忽略竖线标记 @你好
- 步骤4：未知数字编码会保留原值 @99999
- 步骤5：普通文本不会被错误替换 @hello

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$tester->dao->delete()->from(TABLE_SEARCHDICT)->exec();
$tester->dao->insert(TABLE_SEARCHDICT)->data(array('key' => '20320', 'value' => '你'))->exec();
$tester->dao->insert(TABLE_SEARCHDICT)->data(array('key' => '22909', 'value' => '好'))->exec();

su('admin');

$search = new searchTaoTest();

r($search->decodeTest('20320'))                 && p() && e('你');     // 步骤1：单个 unicode 编码会被转换为中文
r($search->decodeTest('20320 22909'))           && p() && e('你好');   // 步骤2：多个 unicode 编码会按顺序拼接
r($search->decodeTest('| 20320 22909 |'))       && p() && e('你好');   // 步骤3：包含分隔符时会忽略竖线标记
r(trim($search->decodeTest('99999')))           && p() && e('99999');  // 步骤4：未知数字编码会保留原值
r(trim($search->decodeTest('hello')))           && p() && e('hello');  // 步骤5：普通文本不会被错误替换
