#!/usr/bin/env php
<?php
/**

title=测试 upgradeModel->updateClassifyLang();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$langData = zenData('lang');
$langData->key->range('support,engineering,project');
$langData->gen(6);
