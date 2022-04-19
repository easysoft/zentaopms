#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/report.class.php';
su('admin');

/**

title=测试 reportModel->getProducts();
cid=1
pid=1



*/
$conditions = array('', 'closedProduct', 'overduePlan');
$storyType  = 'requirement';

$report = new reportTest();

r($report->getProductsTest($conditions[0]))             && p() && e(''); // 测试获取 story 产品
r($report->getProductsTest($conditions[1]))             && p() && e(''); // 测试获取 story closedProduct 产品
r($report->getProductsTest($conditions[2]))             && p() && e(''); // 测试获取 story overduePlan 产品
r($report->getProductsTest($conditions[0], $storyType)) && p() && e(''); // 测试获取 requirement 产品
r($report->getProductsTest($conditions[1], $storyType)) && p() && e(''); // 测试获取 requirement closedProduct 产品
r($report->getProductsTest($conditions[2], $storyType)) && p() && e(''); // 测试获取 requirement overduePlan 产品