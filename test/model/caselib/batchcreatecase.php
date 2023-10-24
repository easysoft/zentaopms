#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->batchCreateCase();
cid=1
pid=1

添加两条数据之后查询数据条数是否正确 >> 12
添加数据之后查询新加用例的名称,用例类型 >> 测试导入添加1,performance
添加数据之后查询新加用例的名称,用例类型 >> 测试导入添加2,unit

*/

$caselib = new caselibTest();
$_POST = array(
        'module'       => array(0 => 0,               1 => 0),
        'title'        => array(0 => '测试导入添加1', 1 => '测试导入添加2'),
        'color'        => array(0 => '',              1 => ''),
        'type'         => array(0 => 'performance',   1 => 'unit'),
        'pri'          => array(0 => 2,               1 => 4),
        'precondition' => array(0 => '',              1 => ''),
        'keywords'     => array(0 => '',              1 => ''),
        'stage'        => array(0 => array(),         1 => array()),
);
$caselib->batchCreateCaseTest(201);
unset($_POST);
$total = $tester->dao->select('count(*) total')->from(TABLE_CASE)->where( 'lib')->eq(201)->fetch('total');
$cases = $tester->dao->select('*')->from(TABLE_CASE)->where( 'lib')->eq(201)->fetchAll('id');

r($total) && p()                 && e('12');                        // 添加两条数据之后查询数据条数是否正确
r($cases) && p('561:title,type') && e('测试导入添加1,performance'); // 添加数据之后查询新加用例的名称,用例类型
r($cases) && p('562:title,type') && e('测试导入添加2,unit');        // 添加数据之后查询新加用例的名称,用例类型

