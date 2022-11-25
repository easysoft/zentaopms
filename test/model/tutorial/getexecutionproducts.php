#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/tutorial.class.php';
su('admin');

/**

title=测试 tutorialModel->getExecutionProducts();
cid=1
pid=1

测试拿到的数据是否正确 >> normal
测试拿到的数据是否正确 >> Test branch
测试拿到的数据是否正确 >> Test plan

*/

$tutorial = new tutorialTest();

r($tutorial->getExecutionProductsTest()) && p('1:type')        && e('normal');      //测试拿到的数据是否正确
r($tutorial->getExecutionProductsTest()) && p('1[branches]:1') && e('Test branch'); //测试拿到的数据是否正确
r($tutorial->getExecutionProductsTest()) && p('1[plans]:1')    && e('Test plan');   //测试拿到的数据是否正确