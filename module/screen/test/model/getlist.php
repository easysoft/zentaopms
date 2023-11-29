#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/screen.class.php';

zdTable('screen')->gen(0);
zdTable('screen')->config('screen-1')->gen(1, false, false);
zdTable('screen')->config('screen-2')->gen(1, false, false);

/**

title=测试 screenModel->getList();
cid=1
pid=1

测试获取dimension=0的数据       >> 0
测试获取dimension=1的数据       >> 2
测试获取dimension=10000的数据   >> 0
*/

$screen = new screenTest();

$dimension = array(0, 1, 10000);
r(count($screen->getListTest($dimension[0]))) && p('') && e(0); //测试获取dimension=0的数据
r(count($screen->getListTest($dimension[1]))) && p('') && e(2); //测试获取dimension=1的数据
r(count($screen->getListTest($dimension[2]))) && p('') && e(0); //测试获取dimension=10000的数据
