#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonTao->getPreAndNextSQL();
timeout=0
cid=15725

- 执行common模块的getPreAndNextSQL方法，参数是'story'  @SELECT * FROM `zt_story` WHERE id < 40
- 执行common模块的getPreAndNextSQL方法，参数是'story'  @SELECT *, IF(`pri` = 0, 256, `pri`) as priOrder, IF(`severity` = 0, 256, `severity`) as severityOrder FROM `zt_story` WHERE id < 40  ORDER BY `id` desc,`priOrder`,`severityOrder`

- 执行common模块的getPreAndNextSQL方法，参数是'story'  @SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version
- 执行common模块的getPreAndNextSQL方法，参数是'story'  @SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version ORDER BY t1.id desc
- 执行common模块的getPreAndNextSQL方法，参数是'story'  @SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version ORDER BY t1.title asc

*/

global $tester;
$tester->loadModel('common');

$_SESSION['storyQueryCondition'] = 'id < 40';
$_SESSION['storyOnlyCondition']  = true;
r(trim($tester->common->getPreAndNextSQL('story'))) && p() && e('SELECT * FROM `zt_story` WHERE id < 40');

$_SESSION['storyOrderBy'] = 'id desc, priOrder, severityOrder';
r(trim($tester->common->getPreAndNextSQL('story'))) && p() && e('SELECT *, IF(`pri` = 0, 256, `pri`) as priOrder, IF(`severity` = 0, 256, `severity`) as severityOrder FROM `zt_story` WHERE id < 40  ORDER BY `id` desc,`priOrder`,`severityOrder`');

$_SESSION['storyQueryCondition'] = 'SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version';
$_SESSION['storyOnlyCondition']  = false;
unset($_SESSION['storyOrderBy']);
r(trim($tester->common->getPreAndNextSQL('story'))) && p() && e('SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version');

$_SESSION['storyOrderBy'] = 't1.id desc';
r(trim($tester->common->getPreAndNextSQL('story'))) && p() && e('SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version ORDER BY t1.id desc');

$_SESSION['storyOrderBy'] = 't1.title asc';
r(trim($tester->common->getPreAndNextSQL('story'))) && p() && e('SELECT t1.* FROM `zt_story` AS t1 LEFT JOIN `zt_storyspec` AS t2 on t1.id=t2.story WHERE t1.id < 40 AND t1.version = t2.version ORDER BY t1.title asc');