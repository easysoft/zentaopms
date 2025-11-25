#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

zenData('user')->gen(1);

su('admin');

/**

title=测试 reportModel->getSysURL();
cid=18168

- 测试获取 config->mail->demain the demain argv1 https:localhost 的 system url @the demain
- 测试获取 config->mail->demain the demain argv1 https:localhost:8080 的 system url @the demain
- 测试获取 config->mail->demain 空 https:localhost 的 system url @https://localhost
- 测试获取 config->mail->demain 空 https:localhost:8080 的 system url @https://localhost:8080
- 测试获取 config->mail->demain demain name argv1 https:localhost 的 system url @demain name
- 测试获取 config->mail->demain demain name argv1 https:localhost:8080 的 system url @demain name

*/
$report = new reportTest();

$demain = array('the demain', '', 'demain name');
$argv = array('https://localhost', 'https://localhost:8080');

r($report->getSysURLTest($demain[0], $argv[0])) && p() && e('the demain'); // 测试获取 config->mail->demain the demain argv1 https://localhost 的 system url
r($report->getSysURLTest($demain[0], $argv[1])) && p() && e('the demain'); // 测试获取 config->mail->demain the demain argv1 https://localhost:8080 的 system url

r($report->getSysURLTest($demain[1], $argv[0])) && p() && e('https://localhost');      // 测试获取 config->mail->demain 空 https://localhost 的 system url
r($report->getSysURLTest($demain[1], $argv[1])) && p() && e('https://localhost:8080'); // 测试获取 config->mail->demain 空 https://localhost:8080 的 system url

r($report->getSysURLTest($demain[2], $argv[0])) && p() && e('demain name'); // 测试获取 config->mail->demain demain name argv1 https://localhost 的 system url
r($report->getSysURLTest($demain[2], $argv[1])) && p() && e('demain name'); // 测试获取 config->mail->demain demain name argv1 https://localhost:8080 的 system url
