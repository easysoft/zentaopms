#!/usr/bin/env php
<?php

/**

title=测试 gitfoxModel::getEdition();
timeout=0
cid=0

- 健康数据edition为Community时返回小写版本 @community
- 健康数据edition为Enterprise时返回小写版本 @enterprise
- 健康数据edition为空字符串时按社区版处理 @community
- 健康数据缺少edition字段时按社区版处理 @community
- 服务不可用时按社区版处理 @community
- 不传参数时调用真实GitFox服务且与health接口一致 @1
- 返回值类型为string @string

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(1);

su('admin');

$gitfoxTest = new gitfoxModelTest();
$gitfoxTest->seedGitFoxEntry();

$communityHealth  = (object)array('status' => 'healthy', 'edition' => 'Community', 'version' => '4.3');
$enterpriseHealth = (object)array('status' => 'healthy', 'edition' => 'Enterprise', 'version' => '4.3');
$emptyHealth      = (object)array('status' => 'healthy', 'edition' => '', 'version' => '4.3');
$oldHealth        = (object)array('status' => 'healthy', 'version' => '3.0');

r($gitfoxTest->getEditionTest($communityHealth)) && p() && e('community'); // 健康数据edition为Community时返回小写版本
r($gitfoxTest->getEditionTest($enterpriseHealth)) && p() && e('enterprise'); // 健康数据edition为Enterprise时返回小写版本
r($gitfoxTest->getEditionTest($emptyHealth)) && p() && e('community'); // 健康数据edition为空字符串时按社区版处理
r($gitfoxTest->getEditionTest($oldHealth)) && p() && e('community'); // 健康数据缺少edition字段时按社区版处理
r($gitfoxTest->getEditionTest(false)) && p() && e('community'); // 服务不可用时按社区版处理

$liveEdition = $gitfoxTest->getEditionTest();
$health      = $gitfoxTest->getHealthTest();
$healthEdition = is_object($health) && !empty($health->edition) ? strtolower(trim($health->edition)) : 'community';
r($liveEdition === $healthEdition) && p() && e('1'); // 不传参数时调用真实GitFox服务且与health接口一致
r(gettype($gitfoxTest->getEditionTest($communityHealth))) && p() && e('string'); // 返回值类型为string

$gitfoxTest->clearGitFoxEntry();
