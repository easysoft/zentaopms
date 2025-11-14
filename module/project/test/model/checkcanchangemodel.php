#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

zenData('project')->loadYaml('project')->gen(4);
zenData('bug')->gen(0);
zenData('doc')->gen(0);
zenData('case')->gen(0);
zenData('build')->gen(0);
zenData('release')->gen(0);
zenData('testtask')->gen(0);
zenData('design')->gen(0);
zenData('review')->gen(0);
zenData('researchplan')->gen(0);
zenData('issue')->gen(0);
zenData('risk')->gen(0);
zenData('opportunity')->gen(0);
zenData('auditplan')->gen(0);
zenData('gapanalysis')->gen(0);
zenData('meeting')->gen(0);

/**

title=测试 projectModel->checkCanChangeModel();
timeout=0
cid=17806

- 执行project模块的checkCanChangeModel方法，参数是1, scrum @0

- 执行project模块的checkCanChangeModel方法，参数是1, agileplus @0

- 执行project模块的checkCanChangeModel方法，参数是1, kanban @0

- 执行project模块的checkCanChangeModel方法，参数是1, waterfall @1

- 执行project模块的checkCanChangeModel方法，参数是1, waterfallplus @1

- 执行project模块的checkCanChangeModel方法，参数是1,  @0

*/

global $tester;
$tester->loadModel('project');

r($tester->project->checkCanChangeModel(1, 'scrum'))         && p() && e('0');
r($tester->project->checkCanChangeModel(1, 'agileplus'))     && p() && e('0');
r($tester->project->checkCanChangeModel(1, 'kanban'))        && p() && e('0');
r($tester->project->checkCanChangeModel(1, 'waterfall'))     && p() && e('1');
r($tester->project->checkCanChangeModel(1, 'waterfallplus')) && p() && e('1');
r($tester->project->checkCanChangeModel(1, ''))              && p() && e('0');
