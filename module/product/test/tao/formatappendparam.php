#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

/**

title=productTao->formatAppendParam();
cid=1
pid=1


*/

global $tester;
$productTao = $tester->loadModel('product');

r($productTao->formatAppendParam('')) && p() && e('0');
r($productTao->formatAppendParam(array())) && p() && e('0');
r($productTao->formatAppendParam('1')) && p() && e('1');
r($productTao->formatAppendParam('1,')) && p() && e('1');
r($productTao->formatAppendParam('1,1')) && p() && e('1');
r($productTao->formatAppendParam('1,2')) && p() && e('1,2');
r($productTao->formatAppendParam('1,a')) && p() && e('1');
r($productTao->formatAppendParam(array('1'))) && p() && e('1');
r($productTao->formatAppendParam(array('1', '1'))) && p() && e('1');
r($productTao->formatAppendParam(array('1', '2'))) && p() && e('1,2');
r($productTao->formatAppendParam(array('1', 'a'))) && p() && e('1');



