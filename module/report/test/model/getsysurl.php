#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/report.class.php';

zdTable('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getSysURL();
cid=1
pid=1

*/
$report = new reportTest();

$demain = array('the demain', '');
$argv = array('https://localhost', 'https://localhost:8080');

r($report->getSysURLTest($demain[0], $argv[0])) && p() && e('the demain'); // 测试获取 config->mail->demain the demain argv1 https://localhost 的 system url
r($report->getSysURLTest($demain[0], $argv[1])) && p() && e('the demain'); // 测试获取 config->mail->demain the demain argv1 https://localhost:8080 的 system url

r($report->getSysURLTest($demain[1], $argv[0])) && p() && e('https://localhost');      // 测试获取 config->mail->demain 空 https://localhost 的 system url
r($report->getSysURLTest($demain[1], $argv[1])) && p() && e('https://localhost:8080'); // 测试获取 config->mail->demain 空 https://localhost:8080 的 system url
