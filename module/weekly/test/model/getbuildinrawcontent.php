#!/usr/bin/env php
<?php
/**

title=测试 weeklyModel->getBuildinRawContent();
timeout=0
cid=19719

- 检查模板标题属性title @项目周报模板
- 获取holder数据
 - 属性name @weekly_term
 - 属性text @报告周期
 - 属性hint @筛选条件“日期范围”介于本周
- 检查区块数量 @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
su('admin');

$weeklyTester = new weeklyModelTest();
$content      = $weeklyTester->getBuildinRawContentTest();

r($content['meta'])                                                                                      && p('title')          && e('项目周报模板');                                        // 检查模板标题
r($content['blocks']['children'][0]['children'][0]['props']['text']['delta'][0]['attributes']['holder']) && p('name,text,hint') && e('weekly_term,报告周期,筛选条件：“日期范围”介于本周'); // 获取holder数据
r(count($content['blocks']))                                                                             && p()                 && e('6');                                                   // 检查区块数量
