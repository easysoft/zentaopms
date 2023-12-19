#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->hasConsistencyError();
cid=1

- 当前日志文件不存在，返回false @0
- 当前日志文件存在，但倒数两行没有错误信息，返回false @0
- 当前日志文件存在，但包含错误，返回true @1

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

global $app;
$path     = $app->getTmpRoot() . 'log/';
$filename = 'consistency.%s.log.php';

function genFile(string $filename, array $contents)
{
    $contents = implode("\n", $contents);
    file_put_contents($filename, $contents);
}

$filePath = sprintf($path . $filename, date('Ymd'));

$upgrade = new upgradeTest();

if(file_exists($filePath)) unlink($filePath);
r($upgrade->hasConsistencyError())  && p('') && e(0);   //当前日志文件不存在，返回false

$contents = array(
    'success',
    'your zentao has been upgraded successfully.',
);
genFile($filePath, $contents);
r($upgrade->hasConsistencyError())  && p('') && e(0);   //当前日志文件存在，但倒数两行没有错误信息，返回false

if(file_exists($filePath)) unlink($filePath);

$filePath = sprintf($path . $filename, date('Ymd'));
$contents = array(
    'HasError',
    'you have some consistency errors in your zentao.',
);
genFile($filePath, $contents);
r($upgrade->hasConsistencyError())  && p('') && e(1);   //当前日志文件存在，但包含错误，返回true
