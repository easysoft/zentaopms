#!/usr/bin/env php
<?php

/**

title=productTao->formatAppendParam();
cid=0

- 执行productTao模块的formatAppendParam方法，参数是''  @0
- 执行productTao模块的formatAppendParam方法，参数是array  @0
- 执行productTao模块的formatAppendParam方法，参数是'1'  @1
- 执行productTao模块的formatAppendParam方法，参数是'1, '  @1
- 执行productTao模块的formatAppendParam方法，参数是'1, 1'  @1
- 执行productTao模块的formatAppendParam方法，参数是'1, 2'  @1,2

- 执行productTao模块的formatAppendParam方法，参数是'1, a'  @1
- 执行productTao模块的formatAppendParam方法，参数是array  @1
- 执行productTao模块的formatAppendParam方法，参数是array  @1
- 执行productTao模块的formatAppendParam方法，参数是array  @1,2

- 执行productTao模块的formatAppendParam方法，参数是array  @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

global $tester;
$productTao = $tester->loadModel('product');

r($productTao->formatAppendParam(''))              && p() && e('0');
r($productTao->formatAppendParam(array()))         && p() && e('0');
r($productTao->formatAppendParam('1'))             && p() && e('1');
r($productTao->formatAppendParam('1,'))            && p() && e('1');
r($productTao->formatAppendParam('1,1'))           && p() && e('1');
r($productTao->formatAppendParam('1,2'))           && p() && e('1,2');
r($productTao->formatAppendParam('1,a'))           && p() && e('1');
r($productTao->formatAppendParam(array('1')))      && p() && e('1');
r($productTao->formatAppendParam(array('1', '1'))) && p() && e('1');
r($productTao->formatAppendParam(array('1', '2'))) && p() && e('1,2');
r($productTao->formatAppendParam(array('1', 'a'))) && p() && e('1');
