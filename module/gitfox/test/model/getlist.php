#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitfox.class.php';
su('admin');

/**

title=测试gitfoxModel->gitList();
timeout=0
cid=1

- 获取GitFox列表第1条的id属性 @1
- 获取GitFox列表数量 @1

*/

zdTable('pipeline')->gen(7);
$gitfox = new gitfoxTest();

$orderBy    = 'id_desc';
$gitfoxList = $gitfox->getList($orderBy);
r($gitfoxList)        && p('7:id') && e('1');    // 获取GitFox列表
r(count($gitfoxList)) && p('')     && e('1'); // 获取GitFox列表数量
