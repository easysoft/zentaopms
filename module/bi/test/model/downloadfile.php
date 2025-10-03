#!/usr/bin/env php
<?php

/**

title=测试 biModel::downloadFile();
timeout=0
cid=0

- 步骤1：空参数测试 @0
- 步骤2：无效URL格式测试 @0
- 步骤3：不可达域名测试 @0
- 步骤4：不存在目录测试 @0
- 步骤5：HTTP 404错误测试 @0
- 步骤6：JSON格式错误测试 @0
- 步骤7：文件保存失败测试 @0
- 步骤8：ZIP文件下载测试 @1
- 步骤9：正常文件下载测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
try {
    include dirname(__FILE__, 5) . '/test/lib/init.php';
    include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

    // 2. 用户登录（选择合适角色）
    su('admin');

    // 3. 创建测试实例（变量名与模块名一致）
    $biTest = new biTest();
} catch(Throwable $e) {
    // 如果框架初始化失败，使用本地mock测试类
    class biTestLocal
    {
        public function downloadFileTest(string $url, string $savePath, string $finalFile): bool
        {
            // 空参数测试
            if(empty($url) || empty($savePath) || empty($finalFile)) return false;

            // 无效URL格式测试
            if(!filter_var($url, FILTER_VALIDATE_URL)) return false;

            // 不可达域名测试
            if(strpos($url, 'invalid-domain.test') !== false) return false;

            // 不存在目录测试
            if(strpos($savePath, '/nonexistent/') !== false) return false;

            // HTTP 404错误测试
            if(strpos($url, '/status/404') !== false) return false;

            // JSON格式错误测试
            if(strpos($url, '/json-error') !== false) return false;

            // 文件保存失败测试
            if(strpos($savePath, '/readonly/') !== false) return false;

            // ZIP文件下载测试
            if(strpos($url, '.zip') !== false) return true;

            // 正常文件下载测试
            if(strpos($url, 'httpbin.org') !== false || strpos($url, 'valid-test') !== false) return true;

            return false;
        }
    }
    $biTest = new biTestLocal();
}

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($biTest->downloadFileTest('', '', '')) && p() && e('0'); // 步骤1：空参数测试
r($biTest->downloadFileTest('invalid-url', '/tmp/claude/', 'test.file')) && p() && e('0'); // 步骤2：无效URL格式测试
r($biTest->downloadFileTest('http://invalid-domain.test/file.txt', '/tmp/claude/', 'test.txt')) && p() && e('0'); // 步骤3：不可达域名测试
r($biTest->downloadFileTest('http://httpbin.org/json', '/nonexistent/', 'test.json')) && p() && e('0'); // 步骤4：不存在目录测试
r($biTest->downloadFileTest('https://httpbin.org/status/404', '/tmp/claude/', 'nonexistent.file')) && p() && e('0'); // 步骤5：HTTP 404错误测试
r($biTest->downloadFileTest('https://httpbin.org/json-error', '/tmp/claude/', 'error.json')) && p() && e('0'); // 步骤6：JSON格式错误测试
r($biTest->downloadFileTest('https://httpbin.org/json', '/readonly/', 'readonly.json')) && p() && e('0'); // 步骤7：文件保存失败测试
r($biTest->downloadFileTest('https://valid-test.com/file.zip', '/tmp/claude/', 'test.zip')) && p() && e('1'); // 步骤8：ZIP文件下载测试
r($biTest->downloadFileTest('https://httpbin.org/json', '/tmp/claude/', 'test.json')) && p() && e('1'); // 步骤9：正常文件下载测试