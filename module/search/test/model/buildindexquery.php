#!/usr/bin/env php
<?php

/**

title=测试 searchModel::buildIndexQuery();
timeout=0
cid=18293

- 执行searchTest模块的buildIndexQueryTest方法，参数是'program'  @SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type`  = 'program' AND  t1.deleted  = '0'
- 执行searchTest模块的buildIndexQueryTest方法，参数是'story'  @SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'story' AND  t1.version=t2.version

- 执行searchTest模块的buildIndexQueryTest方法，参数是'doc'  @SELECT DISTINCT t1.*, t2.content, t2.digest FROM `zt_doc` AS t1  LEFT JOIN `zt_doccontent` AS t2  ON t1.id=t2.doc  WHERE t1.deleted  = '0' AND  t1.version=t2.version

- 执行searchTest模块的buildIndexQueryTest方法，参数是'project'  @SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type`  = 'project' AND  t1.deleted  = '0'
- 执行searchTest模块的buildIndexQueryTest方法，参数是'execution'  @SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type` IN ('stage','sprint','kanban') AND  t1.deleted  = '0'

- 执行searchTest模块的buildIndexQueryTest方法，参数是'requirement'  @SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'requirement' AND  t1.version=t2.version

- 执行searchTest模块的buildIndexQueryTest方法，参数是'epic'  @SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'epic' AND  t1.version=t2.version

- 执行searchTest模块的buildIndexQueryTest方法，参数是'project', false  @SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type`  = 'project'

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 准备测试数据
zenData('project')->gen(5);
zenData('story')->gen(5);
zenData('doc')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$searchTest = new searchTest();

r($searchTest->buildIndexQueryTest('program'))        && p() && e("SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type`  = 'program' AND  t1.deleted  = '0'");
r($searchTest->buildIndexQueryTest('story'))          && p() && e("SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'story' AND  t1.version=t2.version");
r($searchTest->buildIndexQueryTest('doc'))            && p() && e("SELECT DISTINCT t1.*, t2.content, t2.digest FROM `zt_doc` AS t1  LEFT JOIN `zt_doccontent` AS t2  ON t1.id=t2.doc  WHERE t1.deleted  = '0' AND  t1.version=t2.version");
r($searchTest->buildIndexQueryTest('project'))        && p() && e("SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type`  = 'project' AND  t1.deleted  = '0'");
r($searchTest->buildIndexQueryTest('execution'))      && p() && e("SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type` IN ('stage','sprint','kanban') AND  t1.deleted  = '0'");
r($searchTest->buildIndexQueryTest('requirement'))    && p() && e("SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'requirement' AND  t1.version=t2.version");
r($searchTest->buildIndexQueryTest('epic'))           && p() && e("SELECT DISTINCT t1.*, t2.spec, t2.verify FROM `zt_story` AS t1  LEFT JOIN `zt_storyspec` AS t2  ON t1.id=t2.story  WHERE t1.deleted  = '0' AND  `type`  = 'epic' AND  t1.version=t2.version");
r($searchTest->buildIndexQueryTest('project', false)) && p() && e("SELECT t1.* FROM `zt_project` AS t1  WHERE (t1.`isTpl` = '0' OR t1.`isTpl` IS NULL) AND 1=1  AND  `type`  = 'project'");