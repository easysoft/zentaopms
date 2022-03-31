#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->getPairsForStory();
cid=1
pid=1

测试传入已有数据product=10，branch=0情况，正常返回 >> 3
测试传入product=41, branch=1的数据 >> 1
测试传入一个数组 >> 4
测试传入不存在的product >> 0
测试传入不同param情况 >> 3
测试传入不同param情况 >> 3

*/

$plan = new productPlan('admin');

$product = array(10, 41, 42, 1000);
$branch  = array(0, 1);
$param   = array('skipParent', 'withMainPlan', 'unexpired');

r($plan->getPairsForStory($product[0], $branch[0], $param[1])) && p('') && e('3'); //测试传入已有数据product=10，branch=0情况，正常返回
r($plan->getPairsForStory($product[1], $branch[1], $param[1])) && p('') && e('1'); //测试传入product=41, branch=1的数据
r($plan->getPairsForStory($product,    $branch[1], $param[1])) && p('') && e('4'); //测试传入一个数组
r($plan->getPairsForStory($product[3], $branch[1], $param[1])) && p('') && e('0'); //测试传入不存在的product
r($plan->getPairsForStory($product[0], $branch[0], $param[0])) && p('') && e('3'); //测试传入不同param情况
r($plan->getPairsForStory($product[0], $branch[0], $param[2])) && p('') && e('3'); //测试传入不同param情况
?>