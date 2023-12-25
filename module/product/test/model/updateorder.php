#!/usr/bin/env php
<?php

/**

title=productModel->updateOrder();
cid=0

- 不传入任何数据。 @1|2|3|4|5
- 传入全部排序后的 ID 列表。 @1|3|4|5|2
- 传入部分排序后的 ID 列表。 @3|1|4|5|2
- 传入排序后的 ID 列表中，包含无效的数据。 @3|4|1|5|2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

zdTable('product')->config('product')->gen(5);

$product = new productTest('admin');

r($product->updateOrderTest(array()))                 && p() && e('1|2|3|4|5'); // 不传入任何数据。
r($product->updateOrderTest(array(1, 3, 4, 5, 2)))    && p() && e('1|3|4|5|2'); // 传入全部排序后的 ID 列表。
r($product->updateOrderTest(array(3, 1, 4)))          && p() && e('3|1|4|5|2'); // 传入部分排序后的 ID 列表。
r($product->updateOrderTest(array(4, 'program1', 1))) && p() && e('3|4|1|5|2'); // 传入排序后的 ID 列表中，包含无效的数据。
