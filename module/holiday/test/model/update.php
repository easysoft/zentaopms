#!/usr/bin/env php
<?php
/**

title=测试 holidayModel->update();
cid=1

- 测试修改类型 @true
- 测试修改名称 @true
- 测试修改描述 @true
- 测试将必填项名称置空第name条的0属性 @『名称』不能为空。
- 测试将必填项开始日期置空第begin条的0属性 @『开始日期』不能为空。
- 测试将必填项结束日期置空第end条的0属性 @『结束日期』不能为空。
- 测试输入大于开始日期的结束日期第end条的0属性 @『结束日期』应当不小于『2022-05-08』。

*/
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/holiday.unittest.class.php';

zenData('holiday')->gen(10);
zenData('task')->gen(0);
zenData('user')->gen(1);

su('admin');

$holidayIDList = array(1, 2);

$holiday = new holidayTest();

$updateType      = array('type'  => 'working');
$updateName      = array('name'  => '修改holiday的名字');
$updateDesc      = array('desc'  => '一些描述');
$noName          = array('name'  => '');
$noBegin         = array('begin' => '');
$noEnd           = array('end'   => '');
$endltBegin      = array('begin' => '2022-05-08', 'end' => '2022-05-01');

r($holiday->updateTest($holidayIDList[0], $updateType))   && p()          && e('true');                                   // 测试修改类型
r($holiday->updateTest($holidayIDList[0], $updateName))   && p()          && e('true');                                   // 测试修改名称
r($holiday->updateTest($holidayIDList[0], $updateDesc))   && p()          && e('true');                                   // 测试修改描述
r($holiday->updateTest($holidayIDList[0], $noName))       && p('name:0')  && e('『名称』不能为空。');                     // 测试将必填项名称置空
r($holiday->updateTest($holidayIDList[0], $noBegin))      && p('begin:0') && e('『开始日期』不能为空。');                 // 测试将必填项开始日期置空
r($holiday->updateTest($holidayIDList[0], $noEnd))        && p('end:0')   && e('『结束日期』不能为空。');                 // 测试将必填项结束日期置空
r($holiday->updateTest($holidayIDList[0], $endltBegin))   && p('end:0')   && e('『结束日期』应当不小于『2022-05-08』。'); // 测试输入大于开始日期的结束日期
