#!/usr/bin/env php
<?php

/**

title=测试 storyModel->buildTrackCol();
cid=18605

- 执行story模块的buildTrackCol方法，参数是'epic', '业务需求'
 - 属性name @epic
 - 属性title @业务需求
 - 属性parent @0
- 执行story模块的buildTrackCol方法，参数是'epic', '业务需求', '-1'
 - 属性name @epic
 - 属性title @业务需求
 - 属性parent @-1
- 执行story模块的buildTrackCol方法，参数是'epic_1', 'BR1', 'epic'
 - 属性name @epic_1
 - 属性title @BR1
 - 属性parent @epic
 - 属性parentName @epic

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

global $tester;
$tester->loadModel('story');

r($tester->story->buildTrackCol('epic', '业务需求'))       && p('name,title,parent')            && e('epic,业务需求,0');
r($tester->story->buildTrackCol('epic', '业务需求', '-1')) && p('name,title,parent')            && e('epic,业务需求,-1');
r($tester->story->buildTrackCol('epic_1', 'BR1', 'epic'))  && p('name,title,parent,parentName') && e('epic_1,BR1,epic,epic');
