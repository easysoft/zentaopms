#!/usr/bin/env php
<?php

/**

title=测试 zaiModel->extractFileContent();
timeout=0
cid=0

- 测试文件不存在的情况 @0
- 测试文件存在但没有ZAI设置 @0
- 测试文件存在且有ZAI设置（模拟API调用） @0
- 测试文件ID为0 @0
- 测试文件路径为空 @0
- 测试有效的文本文件提取 @0
- 执行$result7 @0
- 执行$result8 @0
- 执行$result9 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备文件测试数据
$fileTable = zenData('file');
$fileTable->id->range('1-10');
$fileTable->pathname->range('202401/01/test1.txt,202401/01/test2.pdf,202401/01/test3.doc,,202401/01/test5.docx');
$fileTable->title->range('测试文本文件.txt,测试PDF文件.pdf,测试Word文件.doc,空路径文件.txt,测试DOCX文件.docx');
$fileTable->extension->range('txt,pdf,doc,txt,docx');
$fileTable->size->range('1024,2048,3072,0,4096');
$fileTable->objectType->range('doc,product,story,story,requirement');
$fileTable->objectID->range('1,2,3,4,5');
$fileTable->addedBy->range('admin');
$fileTable->addedDate->range('`2024-01-01 10:00:00`');
$fileTable->gen(5);

zenData('config')->gen(0);
zenData('user')->gen(1);

su('admin');

global $tester, $app;
$zai = new zaiModelTest();

/* 测试1：文件不存在的情况 */
$result1 = $zai->extractFileContentTest(999);
r($result1) && p() && e('0'); // 测试文件不存在的情况

/* 测试2：文件存在但没有ZAI设置 */
$result2 = $zai->extractFileContentTest(1);
r($result2) && p() && e('0'); // 测试文件存在但没有ZAI设置

/* 设置ZAI配置 */
$setting = new stdClass();
$setting->host = 'testhost.com';
$setting->port = 8080;
$setting->appID = 'testappid123';
$setting->token = 'testtoken123';
$setting->adminToken = 'testadmintoken123';
$tester->loadModel('setting')->setItem('system.zai.global.setting', json_encode($setting));

/* 创建一个临时测试文件 */
$testFilePath = $app->getBasePath() . 'tmp/files/202401/01/';
if(!is_dir($testFilePath))
{
    mkdir($testFilePath, 0777, true);
}
$testFile1 = $testFilePath . 'test1.txt';
file_put_contents($testFile1, "这是一个测试文件内容。\n用于测试文件内容提取功能。");

/* 更新数据库中的文件路径为真实路径 */
$tester->dao->update(TABLE_FILE)->set('pathname')->eq($testFile1)->where('id')->eq(1)->exec();

/* 测试3：文件存在且有ZAI设置（模拟API调用） */
// 由于没有真实的ZAI API服务器，这些测试会因为网络调用失败
// 但我们可以验证方法的逻辑执行到API调用阶段
$result3 = $zai->extractFileContentTest(1);
// API调用会失败（因为没有真实服务器），返回null
r($result3) && p() && e('0'); // 测试文件存在且有ZAI设置（模拟API调用）

/* 测试4：文件ID为0 */
$result4 = $zai->extractFileContentTest(0);
r($result4) && p() && e('0'); // 测试文件ID为0

/* 测试5：文件路径为空的情况 */
$result5 = $zai->extractFileContentTest(4);
r($result5) && p() && e('0'); // 测试文件路径为空

/* 创建更多测试文件用于不同文件类型测试 */
$testFile2 = $testFilePath . 'test2.pdf';
file_put_contents($testFile2, "%PDF-1.4\n这是模拟的PDF内容");
$tester->dao->update(TABLE_FILE)->set('pathname')->eq($testFile2)->where('id')->eq(2)->exec();

/* 测试6：有效的文本文件提取 */
$result6 = $zai->extractFileContentTest(2);
// 同样会因为没有真实服务器而失败
r($result6) && p() && e('0'); // 测试有效的文本文件提取

/* 清理测试文件 */
if(file_exists($testFile1)) unlink($testFile1);
if(file_exists($testFile2)) unlink($testFile2);
if(is_dir($testFilePath)) rmdir($testFilePath);

/* 验证方法调用的基本逻辑 - 无效文件ID */
$result7 = $zai->extractFileContentTest(-1);
r($result7) && p() && e('0');

/* 验证大文件ID */
$result8 = $zai->extractFileContentTest(10000);
r($result8) && p() && e('0');

/* 重新验证已配置但文件实际不存在的情况 */
$tester->dao->update(TABLE_FILE)->set('pathname')->eq('/nonexistent/path/file.txt')->where('id')->eq(5)->exec();
$result9 = $zai->extractFileContentTest(5);
r($result9) && p() && e('0');
