#!/usr/bin/env php
<?php

/**

title=测试 fileModel->parseCSV();
timeout=0
cid=0

- 传入空参数 @0
- 传入禅道之外的文件 @0
- 检查解析csv文件的标题行。
 -  @用例编号
 - 属性1 @用例名称
- 检查解析csv数据的ID和标题
 -  @2490
 - 属性1 @搭建渠成软件的云服务

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$csvString = <<<EOD
"用例编号","用例名称","步骤","预期","实际情况","关键词","优先级","类型","适用阶段","用例状态","执行人","执行时间","结果","由谁创建","创建日期"
"2490","搭建渠成软件的云服务","1. 检查渠成软件的云服务网站可否正常访问
2. 检查站点的升级、降级业务是否取消
3. 检查云服务购买是否有人数限制","1. 可正常访问渠成云服务网站
2. 站点没有升级、降级功能
3. 云服务套餐中有人数限制","1.
2.
3.","","3","功能测试","
功能测试阶段","正常","admin","2020-06-08 13:33:35","失败","admin","2020-05-26"
EOD;
$csvFile = dirname(__FILE__) . '/test.csv';
file_put_contents($csvFile, $csvString);

global $tester;
$fileModel = $tester->loadModel('file');

r($fileModel->parseCSV(''))         && p() && e(0); // 传入空参数
r($fileModel->parseCSV('/tmp/aaa')) && p() && e(0); // 传入禅道之外的文件

$csvData = $fileModel->parseCSV($csvFile);
r($csvData[0])   && p('0,1') && e('用例编号,用例名称');                    // 检查解析csv文件的标题行。
r($csvData[1])   && p('0,1') && e('2490,搭建渠成软件的云服务');            // 检查解析csv数据的ID和标题
r($csvData[1])   && p('2') && e('1. 检查渠成软件的云服务网站可否正常访问
2. 检查站点的升级、降级业务是否取消
3. 检查云服务购买是否有人数限制');                                         //检查解析csv的步骤

unlink($csvFile);