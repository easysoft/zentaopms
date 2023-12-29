#!/usr/bin/env php
<?php

/**

title=测试 repoModel->getProductsByRepo();
timeout=0
cid=1

- 空的数据 @0
- 不存在的数据 @0
- 存在的数据属性1 @正常产品1
- 产品不存在的数据 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';

zdTable('product')->gen(3);
zdTable('repo')->config('repo')->gen(5);
su('admin');

$repo = new repoTest();

r($repo->getProductsByRepoTest(0)) && p()    && e('0');         // 空的数据
r($repo->getProductsByRepoTest(6)) && p()    && e('0');         // 不存在的数据
r($repo->getProductsByRepoTest(1)) && p('1') && e('正常产品1'); // 存在的数据
r($repo->getProductsByRepoTest(4)) && p()    && e('0');         // 产品不存在的数据