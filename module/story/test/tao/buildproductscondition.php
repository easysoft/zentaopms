#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

zdTable('product')->gen(50);

/**

title=测试 storyModel->buildProductsCondition();
cid=1
pid=1

*/

global $tester;
$storyModel = $tester->loadModel('story');
$condition  = $storyModel->buildProductsCondition('');
r($condition == "`product` = '0'") && p() && e('1');              //不传入数据
$condition  = $storyModel->buildProductsCondition(0);
r($condition == "`product` = '0'") && p() && e('1');              //不传入数据
$condition = $storyModel->buildProductsCondition('1,2');
r($condition == "`product` IN ('1','2')")  && p() && e('1');      //传入正常产品 ID。
$condition = $storyModel->buildProductsCondition('1,2', '1');
r($condition == "`product` IN ('1','2')")  && p() && e('1');      //传入正常产品 ID 和传入分支。
$condition = $storyModel->buildProductsCondition('1,2,41');
r($condition == "`product` IN ('1','2','41')")  && p() && e('1'); //传入正常产品和分支产品。
$condition = $storyModel->buildProductsCondition('41');
r($condition == "`product` = '41'")  && p() && e('1');            //传入分支产品。
$condition = $storyModel->buildProductsCondition('41', '1');
r($condition == "((`product` = '41' AND `branch` = '1')) ")  && p() && e('1'); //传入分成产品和分支。
$condition = $storyModel->buildProductsCondition('1,2,41', '1');
r($condition == "((`product` = '41' AND `branch` = '1') OR `product` IN ('1','2')) ") && p() && e('1'); //传入正常产品和分支产品和分支。
