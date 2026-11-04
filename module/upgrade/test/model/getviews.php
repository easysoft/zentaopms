#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->getViews();
timeout=0
cid=73150

- 解析数据库视图定义文件，视图条目数应与 CREATE VIEW 语句数一致，且都成对包含删除语句 @views:39;creates:39;missing:0;dropped:39

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$upgrade = new upgradeModelTest();

r($upgrade->getViewsTest()) && p() && e('views:39;creates:39;missing:0;dropped:39'); //解析数据库视图定义文件，视图条目数应与 CREATE VIEW 语句数一致，且都成对包含删除语句
