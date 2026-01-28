#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 screenModel->getList();
timeout=0
cid=18244

- 测试获取dimension=0的数据 @0
- 测试获取dimension=1的数据 @5
- 测试获取dimension=2的数据 @2
- 测试获取dimension=3的数据 @1
- 测试获取dimension=10000的数据 @0

*/

$screen = new screenModelTest();

$dimension = array(0, 1, 2, 3, 10000);
r(count($screen->getListTest($dimension[0]))) && p('') && e(0); //测试获取dimension=0的数据
r(count($screen->getListTest($dimension[1]))) && p('') && e(6); //测试获取dimension=1的数据
r(count($screen->getListTest($dimension[2]))) && p('') && e(2); //测试获取dimension=2的数据
r(count($screen->getListTest($dimension[3]))) && p('') && e(1); //测试获取dimension=3的数据
r(count($screen->getListTest($dimension[4]))) && p('') && e(0); //测试获取dimension=10000的数据