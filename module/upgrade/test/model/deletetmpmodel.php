#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->deleteTmpModel();
cid=1

- 测试删除临时 model 文件，然后获取 tmp 目录下文件数量。 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$upgrade = new upgradeTest();
r($upgrade->deleteTmpModelTest()) && p() && e('0'); // 测试删除临时 model 文件，然后获取 tmp 目录下文件数量。
