#!/usr/bin/env php
<?php

/**

title=productModel->updateOrder();
timeout=0
cid=17530

- 不传入任何数据。 @1|2|3|4|5
- 传入全部排序后的 ID 列表。 @1|3|4|5|2
- 传入部分排序后的 ID 列表。 @3|1|4|5|2
- 传入部分排序后的 ID 列表。 @5|4|3|2|1
- 传入排序后的 ID 列表中，包含无效的数据。 @1|4|3|2|5

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zenData('product')->loadYaml('product')->gen(5);

$product = new productTest('admin');

r($product->updateOrderTest(array()))                 && p() && e('1|2|3|4|5'); // 不传入任何数据。
r($product->updateOrderTest(array(1, 3, 4, 5, 2)))    && p() && e('1|3|4|5|2'); // 传入全部排序后的 ID 列表。
r($product->updateOrderTest(array(3, 1, 4)))          && p() && e('3|1|4|5|2'); // 传入部分排序后的 ID 列表。
r($product->updateOrderTest(array(5, 4, 3, 2, 1)))    && p() && e('5|4|3|2|1'); // 传入部分排序后的 ID 列表。
r($product->updateOrderTest(array(1, 'program1', 5))) && p() && e('1|4|3|2|5'); // 传入排序后的 ID 列表中，包含无效的数据。
