#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/holiday.class.php';

zdTable('holiday')->gen(0);
zdTable('project')->gen(10);
zdTable('user')->gen(1);

su('admin');

/**

title=测试 holidayModel->create();
cid=1
pid=1

测试创建holiday >> 1,测试创建holiday,holiday
测试创建working >> 2,测试创建working,working
测试不传入必填项开始日期 >> 『开始日期』不能为空。
测试不传入必填项结束日期 >> 『结束日期』不能为空。
测试不传入必填项名称 >> 『名称』不能为空。
测试传入小于开始日期的结束日期 >> 『结束日期』应当不小于『2022-01-10』。

*/

$holiday = new holidayTest();

$createHoliday   = array('name' => '测试创建holiday', 'type' => 'holiday');
$createWorking   = array('name' => '测试创建working', 'type' => 'working');
$createNoBegin   = array('name' => '不传入开始日期',  'type' => 'holiday', 'begin' => '');
$createNoEnd     = array('name' => '不传入结束日期',  'type' => 'holiday', 'end' => '' );
$createNoName    = array('name' => '',                'type' => 'holiday');
$createErrorDate = array('begin' => '2022-01-10',     'end' => '2022-01-01');

r($holiday->createTest($createHoliday))   && p('id,name,type') && e('1,测试创建holiday,holiday');              // 测试创建holiday
r($holiday->createTest($createWorking))   && p('id,name,type') && e('2,测试创建working,working');              // 测试创建working
r($holiday->createTest($createNoBegin))   && p('begin:0')      && e('『开始日期』不能为空。');                 // 测试不传入必填项开始日期
r($holiday->createTest($createNoEnd))     && p('end:0')        && e('『结束日期』不能为空。');                 // 测试不传入必填项结束日期
r($holiday->createTest($createNoName))    && p('name:0')       && e('『名称』不能为空。');                     // 测试不传入必填项名称
r($holiday->createTest($createErrorDate)) && p('end:0')        && e('『结束日期』应当不小于『2022-01-10』。'); // 测试传入小于开始日期的结束日期
