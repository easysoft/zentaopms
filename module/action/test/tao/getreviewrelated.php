#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getReviewRelated().
timeout=0
cid=1

- 测试当objectType为review,objectID为15时，返回的数据是否正确
 - 第0条的5属性 @5
 - 属性1 @15

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

$actionTest = new actionTest();

zdTable('review')->gen(15);
zdTable('projectproduct')->gen(20);

r($actionTest->getReviewRelated('review', 15)) && p('0:5;1') && e('5,15');   //测试当objectType为review,objectID为15时，返回的数据是否正确