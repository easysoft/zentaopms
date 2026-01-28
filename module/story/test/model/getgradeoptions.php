#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getGradeOptions();
timeout=0
cid=18536

- 步骤1：空故事对象，返回story类型启用的分级选项
 - 属性1 @SR1
 - 属性2 @SR2
- 步骤2：story类型grade=1，返回比1更高的分级选项属性2 @SR2
- 步骤3：类型不匹配（story传requirement），返回requirement类型全部启用分级
 - 属性1 @UR1
 - 属性2 @UR2
- 步骤4：epic类型的全部启用分级选项
 - 属性1 @BR1
 - 属性2 @BR2
- 步骤5：grade=3，包含附加分级1,2的选项
 - 属性1 @SR1
 - 属性2 @SR2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zendata('storygrade')->loadYaml('storygrade_getgradeoptions', false, 2)->gen(9);

$storygradeTable = zendata('storygrade');
$storygradeTable->type->range('story{4},requirement{3},epic{2}');
$storygradeTable->grade->range('1,2,3,4,1,2,3,1,2');
$storygradeTable->name->range('SR1,SR2,SR3,子,UR1,UR2,UR3,BR1,BR2');
$storygradeTable->status->range('enable{9}');
$storygradeTable->gen(9);

$storyTable = zendata('story');
$storyTable->id->range('1-3');
$storyTable->type->range('story,requirement,epic');
$storyTable->grade->range('1,2,3');
$storyTable->gen(3);

su('admin');

$storyTest = new storyModelTest();

$story1 = new stdClass();
$story1->type = 'story';
$story1->grade = 1;

$story2 = new stdClass();
$story2->type = 'requirement';
$story2->grade = 2;

$story3 = new stdClass();
$story3->type = 'story';
$story3->grade = 3;

r($storyTest->getGradeOptionsTest(false, 'story')) && p('1,2') && e('SR1,SR2'); // 步骤1：空故事对象，返回story类型启用的分级选项
r($storyTest->getGradeOptionsTest($story1, 'story')) && p('2') && e('SR2'); // 步骤2：story类型grade=1，返回比1更高的分级选项
r($storyTest->getGradeOptionsTest($story1, 'requirement')) && p('1,2') && e('UR1,UR2'); // 步骤3：类型不匹配（story传requirement），返回requirement类型全部启用分级
r($storyTest->getGradeOptionsTest(false, 'epic')) && p('1,2') && e('BR1,BR2'); // 步骤4：epic类型的全部启用分级选项
r($storyTest->getGradeOptionsTest($story3, 'story', array(1,2))) && p('1,2') && e('SR1,SR2'); // 步骤5：grade=3，包含附加分级1,2的选项