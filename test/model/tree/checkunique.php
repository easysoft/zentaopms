#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tree.class.php';
su('admin');

/**

title=测试 treeModel->checkUnique();
cid=1
pid=1

测试module1是否与已存在的模块重名 >> 0
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品模块1
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品模块2
测试module1是否与已存在的模块重名 >> 产品模块3
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品模块1
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品模块2
测试module1是否与已存在的模块重名 >> 0
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品模块161
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品模块161
测试module1是否与已存在的模块重名 >> 产品子模块281
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 产品子模块281
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 0
测试module1是否与已存在的模块重名 >> 0
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 0
测试module1 和 moduleID1 里的模块是否与已存在的模块重名 >> 0

*/
$module1 = new stdclass();
$module1->name   = '不会重名的模块';
$module1->root   = '1';
$module1->branch = '0';
$module1->type   = 'story';
$module1->parent = '0';

$module2 = new stdclass();
$module2->name   = '产品模块3';
$module2->root   = '1';
$module2->branch = '0';
$module2->type   = 'story';
$module2->parent = '0';

$module3 = new stdclass();
$module3->name   = '产品模块162';
$module3->root   = '41';
$module3->branch = '2';
$module3->type   = 'story';
$module3->parent = '0';

$module4 = new stdclass();
$module4->name   = '产品子模块281';
$module4->root   = '41';
$module4->branch = '0';
$module4->type   = 'story';
$module4->parent = '2381';

$module5 = new stdclass();
$module5->name   = '执行子模块1';
$module5->root   = '101';
$module5->branch = '0';
$module5->type   = 'story';
$module5->parent = '21';

$moduleID1 = array(1821, 1823, 1825, 1826, 1981, 1982, 1985, 1986, 21, 2901, 3021);
$moduleID2 = array(1822, 1823, 1825, 1826, 1981, 1822);
$branches  = array(0, 1, 2);

$tree = new treeTest();

r($tree->checkUniqueTest($module1))                        && p() && e('0');             // 测试module1是否与已存在的模块重名
r($tree->checkUniqueTest($module1, $moduleID1, $branches)) && p() && e('产品模块1');     // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module1, $moduleID2, $branches)) && p() && e('产品模块2');     // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module2))                        && p() && e('产品模块3');     // 测试module1是否与已存在的模块重名
r($tree->checkUniqueTest($module2, $moduleID1, $branches)) && p() && e('产品模块1');     // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module2, $moduleID2, $branches)) && p() && e('产品模块2');     // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module3))                        && p() && e('0');             // 测试module1是否与已存在的模块重名
r($tree->checkUniqueTest($module3, $moduleID1, $branches)) && p() && e('产品模块161');   // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module3, $moduleID2, $branches)) && p() && e('产品模块161');   // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module4))                        && p() && e('产品子模块281'); // 测试module1是否与已存在的模块重名
r($tree->checkUniqueTest($module4, $moduleID1, $branches)) && p() && e('产品子模块281'); // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module4, $moduleID2, $branches)) && p() && e('0');             // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module5))                        && p() && e('0');             // 测试module1是否与已存在的模块重名
r($tree->checkUniqueTest($module5, $moduleID1, $branches)) && p() && e('0');             // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名
r($tree->checkUniqueTest($module5, $moduleID2, $branches)) && p() && e('0');             // 测试module1 和 moduleID1 里的模块是否与已存在的模块重名