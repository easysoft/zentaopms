#!/usr/bin/env php
<?php

/**

title=测试 entryModel::saveLog();
timeout=0
cid=0

- 执行entry模块的saveLogTest方法，参数是1, 'http://example.com/api/test'
 - 属性objectID @1
 - 属性objectType @entry
 - 属性url @http://example.com/api/test
- 执行entry模块的saveLogTest方法，参数是2, 'http://test.com/api?param=测试&type=中文'
 - 属性objectID @2
 - 属性objectType @entry
 - 属性url @http://test.com/api?param=测试&type=中文
- 执行entry模块的saveLogTest方法，参数是3, $longUrl
 - 属性objectID @3
 - 属性objectType @entry
 - 属性url @http://example.com/very/long/path/with/many/segments/and/parameters?param1=value1&param2=value2&param3=value3&param4=value4&param5=value5
- 执行entry模块的saveLogTest方法，参数是4, ''
 - 属性objectID @4
 - 属性objectType @entry
 - 属性url @~~
- 执行entry模块的saveLogTest方法，参数是999, 'http://test.com/nonexistent'
 - 属性objectID @999
 - 属性objectType @entry
 - 属性url @http://test.com/nonexistent

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('entry')->gen(10);

su('admin');

$entry = new entryModelTest();

r($entry->saveLogTest(1, 'http://example.com/api/test')) && p('objectID,objectType,url') && e('1,entry,http://example.com/api/test');
r($entry->saveLogTest(2, 'http://test.com/api?param=测试&type=中文')) && p('objectID,objectType,url') && e('2,entry,http://test.com/api?param=测试&type=中文');
$longUrl = 'http://example.com/very/long/path/with/many/segments/and/parameters?param1=value1&param2=value2&param3=value3&param4=value4&param5=value5';
r($entry->saveLogTest(3, $longUrl)) && p('objectID,objectType,url') && e('3,entry,http://example.com/very/long/path/with/many/segments/and/parameters?param1=value1&param2=value2&param3=value3&param4=value4&param5=value5');
r($entry->saveLogTest(4, '')) && p('objectID,objectType,url') && e('4,entry,~~');
r($entry->saveLogTest(999, 'http://test.com/nonexistent')) && p('objectID,objectType,url') && e('999,entry,http://test.com/nonexistent');
