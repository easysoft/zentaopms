#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

/**

title=productModel->manageLine();
cid=1
pid=1

测试修改产品线名称 >> 产品线2
测试修改所属项目集 >> 1
测试创建新的产品线 >> 产品线21

*/

$product = new productTest('admin');

$modules  = array('id1' => '产品线2', 'id2' => '产品线2', 'id3' => '产品线3');
$programs = array('id1' => '1', 'id2' => '2', 'id3' => '3');
$lines1   = array('modules' => $modules, 'programs' => $programs);

$modules  = array('id1' => '产品线1', 'id2' => '产品线2', 'id3' => '产品线3');
$programs = array('id1' => '1', 'id2' => '1', 'id3' => '3');
$lines2   = array('modules' => $modules, 'programs' => $programs);

$modules  = array(0 => '产品线21', 1 => '');
$programs = array(0 => '1', 1 => '1');
$lines3   = array('modules' => $modules, 'programs' => $programs);

$modules  = array(0 => '');
$programs = array(0 => '1');
$lines4   = array('modules' => $modules, 'programs' => $programs);

r($product->manageLineTest($lines1, 2)) && p('name') && e('产品线2');  // 测试修改产品线名称
r($product->manageLineTest($lines2, 1)) && p('root') && e('1');        // 测试修改所属项目集
r($product->manageLineTest($lines3))    && p('name') && e('产品线21'); // 测试创建新的产品线
r($product->manageLineTest($lines4))    && p()       && e('');         // 测试创建没有项目集的产品线
