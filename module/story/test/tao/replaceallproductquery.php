#!/usr/bin/env php
<?php

/**

title=测试 storyModel->replaceAllProductQuery();
timeout=0
cid=18657

- 不传入数据。 @0
- 传入符合条件的查询语句。 @1 = 1
- 传入不符合条件的查询语句。 @`product` = 1
- all不带引号 @`product` = all
- product只有一半反引号 @product`  = all

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->replaceAllProductQuery(''))                  && p() && e('0'); //不传入数据。
r($storyModel->replaceAllProductQuery("`product` = 'all'")) && p() && e('1 = 1'); //传入符合条件的查询语句。
r($storyModel->replaceAllProductQuery("`product` = 1"))     && p() && e('`product` = 1'); //传入不符合条件的查询语句。
r($storyModel->replaceAllProductQuery("`product` = all"))   && p() && e('`product` = all'); //all不带引号
r($storyModel->replaceAllProductQuery("product`  = all"))   && p() && e('product`  = all'); //product只有一半反引号