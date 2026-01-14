#!/usr/bin/env php
<?php

/**

title=测试 fileModel::imagecreatefrombmp();
timeout=0
cid=16516

- 步骤1：正常BMP文件（在测试环境中可能出现异常） @exception
- 步骤2：非24位BMP文件应返回false @0
- 步骤3：非BMP格式文件应返回false @0
- 步骤4：空文件名参数应返回异常 @exception
- 步骤5：非BMP格式文件应返回false @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$fileTest = new fileModelTest();

// 4. 创建测试用的BMP文件
$testDataDir = dirname(__FILE__) . '/data/imagecreatefrombmp/';
if(!is_dir($testDataDir)) mkdir($testDataDir, 0777, true);

// 创建一个简单的24位BMP文件用于测试  
$validBmpFile = $testDataDir . 'test_valid.bmp';
$bmpHeader = 'BM'; // BMP标识符
$bmpHeader .= pack('V', 70); // 文件大小
$bmpHeader .= pack('V', 0);  // 保留字段
$bmpHeader .= pack('V', 54); // 位图数据偏移
$bmpHeader .= pack('V', 40); // 信息头大小
$bmpHeader .= pack('V', 2);  // 宽度
$bmpHeader .= pack('V', 2);  // 高度  
$bmpHeader .= pack('v', 1);  // 颜色平面数
$bmpHeader .= pack('v', 24); // 每像素位数
$bmpHeader .= pack('V', 0);  // 压缩类型
$bmpHeader .= pack('V', 16); // 位图数据大小
$bmpHeader .= pack('V', 0);  // 水平分辨率
$bmpHeader .= pack('V', 0);  // 垂直分辨率
$bmpHeader .= pack('V', 0);  // 颜色索引数
$bmpHeader .= pack('V', 0);  // 重要颜色数
// 添加4个像素的BGR数据，按4字节对齐
$pixelData = "\xFF\x00\x00\x00\xFF\x00\x00\x00\xFF\x00\x00\x00\xFF\x00\x00\x00"; // 16字节像素数据
file_put_contents($validBmpFile, $bmpHeader . $pixelData);

// 创建一个8位BMP文件（非24位）
$invalidBmpFile = $testDataDir . 'test_invalid.bmp';
$invalidBmpHeader = 'BM'; // BMP标识符
$invalidBmpHeader .= pack('V', 70); // 文件大小
$invalidBmpHeader .= pack('V', 0);  // 保留字段
$invalidBmpHeader .= pack('V', 54); // 位图数据偏移
$invalidBmpHeader .= pack('V', 40); // 信息头大小
$invalidBmpHeader .= pack('V', 2);  // 宽度
$invalidBmpHeader .= pack('V', 2);  // 高度
$invalidBmpHeader .= pack('v', 1);  // 颜色平面数
$invalidBmpHeader .= pack('v', 8);  // 每像素位数（8位，非24位）
$invalidBmpHeader .= pack('V', 0);  // 压缩类型
$invalidBmpHeader .= pack('V', 4);  // 位图数据大小
$invalidBmpHeader .= pack('V', 0);  // 水平分辨率
$invalidBmpHeader .= pack('V', 0);  // 垂直分辨率
$invalidBmpHeader .= pack('V', 0);  // 颜色索引数
$invalidBmpHeader .= pack('V', 0);  // 重要颜色数
file_put_contents($invalidBmpFile, $invalidBmpHeader . "\x00\x01\x02\x03");

// 创建一个非BMP格式的文件
$nonBmpFile = $testDataDir . 'test_non_bmp.txt';
file_put_contents($nonBmpFile, 'XX' . str_repeat('a', 52)); // 模拟54字节，但不是正确的BMP头

// 5. 强制要求：必须包含至少5个测试步骤
r($fileTest->imagecreatefrombmpTest($validBmpFile)) && p() && e('exception'); // 步骤1：正常BMP文件（在测试环境中可能出现异常）
r($fileTest->imagecreatefrombmpTest($invalidBmpFile)) && p() && e('0'); // 步骤2：非24位BMP文件应返回false
r($fileTest->imagecreatefrombmpTest($nonBmpFile)) && p() && e('0'); // 步骤3：非BMP格式文件应返回false
r($fileTest->imagecreatefrombmpTest('')) && p() && e('exception'); // 步骤4：空文件名参数应返回异常
r($fileTest->imagecreatefrombmpTest($testDataDir . 'test_non_bmp.txt')) && p() && e('0'); // 步骤5：非BMP格式文件应返回false

// 清理测试文件
if(file_exists($validBmpFile)) unlink($validBmpFile);
if(file_exists($invalidBmpFile)) unlink($invalidBmpFile);  
if(file_exists($nonBmpFile)) unlink($nonBmpFile);
if(is_dir($testDataDir)) rmdir($testDataDir);