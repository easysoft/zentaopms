#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/caselib.class.php';
su('admin');

/**

title=测试 caselibModel->createFromImport();
cid=1
pid=1

添加两条数据之后查询数据条数是否正确 >> 12
添加数据之后查询新加用例的名称，关键字 >> 测试导入添加1,keywords1
添加数据之后查询新加用例的名称，关键字 >> 测试导入添加2,keywords2

*/

$caselib = new caselibTest();
$_POST = array(
        'lib'          => array(2 => 201,             3 => 201),
        'title'        => array(2 => '测试导入添加1', 3 => '测试导入添加2'),
        'module'       => array(2 => 0,               3 => 0),
        'pri'          => array(2 => 2,               3 => 4),
        'type'         => array(2 => 'performance',   3 => 'unit'),
        'stage'        => array(2 => array(),         3 => array()),
        'keywords'     => array(2 => 'keywords1',     3 => 'keywords2'),
        'status'       => array(2 => '',              3 => ''),
        'desc'         => array(2 => array(),         3 => array()),
        'precondition' => array(2 => '',              3 => ''),
        'isEndPage'    => 0,
        'pagerID'      => 1
);

$caselib->createFromImportTest(201);
unset($_POST);
$total = $tester->dao->select('count(*) total')->from(TABLE_CASE)->where( 'lib')->eq(201)->fetch('total');
$cases = $tester->dao->select('*')->from(TABLE_CASE)->where( 'lib')->eq(201)->fetchAll('id');

r($total) && p()                     && e('12');                      //添加两条数据之后查询数据条数是否正确
r($cases) && p('561:title,keywords') && e('测试导入添加1,keywords1'); //添加数据之后查询新加用例的名称，关键字
r($cases) && p('562:title,keywords') && e('测试导入添加2,keywords2'); //添加数据之后查询新加用例的名称，关键字

