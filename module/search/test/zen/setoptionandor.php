#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptionAndOr();
timeout=0
cid=18349

- 执行$result1 @1
- 执行$result2 @2
- 执行$result3第0条的value属性 @and
- 执行$result4第0条的title属性 @并且
- 执行$result5第1条的value属性 @or
- 执行$result6第1条的title属性 @或者

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$searchTest = new searchZenTest();

$result1 = $searchTest->setOptionAndOrTest();
$result2 = $searchTest->setOptionAndOrTest();
$result3 = $searchTest->setOptionAndOrTest();
$result4 = $searchTest->setOptionAndOrTest();
$result5 = $searchTest->setOptionAndOrTest();
$result6 = $searchTest->setOptionAndOrTest();

r(is_array($result1)) && p() && e('1');
r(count($result2)) && p() && e('2');
r($result3) && p('0:value') && e('and');
r($result4) && p('0:title') && e('并且');
r($result5) && p('1:value') && e('or');
r($result6) && p('1:title') && e('或者');