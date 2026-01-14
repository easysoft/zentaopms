#!/usr/bin/env php
<?php

/**

title=测试 mailModel::convertCharset();
timeout=0
cid=17005

- 执行mailTest模块的convertCharsetTest方法，参数是''  @0
- 执行mailTest模块的convertCharsetTest方法，参数是'测试文本'  @测试文本
- 执行mailTest模块的convertCharsetTest方法，参数是$gbkString  @中文测试
- 执行mailTest模块的convertCharsetTest方法，参数是'无需转换'  @无需转换
- 执行mailTest模块的convertCharsetTest方法，参数是'相同编码'  @相同编码
- 执行mailTest模块的convertCharsetTest方法，参数是'123456'  @123456
- 执行mailTest模块的convertCharsetTest方法，参数是'Hello World'  @Hello World

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$mailTest = new mailModelTest();

r($mailTest->convertCharsetTest('')) && p() && e('0');

$mailTest->objectModel->config->charset = 'utf-8';
$mailTest->objectModel->config->mail->smtp->charset = 'utf-8';
r($mailTest->convertCharsetTest('测试文本')) && p() && e('测试文本');

$mailTest->objectModel->config->charset = 'gbk';
$mailTest->objectModel->config->mail->smtp->charset = 'utf-8';
$gbkString = iconv('utf-8', 'gbk', '中文测试');
r($mailTest->convertCharsetTest($gbkString)) && p() && e('中文测试');

$mailTest->objectModel->config->mail->smtp->charset = '';
r($mailTest->convertCharsetTest('无需转换')) && p() && e('无需转换');

$mailTest->objectModel->config->charset = 'utf-8';
$mailTest->objectModel->config->mail->smtp->charset = 'utf-8';
r($mailTest->convertCharsetTest('相同编码')) && p() && e('相同编码');

r($mailTest->convertCharsetTest('123456')) && p() && e('123456');

r($mailTest->convertCharsetTest('Hello World')) && p() && e('Hello World');