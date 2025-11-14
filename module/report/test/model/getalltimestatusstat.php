#!/usr/bin/env php
<?php

/**

title=测试 reportModel::getAllTimeStatusStat();
timeout=0
cid=18162

- 测试步骤1：正常情况下获取story状态统计属性story @changing:15;active:15;
- 测试步骤2：正常情况下获取task状态统计属性task @wait:10;doing:10;done:10;pause:10;cancel:10;closed:10;
- 测试步骤3：正常情况下获取bug状态统计属性bug @active:40;resolved:10;
- 测试步骤4：测试type='story'过滤功能属性story @changing:8;active:7;
- 测试步骤5：测试已删除数据过滤功能属性story @changing:8;active:7;

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

// 生成测试数据
zenData('task')->gen(60);
zenData('bug')->gen(60);
zenData('story')->gen(60);
zenData('user')->gen(1);

su('admin');

$report = new reportTest();

r($report->getAllTimeStatusStatTest()) && p('story') && e('changing:15;active:15;');                                 // 测试步骤1：正常情况下获取story状态统计
r($report->getAllTimeStatusStatTest()) && p('task')  && e('wait:10;doing:10;done:10;pause:10;cancel:10;closed:10;'); // 测试步骤2：正常情况下获取task状态统计
r($report->getAllTimeStatusStatTest()) && p('bug')   && e('active:40;resolved:10;');                                 // 测试步骤3：正常情况下获取bug状态统计

// 修改部分story的type为requirement，测试type过滤
global $tester;
$tester->dao->update(TABLE_STORY)->set('type')->eq('requirement')->where('id')->le(30)->exec();

r($report->getAllTimeStatusStatTest()) && p('story') && e('changing:8;active:7;');                                  // 测试步骤4：测试type='story'过滤功能

// 将部分数据设为已删除状态
$tester->dao->update(TABLE_STORY)->set('deleted')->eq('1')->where('id')->le(15)->exec();
$tester->dao->update(TABLE_TASK)->set('deleted')->eq('1')->where('id')->le(20)->exec();
$tester->dao->update(TABLE_BUG)->set('deleted')->eq('1')->where('id')->le(20)->exec();

r($report->getAllTimeStatusStatTest()) && p('story') && e('changing:8;active:7;');                                  // 测试步骤5：测试已删除数据过滤功能