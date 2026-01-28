#!/usr/bin/env php
<?php

/**

title=测试 holidayModel::getHolidayByAPI();
timeout=0
cid=16742

- 执行holiday模块的getHolidayByAPITest方法，参数是'this year'  @11
- 执行holiday模块的getHolidayByAPITest方法，参数是'last year'  @12
- 执行holiday模块的getHolidayByAPITest方法，参数是'next year'  @14
- 执行holiday模块的getHolidayByAPITest方法，参数是''  @2
- 执行holiday模块的getHolidayByAPITest方法，参数是'invalid'  @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('holiday')->gen(50);
zenData('user')->gen(1);

su('admin');

$holiday = new holidayModelTest();

r($holiday->getHolidayByAPITest('this year')) && p() && e('11');
r($holiday->getHolidayByAPITest('last year')) && p() && e('12');
r($holiday->getHolidayByAPITest('next year')) && p() && e('14');
r($holiday->getHolidayByAPITest('')) && p() && e('2');
r($holiday->getHolidayByAPITest('invalid')) && p() && e('2');