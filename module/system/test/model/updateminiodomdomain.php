#!/usr/bin/env php
<?php

/**

title=测试 systemModel::updateMinioDomain();
timeout=0
cid=18751

- 执行systemTest模块的updateMinioDomainTest方法  @0
- 执行systemTest模块的updateMinioDomainTest方法  @0
- 执行systemTest模块的updateMinioDomainTest方法  @0
- 执行systemTest模块的updateMinioDomainTest方法  @0
- 执行systemTest模块的updateMinioDomainTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$systemTest = new systemModelTest();

r($systemTest->updateMinioDomainTest()) && p() && e('0');
r($systemTest->updateMinioDomainTest()) && p() && e('0');
r($systemTest->updateMinioDomainTest()) && p() && e('0');
r($systemTest->updateMinioDomainTest()) && p() && e('0');
r($systemTest->updateMinioDomainTest()) && p() && e('0');