#!/usr/bin/env php
<?php

/**

title=测试 entryModel::saveLog();
cid=16252

- 测试步骤1：正常保存日志记录 >> 期望成功保存并返回正确数据
- 测试步骤2：保存包含特殊字符的URL >> 期望正确处理特殊字符
- 测试步骤3：保存长URL地址 >> 期望成功保存长URL
- 测试步骤4：保存空URL字符串 >> 期望成功保存空URL
- 测试步骤5：使用不存在的entryID >> 期望依然成功保存日志

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/entry.unittest.class.php';

zenData('user')->gen(5);
zenData('entry')->gen(10);

su('admin');

$entry = new entryTest();

r($entry->saveLogTest(1, 'http://example.com/api/test')) && p('objectID,objectType,url') && e('1,entry,http://example.com/api/test');
r($entry->saveLogTest(2, 'http://test.com/api?param=测试&type=中文')) && p('objectID,objectType,url') && e('2,entry,http://test.com/api?param=测试&type=中文');
$longUrl = 'http://example.com/very/long/path/with/many/segments/and/parameters?param1=value1&param2=value2&param3=value3&param4=value4&param5=value5';
r($entry->saveLogTest(3, $longUrl)) && p('objectID,objectType,url') && e('3,entry,' . $longUrl);
r($entry->saveLogTest(4, '')) && p('objectID,objectType,url') && e('4,entry,');
r($entry->saveLogTest(999, 'http://test.com/nonexistent')) && p('objectID,objectType,url') && e('999,entry,http://test.com/nonexistent');