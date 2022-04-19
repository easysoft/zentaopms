#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

su('admin');

/**

title=测试productModel->isClickable();
cid=1
pid=1

status为normal,action为close >> true
status为close,action为close >> false
status为normal,action为start >> true
status为close,action为start >> true

*/

$product = new productTest('admin');

$t_status = array('2', '75', 'close', 'start');

r($product->testIsClickable($t_status[0], $t_status[2])) && p() && e('true');  // status为normal,action为close
r($product->testIsClickable($t_status[1], $t_status[2])) && p() && e('false'); // status为close,action为close
r($product->testIsClickable($t_status[0], $t_status[3])) && p() && e('true');  // status为normal,action为start
r($product->testIsClickable($t_status[1], $t_status[3])) && p() && e('true');  // status为close,action为start