#!/usr/bin/env php
<?php
/**

title=测试 holidayModel->batchCreate();
cid=1

- 测试创建holiday
 - 属性id @1
 - 属性name @测试创建holiday
 - 属性type @holiday
- 测试创建working
 - 属性id @2
 - 属性name @测试创建working
 - 属性type @working
- 测试创建holiday 和 working
 - 属性id @4
 - 属性name @测试创建working
 - 属性type @working
- 测试不传入必填项开始日期第begin条的0属性 @『开始日期』不能为空。
- 测试不传入必填项结束日期第end条的0属性 @『结束日期』不能为空。
- 测试不传入必填项名称第name条的0属性 @『名称』不能为空。
- 测试传入小于开始日期的结束日期第end条的0属性 @『结束日期』应当不小于『2022-01-10』。
- 测试不传入必填项开始日期和结束日期第end条的0属性 @『结束日期』不能为空。

*/
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(0);
zdTable('user')->gen(1);

su('admin');

$holiday = new holidayTest();

$createHoliday   = array('name' => '测试创建holiday', 'type' => 'holiday');
$createWorking   = array('name' => '测试创建working', 'type' => 'working');
$createNoBegin   = array('name' => '不传入开始日期',  'type' => 'holiday', 'begin' => '');
$createNoEnd     = array('name' => '不传入结束日期',  'type' => 'holiday', 'end' => '' );
$createNoName    = array('name' => '',                'type' => 'holiday');
$createErrorDate = array('begin' => '2022-01-10',     'end' => '2022-01-01');

$batchHoliday1 = array($createHoliday);
$batchHoliday2 = array($createWorking);
$batchHoliday3 = array($createHoliday, $createWorking);
$batchHoliday4 = array($createNoBegin);
$batchHoliday5 = array($createNoEnd);
$batchHoliday6 = array($createNoName);
$batchHoliday7 = array($createErrorDate);
$batchHoliday8 = array($createNoBegin, $createNoEnd);

r($holiday->batchCreateTest($batchHoliday1))   && p('id,name,type') && e('1,测试创建holiday,holiday');              // 测试创建holiday
r($holiday->batchCreateTest($batchHoliday2))   && p('id,name,type') && e('2,测试创建working,working');              // 测试创建working
r($holiday->batchCreateTest($batchHoliday3))   && p('id,name,type') && e('4,测试创建working,working');              // 测试创建holiday 和 working
r($holiday->batchCreateTest($batchHoliday4))   && p('begin:0')      && e('『开始日期』不能为空。');                 // 测试不传入必填项开始日期
r($holiday->batchCreateTest($batchHoliday5))   && p('end:0')        && e('『结束日期』不能为空。');                 // 测试不传入必填项结束日期
r($holiday->batchCreateTest($batchHoliday6))   && p('name:0')       && e('『名称』不能为空。');                     // 测试不传入必填项名称
r($holiday->batchCreateTest($batchHoliday7))   && p('end:0')        && e('『结束日期』应当不小于『2022-01-10』。'); // 测试传入小于开始日期的结束日期
r($holiday->batchCreateTest($batchHoliday8))   && p('end:0')        && e('『结束日期』不能为空。');                 // 测试不传入必填项开始日期和结束日期
