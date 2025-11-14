#!/usr/bin/env php
<?php

/**

title=测试 mailZen::getConfigForEdit();
timeout=0
cid=17039

- 步骤1：无配置返回false @0
- 步骤2：会话配置为空时返回false @0
- 步骤3：mail功能未开启时返回false @0
- 步骤4：配置对象为空时返回false @0
- 步骤5：配置不是对象时返回false @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/mailzen.unittest.class.php';

$mailZenTest = new mailZenTest();

r($mailZenTest->getConfigForEditZenTest()) && p() && e('0'); // 步骤1：无配置返回false
r($mailZenTest->getConfigForEditZenTest()) && p() && e('0'); // 步骤2：会话配置为空时返回false
r($mailZenTest->getConfigForEditZenTest()) && p() && e('0'); // 步骤3：mail功能未开启时返回false
r($mailZenTest->getConfigForEditZenTest()) && p() && e('0'); // 步骤4：配置对象为空时返回false
r($mailZenTest->getConfigForEditZenTest()) && p() && e('0'); // 步骤5：配置不是对象时返回false