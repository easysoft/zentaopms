#!/usr/bin/env php
<?php
/**

title=productTao->getStoryStats();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->gen(50);
zdTable('story')->gen(50);
zdTable('user')->gen(5);
su('admin');

$productIdList[] = array();
$productIdList[] = range(1, 10);
$productIdList[] = range(10, 20);

$productTester = new productTest();
r($productTester->getStoryStatsTest($productIdList[0])) && p('1:draft,active,changing,reviewing,finished,closed,total')  && e('0,1,1,0,0,0,2'); // 测试系统中所有产品下的需求统计信息
r($productTester->getStoryStatsTest($productIdList[1])) && p('2:draft,active,changing,reviewing,finished,closed,total')  && e('0,1,1,0,0,0,2'); // 测试产品id为1-10的产品下的需求统计信息
r($productTester->getStoryStatsTest($productIdList[2])) && p('11:draft,active,changing,reviewing,finished,closed,total') && e('0,1,1,0,0,0,2'); // 测试产品id为11-20的产品下的需求统计信息
