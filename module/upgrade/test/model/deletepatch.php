#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->deletePatch();
cid=19515

- 测试删除补丁记录，然后获取补丁记录数量。 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$upgrade = new upgradeModelTest();
r($upgrade->deletePatchTest()) && p() && e('0'); // 测试删除补丁记录，然后获取补丁记录数量。
