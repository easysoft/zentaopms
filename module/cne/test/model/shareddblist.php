#!/usr/bin/env php
<?php

/**

title=测试 cneModel::sharedDBList();
timeout=0
cid=15628

- 执行cneTest模块的sharedDBListTest方法，参数是''  @0
- 执行cneTest模块的sharedDBListTest方法，参数是'mysql'
 - 第zentaopaas-mysql条的name属性 @zentaopaas-mysql
 - 第zentaopaas-mysql条的kind属性 @mysql
 - 第zentaopaas-mysql条的port属性 @3306
- 执行cneTest模块的sharedDBListTest方法，参数是'postgresql'
 - 第postgres-shared条的name属性 @postgres-shared
 - 第postgres-shared条的kind属性 @postgresql
 - 第postgres-shared条的port属性 @5432
- 执行cneTest模块的sharedDBListTest方法，参数是'mongodb'  @0
- 执行cneTest模块的sharedDBListTest方法，参数是'redis'
 - 第redis-cluster条的name属性 @redis-cluster
 - 第redis-cluster条的kind属性 @redis
 - 第redis-cluster条的port属性 @6379

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

r($cneTest->sharedDBListTest('')) && p() && e('0');
r($cneTest->sharedDBListTest('mysql')) && p('zentaopaas-mysql:name,kind,port') && e('zentaopaas-mysql,mysql,3306');
r($cneTest->sharedDBListTest('postgresql')) && p('postgres-shared:name,kind,port') && e('postgres-shared,postgresql,5432');
r($cneTest->sharedDBListTest('mongodb')) && p() && e('0');
r($cneTest->sharedDBListTest('redis')) && p('redis-cluster:name,kind,port') && e('redis-cluster,redis,6379');