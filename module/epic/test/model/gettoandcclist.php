#!/usr/bin/env php
<?php

/**

title=测试 epicModel::getToAndCcList();
timeout=0
cid=16255

- 步骤1:有assignedTo和mailto,基本情况 @user1
- 步骤2:只有assignedTo,无mailto @user2
- 步骤3:只有mailto,无assignedTo,单个收件人 @user5
- 步骤4:只有mailto,无assignedTo,多个收件人 @user4
- 步骤5:closed状态的story,添加openedBy到ccList @user3
- 步骤6:无assignedTo无mailto @0
- 步骤7:有assignedTo和mailto,type为epic @user1
- 步骤8:无assignedTo单个mailto,type为story @user5
- 步骤9:有assignedTo无mailto,type为story @user2
- 步骤10:有assignedTo和mailto,状态为draft @user1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('story')->gen(0);
zenData('storyreview')->gen(0);
zenData('team')->gen(0);
zenData('task')->gen(0);
zenData('project')->gen(0);
zenData('projectstory')->gen(0);

su('admin');

$epicTest = new epicModelTest();

$story1 = new stdClass();
$story1->id          = 1;
$story1->assignedTo  = 'user1';
$story1->mailto      = 'user4,user5';
$story1->status      = 'active';
$story1->type        = 'story';
$story1->openedBy    = 'admin';
$story1->version     = 1;

$story2 = new stdClass();
$story2->id          = 2;
$story2->assignedTo  = 'user2';
$story2->mailto      = '';
$story2->status      = 'active';
$story2->type        = 'story';
$story2->openedBy    = 'user1';
$story2->version     = 1;

$story3 = new stdClass();
$story3->id          = 3;
$story3->assignedTo  = '';
$story3->mailto      = 'user5';
$story3->status      = 'active';
$story3->type        = 'story';
$story3->openedBy    = 'admin';
$story3->version     = 1;

$story4 = new stdClass();
$story4->id          = 4;
$story4->assignedTo  = '';
$story4->mailto      = 'user4,user5,reviewer1';
$story4->status      = 'active';
$story4->type        = 'story';
$story4->openedBy    = 'user1';
$story4->version     = 1;

$story5 = new stdClass();
$story5->id          = 5;
$story5->assignedTo  = 'user3';
$story5->mailto      = '';
$story5->status      = 'closed';
$story5->type        = 'story';
$story5->openedBy    = 'admin';
$story5->version     = 1;

$story6 = new stdClass();
$story6->id          = 6;
$story6->assignedTo  = '';
$story6->mailto      = '';
$story6->status      = 'draft';
$story6->type        = 'epic';
$story6->openedBy    = 'user2';
$story6->version     = 1;

$story7 = new stdClass();
$story7->id          = 7;
$story7->assignedTo  = 'user1';
$story7->mailto      = 'user4,user5';
$story7->status      = 'active';
$story7->type        = 'epic';
$story7->openedBy    = 'admin';
$story7->version     = 1;

$story8 = new stdClass();
$story8->id          = 8;
$story8->assignedTo  = '';
$story8->mailto      = 'user5';
$story8->status      = 'active';
$story8->type        = 'story';
$story8->openedBy    = 'admin';
$story8->version     = 1;

$story9 = new stdClass();
$story9->id          = 9;
$story9->assignedTo  = 'user2';
$story9->mailto      = '';
$story9->status      = 'active';
$story9->type        = 'story';
$story9->openedBy    = 'user1';
$story9->version     = 1;

$story10 = new stdClass();
$story10->id          = 10;
$story10->assignedTo  = 'user1';
$story10->mailto      = 'user4,user5';
$story10->status      = 'draft';
$story10->type        = 'story';
$story10->openedBy    = 'admin';
$story10->version     = 1;

r($epicTest->getToAndCcListTest($story1, 'activate')) && p('0') && e('user1'); // 步骤1:有assignedTo和mailto,基本情况
r($epicTest->getToAndCcListTest($story2, 'activate')) && p('0') && e('user2'); // 步骤2:只有assignedTo,无mailto
r($epicTest->getToAndCcListTest($story3, 'activate')) && p('0') && e('user5'); // 步骤3:只有mailto,无assignedTo,单个收件人
r($epicTest->getToAndCcListTest($story4, 'activate')) && p('0') && e('user4'); // 步骤4:只有mailto,无assignedTo,多个收件人
r($epicTest->getToAndCcListTest($story5, 'activate')) && p('0') && e('user3'); // 步骤5:closed状态的story,添加openedBy到ccList
r($epicTest->getToAndCcListTest($story6, 'activate')) && p() && e('0'); // 步骤6:无assignedTo无mailto
r($epicTest->getToAndCcListTest($story7, 'activate')) && p('0') && e('user1'); // 步骤7:有assignedTo和mailto,type为epic
r($epicTest->getToAndCcListTest($story8, 'activate')) && p('0') && e('user5'); // 步骤8:无assignedTo单个mailto,type为story
r($epicTest->getToAndCcListTest($story9, 'activate')) && p('0') && e('user2'); // 步骤9:有assignedTo无mailto,type为story
r($epicTest->getToAndCcListTest($story10, 'activate')) && p('0') && e('user1'); // 步骤10:有assignedTo和mailto,状态为draft