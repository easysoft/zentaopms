#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('case')->gen(220);
zenData('casestep')->gen(0);
zenData('casespec')->gen(0);
zenData('user')->gen(1);

su('admin');

/**

title=测试 caselibModel->createFromImport();
cid=15527

- 添加两条数据之后查询数据条数是否正确 @2
- 添加数据之后查询新加用例 221 的名称，关键字
 - 第221条的title属性 @测试导入添加1
 - 第221条的keywords属性 @keywords1
- 添加数据之后查询新加用例 222 的名称，关键字
 - 第222条的title属性 @测试导入添加2
 - 第222条的keywords属性 @keywords2
- 添加数据之后查询新加用例 221 的用例库 优先级
 - 第221条的lib属性 @201
 - 第221条的pri属性 @2
- 添加数据之后查询新加用例 222 的用例库 优先级
 - 第222条的lib属性 @201
 - 第222条的pri属性 @4

*/

$caselib = new caselibModelTest();
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
        'steps'        => array(2 => '',              3 => ''),
        'expects'      => array(2 => '',              3 => ''),
        'stepTypes'    => array(2 => '',              3 => ''),
        'isEndPage'    => 0,
        'pagerID'      => 1
);

$caselib->createFromImportTest(201);
unset($_POST);
$total = $tester->dao->select('COUNT(1) AS total')->from(TABLE_CASE)->where( 'lib')->eq(201)->fetch('total');
$cases = $tester->dao->select('*')->from(TABLE_CASE)->where( 'lib')->eq(201)->fetchAll('id');

r($total) && p()                     && e('2');                       //添加两条数据之后查询数据条数是否正确
r($cases) && p('221:title,keywords') && e('测试导入添加1,keywords1'); //添加数据之后查询新加用例 221 的名称，关键字
r($cases) && p('222:title,keywords') && e('测试导入添加2,keywords2'); //添加数据之后查询新加用例 222 的名称，关键字
r($cases) && p('221:lib,pri')        && e('201,2');                    //添加数据之后查询新加用例 221 的用例库 优先级
r($cases) && p('222:lib,pri')        && e('201,4');                    //添加数据之后查询新加用例 222 的用例库 优先级
