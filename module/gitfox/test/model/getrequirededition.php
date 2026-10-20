#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getRequiredEdition();
timeout=0
cid=0

- 配置为Enterprise时返回小写版本 @enterprise
- 配置为COMMUNITY时返回小写版本 @community
- 配置两端带空格时先去空格 @community
- 配置为空字符串时按社区版处理 @community
- 配置被清空时按社区版处理 @community
- 恢复默认配置后返回值与配置一致 @1
- 返回值类型为string @string

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

$gitfoxTest = new gitfoxModelTest();
$original   = $gitfoxTest->instance->config->devops->gitfoxEdition;

$gitfoxTest->instance->config->devops->gitfoxEdition = 'Enterprise';
r($gitfoxTest->getRequiredEditionTest()) && p() && e('enterprise'); // 配置为Enterprise时返回小写版本

$gitfoxTest->instance->config->devops->gitfoxEdition = 'COMMUNITY';
r($gitfoxTest->getRequiredEditionTest()) && p() && e('community'); // 配置为COMMUNITY时返回小写版本

$gitfoxTest->instance->config->devops->gitfoxEdition = '  community  ';
r($gitfoxTest->getRequiredEditionTest()) && p() && e('community'); // 配置两端带空格时先去空格

$gitfoxTest->instance->config->devops->gitfoxEdition = '';
r($gitfoxTest->getRequiredEditionTest()) && p() && e('community'); // 配置为空字符串时按社区版处理

unset($gitfoxTest->instance->config->devops->gitfoxEdition);
r($gitfoxTest->getRequiredEditionTest()) && p() && e('community'); // 配置被清空时按社区版处理

$gitfoxTest->instance->config->devops->gitfoxEdition = $original;
r($gitfoxTest->getRequiredEditionTest() === strtolower(trim((string)$original))) && p() && e('1'); // 恢复默认配置后返回值与配置一致
r(gettype($gitfoxTest->getRequiredEditionTest())) && p() && e('string'); // 返回值类型为string
