#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 storyModel->replaceAllProductQuery();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->replaceAllProductQuery(''))                  && p() && e('0');             //不传入数据。
r($storyModel->replaceAllProductQuery("`product` = 'all'")) && p() && e('1 = 1');             //传入符合条件的查询语句。
r($storyModel->replaceAllProductQuery("`product` = 1"))     && p() && e('`product` = 1'); //传入不符合条件的查询语句。
