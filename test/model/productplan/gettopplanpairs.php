#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

$plan = new productPlan('admin');

$param = array();
$param[0] = array('productID' => 1,  'branch' => '', 'exclude' => '');
$param[1] = array('productID' => 41, 'branch' => 1,  'exclude' => '');
$param[2] = array('productID' => 41, 'branch' => 2,  'exclude' => '');

r($plan->getTopPlanPairsTest($param[0]))  && p('1,2') && e('1.0,1.1'); //测试获取正常产品的顶级计划
r($plan->getTopPlanPairsTest($param[1]))  && p('31')  && e('1.0');     //测试获取分支1计划的正常产品的顶级计划
r($plan->getTopPlanPairsTest($param[2]))  && p('32')  && e('1.1');     //测试获取分支2计划的正常产品的顶级计划
?>
