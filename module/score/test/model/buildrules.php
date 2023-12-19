#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->buildRules();
cid=1

- 获取积分规则列表数量 @38
- 获取第一条积分规则
 - 第0条的module属性 @文档
 - 第0条的method属性 @创建文档
 - 第0条的times属性 @不限制
 - 第0条的hour属性 @不限制
 - 第0条的score属性 @5
- 获取第二条积分规则
 - 第1条的module属性 @Bug
 - 第1条的method属性 @创建Bug
 - 第1条的times属性 @不限制
 - 第1条的hour属性 @不限制
 - 第1条的score属性 @1
- 获取第三条积分规则
 - 第2条的module属性 @Bug
 - 第2条的method属性 @解决Bug
 - 第2条的times属性 @不限制
 - 第2条的hour属性 @不限制
 - 第2条的score属性 @1
- 获取第四条积分规则
 - 第3条的module属性 @Bug
 - 第3条的method属性 @确认Bug
 - 第3条的times属性 @不限制
 - 第3条的hour属性 @不限制
 - 第3条的score属性 @1
- 获取第五条积分规则
 - 第4条的module属性 @Bug
 - 第4条的method属性 @保存模板
 - 第4条的times属性 @1
 - 第4条的hour属性 @不限制
 - 第4条的score属性 @20
- 获取第六条积分规则
 - 第5条的module属性 @Bug
 - 第5条的method属性 @从用例创建
 - 第5条的times属性 @不限制
 - 第5条的hour属性 @不限制
 - 第5条的score属性 @1
- 获取第七条积分规则
 - 第6条的module属性 @待办
 - 第6条的method属性 @创建待办
 - 第6条的times属性 @5
 - 第6条的hour属性 @24
 - 第6条的score属性 @1
- 获取第八条积分规则
 - 第7条的module属性 @任务
 - 第7条的method属性 @关闭任务
 - 第7条的times属性 @不限制
 - 第7条的hour属性 @不限制
 - 第7条的score属性 @1
- 获取第九条积分规则
 - 第8条的module属性 @任务
 - 第8条的method属性 @创建任务
 - 第8条的times属性 @不限制
 - 第8条的hour属性 @不限制
 - 第8条的score属性 @1
- 获取第十条积分规则
 - 第9条的module属性 @任务
 - 第9条的method属性 @完成任务
 - 第9条的times属性 @不限制
 - 第9条的hour属性 @不限制
 - 第9条的score属性 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

zdTable('user')->gen(5);
$scoreTester = new scoreTest();
$ruleList    = $scoreTester->buildRulesTest();

r(count($ruleList)) && p() && e('38'); // 获取积分规则列表数量

r($ruleList) && p('0:module,method,times,hour,score') && e('文档,创建文档,不限制,不限制,5');  // 获取第一条积分规则
r($ruleList) && p('1:module,method,times,hour,score') && e('Bug,创建Bug,不限制,不限制,1');    // 获取第二条积分规则
r($ruleList) && p('2:module,method,times,hour,score') && e('Bug,解决Bug,不限制,不限制,1');    // 获取第三条积分规则
r($ruleList) && p('3:module,method,times,hour,score') && e('Bug,确认Bug,不限制,不限制,1');    // 获取第四条积分规则
r($ruleList) && p('4:module,method,times,hour,score') && e('Bug,保存模板,1,不限制,20');       // 获取第五条积分规则
r($ruleList) && p('5:module,method,times,hour,score') && e('Bug,从用例创建,不限制,不限制,1'); // 获取第六条积分规则
r($ruleList) && p('6:module,method,times,hour,score') && e('待办,创建待办,5,24,1');           // 获取第七条积分规则
r($ruleList) && p('7:module,method,times,hour,score') && e('任务,关闭任务,不限制,不限制,1');  // 获取第八条积分规则
r($ruleList) && p('8:module,method,times,hour,score') && e('任务,创建任务,不限制,不限制,1');  // 获取第九条积分规则
r($ruleList) && p('9:module,method,times,hour,score') && e('任务,完成任务,不限制,不限制,1');  // 获取第十条积分规则
