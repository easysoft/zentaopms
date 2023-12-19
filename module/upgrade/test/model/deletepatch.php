#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->deletePatch();
cid=1

- 测试删除补丁记录，然后获取补丁记录数量。 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();
r($upgrade->deletePatchTest()) && p() && e('0'); // 测试删除补丁记录，然后获取补丁记录数量。
