#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptionOperators();
timeout=0
cid=18351

- 执行$result1 @1
- 执行$result2 @10
- 执行$result3第0条的value属性 @=
- 执行$result4第0条的title属性 @=
- 执行$result5第1条的value属性 @!=
- 执行$result6第1条的title属性 @!=
- 执行$result7第6条的value属性 @include
- 执行$result8第6条的title属性 @包含
- 执行$result9第7条的value属性 @between
- 执行$result10第7条的title属性 @介于

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$searchTest = new searchZenTest();

$result1 = $searchTest->setOptionOperatorsTest();
$result2 = $searchTest->setOptionOperatorsTest();
$result3 = $searchTest->setOptionOperatorsTest();
$result4 = $searchTest->setOptionOperatorsTest();
$result5 = $searchTest->setOptionOperatorsTest();
$result6 = $searchTest->setOptionOperatorsTest();
$result7 = $searchTest->setOptionOperatorsTest();
$result8 = $searchTest->setOptionOperatorsTest();
$result9 = $searchTest->setOptionOperatorsTest();
$result10 = $searchTest->setOptionOperatorsTest();

r(is_array($result1)) && p() && e('1');
r(count($result2)) && p() && e('10');
r($result3) && p('0:value') && e('=');
r($result4) && p('0:title') && e('=');
r($result5) && p('1:value') && e('!=');
r($result6) && p('1:title') && e('!=');
r($result7) && p('6:value') && e('include');
r($result8) && p('6:title') && e('包含');
r($result9) && p('7:value') && e('between');
r($result10) && p('7:title') && e('介于');