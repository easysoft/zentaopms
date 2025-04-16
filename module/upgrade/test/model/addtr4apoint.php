#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$result = zenData('project');
$result->model->range('{10},[scrum,waterfall,kanban,agileplus,waterfallplus,ipd]{30!}');
$result->gen(40);

$result = zenData('object');
$result->project->range('16{15},22{15},28{15},34{15},40{15}');
$result->gen(75);

/**

title=upgradeModel->addTR4APoint();
cid=1
pid=1

- 获取ID为61的项目评审点。
 - 第76条的project属性 @16
 - 第76条的title属性 @TR4A-设计样机评审
 - 第76条的category属性 @TR4A
 - 第76条的type属性 @reviewed
- 获取ID为62的项目评审点。
 - 第77条的project属性 @22
 - 第77条的title属性 @TR4A-设计样机评审
 - 第77条的category属性 @TR4A
 - 第77条的type属性 @reviewed
- 获取ID为63的项目评审点。
 - 第78条的project属性 @28
 - 第78条的title属性 @TR4A-设计样机评审
 - 第78条的category属性 @TR4A
 - 第78条的type属性 @reviewed
- 获取ID为64的项目评审点。
 - 第79条的project属性 @34
 - 第79条的title属性 @TR4A-设计样机评审
 - 第79条的category属性 @TR4A
 - 第79条的type属性 @reviewed
- 获取ID为65的项目评审点。
 - 第80条的project属性 @40
 - 第80条的title属性 @TR4A-设计样机评审
 - 第80条的category属性 @TR4A
 - 第80条的type属性 @reviewed

*/

global $tester;
$upgrade = $tester->loadModel('upgrade');
$upgrade->addTR4APoint();
$objects = $upgrade->dao->select('*')->from(TABLE_OBJECT)->fetchAll('id');
r($objects) && p('76:project,title,category,type') && e('16,TR4A-设计样机评审,TR4A,reviewed'); // 获取ID为61的项目评审点。
r($objects) && p('77:project,title,category,type') && e('22,TR4A-设计样机评审,TR4A,reviewed'); // 获取ID为62的项目评审点。
r($objects) && p('78:project,title,category,type') && e('28,TR4A-设计样机评审,TR4A,reviewed'); // 获取ID为63的项目评审点。
r($objects) && p('79:project,title,category,type') && e('34,TR4A-设计样机评审,TR4A,reviewed'); // 获取ID为64的项目评审点。
r($objects) && p('80:project,title,category,type') && e('40,TR4A-设计样机评审,TR4A,reviewed'); // 获取ID为65的项目评审点。
