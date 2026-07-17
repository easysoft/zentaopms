#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getZaiBaseUrl();
timeout=0
cid=0

- 步骤1：配置了 url 时直接返回并去除尾部斜杠 @https://zai.example.com
- 步骤2：未配置 url 且无 port 时使用 http 协议拼接 host @http://localhost
- 步骤3：配置了 port 时在 host 后追加端口 @http://localhost:8080
- 步骤4：HTTPS 环境时使用 https 协议 @https://zai.local
- 步骤5：通过 X-Forwarded-Proto 头判断为 https @https://proxy.local

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

/* 步骤1：配置了 url 时直接返回并去除尾部斜杠 */
$setting1 = (object)array('url' => 'https://zai.example.com/', 'host' => 'ignored', 'port' => '9999');
r($aiTest->getZaiBaseUrlTest($setting1)) && p() && e('https://zai.example.com'); // 步骤1：配置了 url 时直接返回并去除尾部斜杠

/* 步骤2：未配置 url 且无 port 时使用 http 协议拼接 host */
$setting2 = (object)array('host' => 'localhost');
r($aiTest->getZaiBaseUrlTest($setting2)) && p() && e('http://localhost'); // 步骤2：未配置 url 且无 port 时使用 http 协议拼接 host

/* 步骤3：配置了 port 时在 host 后追加端口 */
$setting3 = (object)array('host' => 'localhost', 'port' => '8080');
r($aiTest->getZaiBaseUrlTest($setting3)) && p() && e('http://localhost:8080'); // 步骤3：配置了 port 时在 host 后追加端口

/* 步骤4：HTTPS 环境时使用 https 协议 */
$setting4 = (object)array('host' => 'zai.local');
r($aiTest->getZaiBaseUrlTest($setting4, array('HTTPS' => 'on'))) && p() && e('https://zai.local'); // 步骤4：HTTPS 环境时使用 https 协议

/* 步骤5：通过 X-Forwarded-Proto 头判断为 https */
$setting5 = (object)array('host' => 'proxy.local');
r($aiTest->getZaiBaseUrlTest($setting5, array('HTTP_X_FORWARDED_PROTO' => 'https'))) && p() && e('https://proxy.local'); // 步骤5：通过 X-Forwarded-Proto 头判断为 https