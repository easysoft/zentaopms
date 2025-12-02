#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getMiniProgramFields();
timeout=0
cid=15039

- 执行aiTest模块的getMiniProgramFieldsTest方法，参数是'1'  @5
- 执行aiTest模块的getMiniProgramFieldsTest方法，参数是'2'  @5
- 执行aiTest模块的getMiniProgramFieldsTest方法，参数是'999'  @2
- 执行aiTest模块的getMiniProgramFieldsTest方法，参数是'3'  @5
- 执行aiTest模块的getMiniProgramFieldsTest方法，参数是'0'  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_miniprogramfield');
$table->id->range('1-20');
$table->appID->range('1{5},2{5},3{5},999{2},0{3}');
$table->name->range('教育背景,职位信息,工作经验,掌握技能,职业目标,兴趣领域,规划时长,补充信息,身份描述,工作内容描述,技能要求,项目经验,学历要求,工作年限,薪资期望,联系方式,个人简介,其他信息');
$table->type->range('text,textarea,radio,checkbox');
$table->placeholder->range('学历/专业,行业领域/职位描述,简要概述工作成果,关键技能、熟练程度,请输入你的职业目标,请输入更多你感兴趣的领域,职位/角色/职责,请输入工作内容,请输入更多补充信息,请选择,请填写,[]{2}');
$table->options->range('1年,3-5年,10年,是,否,初级,中级,高级,必须,优先,可选,[]{8}');
$table->required->range('1,0');
$table->gen(20);

su('admin');

$aiTest = new aiTest();

r(count($aiTest->getMiniProgramFieldsTest('1'))) && p() && e('5');
r(count($aiTest->getMiniProgramFieldsTest('2'))) && p() && e('5');
r(count($aiTest->getMiniProgramFieldsTest('999'))) && p() && e('2');
r(count($aiTest->getMiniProgramFieldsTest('3'))) && p() && e('5');
r(count($aiTest->getMiniProgramFieldsTest('0'))) && p() && e('3');