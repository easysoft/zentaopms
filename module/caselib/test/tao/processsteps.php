#!/usr/bin/env php
<?php

/**

title=测试 caselibTao::processSteps();
timeout=0
cid=15540

- 执行caselibTest模块的processStepsTest方法，参数是array 
 - 第0条的desc属性 @登录系统
 - 第1条的desc属性 @创建项目
 - 第2条的desc属性 @验证结果
- 执行caselibTest模块的processStepsTest方法，参数是array  @1
- 执行caselibTest模块的processStepsTest方法，参数是array  @0
- 执行caselibTest模块的processStepsTest方法，参数是array 第0条的desc属性 @包含&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;的描述
- 执行caselibTest模块的processStepsTest方法，参数是array 
 - 第0条的type属性 @item
 - 第0条的desc属性 @单个测试步骤
 - 第0条的expect属性 @单个期望结果

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$caselibTest = new caselibTaoTest();

r($caselibTest->processStepsTest(array('登录系统', '创建项目', '验证结果'), array('step', 'step', 'step'), array('成功登录', '项目创建成功', '验证通过'))) && p('0:desc;1:desc;2:desc') && e('登录系统;创建项目;验证结果');
r(count($caselibTest->processStepsTest(array('', '有效步骤', ''), array('step', 'step', 'step'), array('', '期望结果', '')))) && p() && e('1');
r(count($caselibTest->processStepsTest(array('', '', ''), array('step', 'step', 'step'), array('', '', '')))) && p() && e('0');
r($caselibTest->processStepsTest(array('包含<script>alert("XSS")</script>的描述'), array('step'), array('包含&amp;特殊字符的期望'))) && p('0:desc') && e('包含&lt;script&gt;alert(&quot;XSS&quot;)&lt;/script&gt;的描述');
r($caselibTest->processStepsTest(array('单个测试步骤'), array('item'), array('单个期望结果'))) && p('0:type;0:desc;0:expect') && e('item;单个测试步骤;单个期望结果');