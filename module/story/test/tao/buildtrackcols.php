#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildTrackCols();
timeout=0
cid=18606

- 执行$epicCols @16
- 执行$epicCols[0]
 - 属性name @epic
 - 属性title @业务需求
 - 属性parent @-1
- 执行$epicCols[1]
 - 属性name @requirement
 - 属性title @用户需求
 - 属性parent @-1
- 执行$epicCols[2]
 - 属性name @story
 - 属性title @研发需求
 - 属性parent @-1
- 执行$epicCols[10]
 - 属性name @story_1
 - 属性title @SR1
 - 属性parent @story
 - 属性parentName @story
- 执行$epicCols[13]
 - 属性name @requirement_2
 - 属性title @UR2
 - 属性parent @requirement
 - 属性parentName @requirement
- 执行$epicCols[15]
 - 属性name @epic_2
 - 属性title @BR2
 - 属性parent @epic
 - 属性parentName @epic
- 执行$requirementCols @13
- 执行$requirementCols[0]
 - 属性name @requirement
 - 属性title @用户需求
 - 属性parent @-1
- 执行$requirementCols[1]
 - 属性name @story
 - 属性title @研发需求
 - 属性parent @-1
- 执行$requirementCols[10]
 - 属性name @story_2
 - 属性title @SR2
 - 属性parent @story
 - 属性parentName @story
- 执行$requirementCols[12]
 - 属性name @requirement_2
 - 属性title @UR2
 - 属性parent @requirement
 - 属性parentName @requirement
- 执行$storyCols @10
- 执行$storyCols[0]
 - 属性name @story
 - 属性title @研发需求
 - 属性parent @-1
- 执行$storyCols[9]
 - 属性name @story_2
 - 属性title @SR2
 - 属性parent @story
 - 属性parentName @story
- 执行$epicCols[0]
 - 属性name @epic
 - 属性title @业务需求
 - 属性parent @-1
- 执行$epicCols[1]
 - 属性name @requirement
 - 属性title @用户需求
 - 属性parent @-1
- 执行$epicCols[2]
 - 属性name @story
 - 属性title @研发需求
 - 属性parent @-1
- 执行$epicCols[10]
 - 属性name @story_1
 - 属性title @SR1
 - 属性parent @story
 - 属性parentName @story
- 执行$epicCols[13]
 - 属性name @requirement_2
 - 属性title @UR2
 - 属性parent @requirement
 - 属性parentName @requirement

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$storyGrade = zenData('storygrade');
$storyGrade->type->range('epic{2},requirement{2},story{2}');
$storyGrade->grade->range('1,2');
$storyGrade->name->range('BR1,BR2,UR1,UR2,SR1,SR2');
$storyGrade->status->range('enable');
$storyGrade->gen(6);

global $tester;
$tester->loadModel('story');

$tester->story->lang->ERCommon = '业务需求';
$tester->story->lang->URCommon = '用户需求';
$tester->story->lang->SRCommon = '研发需求';

$epicCols        = $tester->story->buildTrackCols('epic');
$requirementCols = $tester->story->buildTrackCols('requirement');
$storyCols       = $tester->story->buildTrackCols('story');

r(count($epicCols)) && p() && e(16);
r($epicCols[0]) && p('name,title,parent') && e('epic,业务需求,-1');
r($epicCols[1]) && p('name,title,parent') && e('requirement,用户需求,-1');
r($epicCols[2]) && p('name,title,parent') && e('story,研发需求,-1');
r($epicCols[10]) && p('name,title,parent,parentName') && e('story_1,SR1,story,story');
r($epicCols[13]) && p('name,title,parent,parentName') && e('requirement_2,UR2,requirement,requirement');
r($epicCols[15]) && p('name,title,parent,parentName') && e('epic_2,BR2,epic,epic');

r(count($requirementCols)) && p() && e(13);
r($requirementCols[0]) && p('name,title,parent') && e('requirement,用户需求,-1');
r($requirementCols[1]) && p('name,title,parent') && e('story,研发需求,-1');
r($requirementCols[10]) && p('name,title,parent,parentName') && e('story_2,SR2,story,story');
r($requirementCols[12]) && p('name,title,parent,parentName') && e('requirement_2,UR2,requirement,requirement');

r(count($storyCols)) && p() && e(10);
r($storyCols[0]) && p('name,title,parent') && e('story,研发需求,-1');
r($storyCols[9]) && p('name,title,parent,parentName') && e('story_2,SR2,story,story');

$tester->story->dao->delete()->from(TABLE_STORYGRADE)->where('type')->eq('epic')->andWhere('grade')->gt('1')->exec();

$epicCols = $tester->story->buildTrackCols('epic');
r($epicCols[0]) && p('name,title,parent') && e('epic,业务需求,-1');
r($epicCols[1]) && p('name,title,parent') && e('requirement,用户需求,-1');
r($epicCols[2]) && p('name,title,parent') && e('story,研发需求,-1');
r($epicCols[10]) && p('name,title,parent,parentName') && e('story_1,SR1,story,story');
r($epicCols[13]) && p('name,title,parent,parentName') && e('requirement_2,UR2,requirement,requirement');