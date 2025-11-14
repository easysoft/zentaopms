#!/usr/bin/env php
<?php

/**

title=测试 commonModel::apiError();
timeout=0
cid=15640

- 执行commonTest模块的apiErrorTest方法，参数是null 属性code @600
- 执行commonTest模块的apiErrorTest方法，参数是$validError
 - 属性code @400
 - 属性message @Bad Request
- 执行commonTest模块的apiErrorTest方法，参数是$zeroCodeError 属性code @600
- 执行commonTest模块的apiErrorTest方法，参数是$completeError
 - 属性code @404
 - 属性message @Not Found
- 执行commonTest模块的apiErrorTest方法，参数是null 属性message @服务器错误
- 执行commonTest模块的apiErrorTest方法，参数是$negativeCodeError
 - 属性code @-1
 - 属性message @Invalid

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

$validError = new stdclass;
$validError->code = 400;
$validError->message = 'Bad Request';

$zeroCodeError = new stdclass;
$zeroCodeError->code = 0;
$zeroCodeError->message = 'No Error';

$completeError = new stdclass;
$completeError->code = 404;
$completeError->message = 'Not Found';

$negativeCodeError = new stdclass;
$negativeCodeError->code = -1;
$negativeCodeError->message = 'Invalid';

r($commonTest->apiErrorTest(null)) && p('code') && e('600');
r($commonTest->apiErrorTest($validError)) && p('code,message') && e('400,Bad Request');
r($commonTest->apiErrorTest($zeroCodeError)) && p('code') && e('600');
r($commonTest->apiErrorTest($completeError)) && p('code,message') && e('404,Not Found');
r($commonTest->apiErrorTest(null)) && p('message') && e('服务器错误');
r($commonTest->apiErrorTest($negativeCodeError)) && p('code,message') && e('-1,Invalid');