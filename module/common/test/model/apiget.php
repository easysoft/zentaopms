#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiGet();
timeout=0
cid=15641

- 执行commonTest模块的apiGetTest方法，参数是''  @Empty URL
- 执行commonTest模块的apiGetTest方法，参数是'invalid-url' 属性code @600
- 执行commonTest模块的apiGetTest方法，参数是'http://api.success.com/test', array 属性code @200
- 执行commonTest模块的apiGetTest方法，参数是'http://api.error.com/test' 属性code @400
- 执行commonTest模块的apiGetTest方法，参数是'http://api.example.com/test' 属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

su('admin');

$commonTest = new commonTest();

r($commonTest->apiGetTest('')) && p() && e('Empty URL');
r($commonTest->apiGetTest('invalid-url')) && p('code') && e('600');
r($commonTest->apiGetTest('http://api.success.com/test', array('param' => 'value'))) && p('code') && e('200');
r($commonTest->apiGetTest('http://api.error.com/test')) && p('code') && e('400');
r($commonTest->apiGetTest('http://api.example.com/test')) && p('code') && e('600');