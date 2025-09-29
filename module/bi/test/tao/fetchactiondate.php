#!/usr/bin/env php
<?php

/**

title=测试 biTao::fetchActionDate();
timeout=0
cid=0

- 测试fetchActionDate方法返回对象类型 >> 返回object类型
- 测试有效数据时获取最小日期 >> 返回最小日期值
- 测试有效数据时获取最大日期 >> 返回最大日期值
- 测试过滤2009年前数据的功能 >> 正确过滤旧数据
- 测试空表时返回null >> 返回null对象

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

// 准备测试数据：包含有效的日期数据，模拟真实的action记录
$table = zenData('action');
$table->id->range('1-10');
$table->objectType->range('task{2},bug{2},story{2},project{2},product{2}');
$table->objectID->range('1-10');
$table->actor->range('admin,user1,user2,test');
$table->action->range('opened,closed,edited,created,updated');
$table->date->range('2010-01-01 08:00:00,2015-06-15 14:30:00,2020-12-31 23:59:59,2023-03-20 09:15:00,2024-08-10 16:45:00');
$table->comment->range('创建了任务,关闭了缺陷,编辑了需求,测试评论,系统自动更新');
$table->gen(10);

su('admin');

$biTest = new biTest();

r($biTest->fetchActionDateObjectTest()) && p() && e('object');                                            // 测试fetchActionDate方法返回对象类型
r($biTest->fetchActionDateTest()) && p('minDate') && e('2010-01-01 08:00:00');                           // 测试有效数据时获取最小日期
r($biTest->fetchActionDateTest()) && p('maxDate') && e('2024-08-10 16:45:00');                           // 测试有效数据时获取最大日期

// 测试过滤2009年前数据的功能
$table2 = zenData('action');
$table2->id->range('11-15');
$table2->objectType->range('task');
$table2->objectID->range('11-15');
$table2->actor->range('admin');
$table2->action->range('opened');
$table2->date->range('2008-12-31 23:59:59,2009-01-01 00:00:00,2010-01-01 00:00:00');
$table2->comment->range('旧数据测试');
$table2->gen(3);

r($biTest->fetchActionDateTest()) && p('minDate') && e('2009-01-01 00:00:00');                           // 测试过滤2009年前数据的功能

// 清空表数据测试空表情况
zenData('action')->gen(0);
r($biTest->fetchActionDateTest()) && p('minDate') && e('~~');                                             // 测试空表时返回null