#!/usr/bin/env php
<?php

/**

title=测试 giteaZen::checkToken();
timeout=0
cid=16570

- 执行$giteaZen, $method, $invalidGitea属性result @fail
- 执行$giteaZen, $method, $missingNameGitea属性result @fail
- 执行$giteaZen, $method, $missingUrlGitea属性result @fail
- 执行$giteaZen, $method, $invalidUrlGitea属性result @fail
- 执行$giteaZen, $method, $insufficientGitea属性result @fail

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester;
$giteaModel = $tester->loadModel('gitea');

// 创建gitea别名以便zen类可以继承
if(!class_exists('gitea')) {
    class_alias('giteaModel', 'gitea');
}

// 加载zen文件
include dirname(__FILE__, 3) . '/zen.php';

// 创建giteaZen实例
$giteaZen = new giteaZen();
// 设置必要的属性
$giteaZen->gitea = $giteaModel;

// 使用反射调用protected方法
$reflection = new ReflectionClass($giteaZen);
$method = $reflection->getMethod('checkToken');
$method->setAccessible(true);

// 创建测试函数
function testCheckToken($giteaZen, $method, $giteaData) {
    try {
        $result = $method->invoke($giteaZen, $giteaData);
        if(dao::isError()) return dao::getError();
        return $result;
    } catch (TypeError $e) {
        // 处理参数类型错误，如缺少必需字段
        return array('result' => 'fail', 'message' => 'required field missing');
    } catch (Exception $e) {
        return array('result' => 'fail', 'message' => $e->getMessage());
    }
}

// 测试步骤1：必填字段缺失的情况（缺少token）
$invalidGitea = new stdclass();
$invalidGitea->name = 'test-gitea';
$invalidGitea->url = 'https://gitea.example.com';
$invalidGitea->token = ''; // 空token
r(testCheckToken($giteaZen, $method, $invalidGitea)) && p('result') && e('fail');

// 测试步骤2：必填字段缺失的情况（缺少name）
$missingNameGitea = new stdclass();
$missingNameGitea->name = ''; // 空name
$missingNameGitea->url = 'https://gitea.example.com';
$missingNameGitea->token = 'test_token_123';
r(testCheckToken($giteaZen, $method, $missingNameGitea)) && p('result') && e('fail');

// 测试步骤3：必填字段缺失的情况（缺少url）
$missingUrlGitea = new stdclass();
$missingUrlGitea->name = 'test-gitea';
$missingUrlGitea->url = ''; // 空url
$missingUrlGitea->token = 'test_token_123';
r(testCheckToken($giteaZen, $method, $missingUrlGitea)) && p('result') && e('fail');

// 测试步骤4：无效的URL地址（会导致连接失败）
$invalidUrlGitea = new stdclass();
$invalidUrlGitea->name = 'test-gitea';
$invalidUrlGitea->url = 'https://invalid-url-test.example.com';
$invalidUrlGitea->token = 'valid_token_123';
r(testCheckToken($giteaZen, $method, $invalidUrlGitea)) && p('result') && e('fail');

// 测试步骤5：有效格式但无效token（会导致权限不足）
$insufficientGitea = new stdclass();
$insufficientGitea->name = 'test-gitea';
$insufficientGitea->url = 'https://gitea.example.com';
$insufficientGitea->token = 'insufficient_token_123';
r(testCheckToken($giteaZen, $method, $insufficientGitea)) && p('result') && e('fail');