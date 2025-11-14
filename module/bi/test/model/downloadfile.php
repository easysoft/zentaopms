#!/usr/bin/env php
<?php

/**

title=测试 biModel::downloadFile();
timeout=0
cid=15156

- 执行biTest模块的downloadFileTest方法，参数是'', '/tmp/', 'test.txt'  @0
- 执行biTest模块的downloadFileTest方法，参数是'invalid-url', '/tmp/', 'test.txt'  @0
- 执行biTest模块的downloadFileTest方法，参数是'https://example.com/file.txt', '/nonexistent/', 'test.txt'  @0
- 执行biTest模块的downloadFileTest方法，参数是'https://httpbin.org/status/404', '/tmp/', 'test.txt'  @0
- 执行biTest模块的downloadFileTest方法，参数是'https://example.com/json-error', '/tmp/', 'test.txt'  @0
- 执行biTest模块的downloadFileTest方法，参数是'https://valid-test.com/file.txt', '/tmp/', 'test.txt'  @1
- 执行biTest模块的downloadFileTest方法，参数是'https://valid-test.com/file.zip', '/tmp/', 'test.bin'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$biTest = new biTest();

r($biTest->downloadFileTest('', '/tmp/', 'test.txt')) && p() && e('0');
r($biTest->downloadFileTest('invalid-url', '/tmp/', 'test.txt')) && p() && e('0');
r($biTest->downloadFileTest('https://example.com/file.txt', '/nonexistent/', 'test.txt')) && p() && e('0');
r($biTest->downloadFileTest('https://httpbin.org/status/404', '/tmp/', 'test.txt')) && p() && e('0');
r($biTest->downloadFileTest('https://example.com/json-error', '/tmp/', 'test.txt')) && p() && e('0');
r($biTest->downloadFileTest('https://valid-test.com/file.txt', '/tmp/', 'test.txt')) && p() && e('1');
r($biTest->downloadFileTest('https://valid-test.com/file.zip', '/tmp/', 'test.bin')) && p() && e('1');