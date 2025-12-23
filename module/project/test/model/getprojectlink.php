#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zenData('project')->loadYaml('project');
$project->gen(4);

/**

title=测试 projectModel->getProjectLink();
timeout=0
cid=17846

- test project->multiple=0 @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'execution'     , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'test'          , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'bug'           , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'testcase'      , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'testtask'      , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'testreport'    , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'build'         , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'dynamic'       , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'view'          , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'manageproducts', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'team'          , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'managemembers' , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'whitelist'     , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'addwhitelist'  , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'group'         , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'project', 'managePriv'    , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'product', 'showerrornone', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'projectstory', 'story'    , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'projectstory', 'linkstory', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'projectstory', 'track'    , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'bug', 'create', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'bug', 'edit'  , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'story', 'change'  , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'story', 'create'  , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'story', 'zerocase', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'testcase', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'testtask', 'browseunits' , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'testtask', 'browse'      , 12)) > 0   @1
- 执行projectModel模块的getProjectLink方法，参数是'testreport', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'repo', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'doc', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'api', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'build', 'create', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'build', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'build', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'projectrelease', 'create', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'projectrelease', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'stakeholder', 'create', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'stakeholder', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'issue', 'projectsummary', 11)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'issue', 'browse'        , 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'zahost', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'design', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'reviewissue', 'browse', 12)) > 0  @1
- 执行projectModel模块的getProjectLink方法，参数是'programplan', 'execution', 12)) > 0  @1

*/

global $tester;
$projectModel = $tester->loadModel('project');

/* test project module */
r(strlen($projectModel->getProjectLink('project', 'execution'     , 11)) > 0) && p() && e('1'); //test project->multiple=0
r(strlen($projectModel->getProjectLink('project', 'execution'     , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'test'          , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'bug'           , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'testcase'      , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'testtask'      , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'testreport'    , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'build'         , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'dynamic'       , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'view'          , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'manageproducts', 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'team'          , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'managemembers' , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'whitelist'     , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'addwhitelist'  , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'group'         , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('project', 'managePriv'    , 12)) > 0) && p() && e('1');

/* test product module */
r(strlen($projectModel->getProjectLink('product', 'showerrornone', 12)) > 0) && p() && e('1');

/* test projectstory module */
r(strlen($projectModel->getProjectLink('projectstory', 'story'    , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('projectstory', 'linkstory', 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('projectstory', 'track'    , 12)) > 0) && p() && e('1');

/* test bug module */
r(strlen($projectModel->getProjectLink('bug', 'create', 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('bug', 'edit'  , 12)) > 0) && p() && e('1');

/* test story module */
r(strlen($projectModel->getProjectLink('story', 'change'  , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('story', 'create'  , 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('story', 'zerocase', 12)) > 0) && p() && e('1');

/* test testcase module */
r(strlen($projectModel->getProjectLink('testcase', 'browse', 12)) > 0) && p() && e('1');

/* test testtask module */
r(strlen($projectModel->getProjectLink('testtask', 'browseunits' , 12)) > 0)  && p() && e('1');
r(strlen($projectModel->getProjectLink('testtask', 'browse'      , 12)) > 0 ) && p() && e('1');

/* test testreport module */
r(strlen($projectModel->getProjectLink('testreport', 'browse', 12)) > 0) && p() && e('1');

/* test repo module */
r(strlen($projectModel->getProjectLink('repo', 'browse', 12)) > 0) && p() && e('1');

/* test repo module */
r(strlen($projectModel->getProjectLink('doc', 'browse', 12)) > 0) && p() && e('1');

/* test api module */
r(strlen($projectModel->getProjectLink('api', 'browse', 12)) > 0) && p() && e('1');

/* test build module */
r(strlen($projectModel->getProjectLink('build', 'create', 12)) > 0) && p() && e('1');

$tester->app->tab = 'project';
r(strlen($projectModel->getProjectLink('build', 'browse', 12)) > 0) && p() && e('1');

$tester->app->tab = 'my';
r(strlen($projectModel->getProjectLink('build', 'browse', 12)) > 0) && p() && e('1');

/* test projectrelease module */
r(strlen($projectModel->getProjectLink('projectrelease', 'create', 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('projectrelease', 'browse', 12)) > 0) && p() && e('1');

/* test projectrelease module */
r(strlen($projectModel->getProjectLink('stakeholder', 'create', 12)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('stakeholder', 'browse', 12)) > 0) && p() && e('1');

/* test issue module */
r(strlen($projectModel->getProjectLink('issue', 'projectsummary', 11)) > 0) && p() && e('1');
r(strlen($projectModel->getProjectLink('issue', 'browse'        , 12)) > 0) && p() && e('1');

/* test zahost module which not in waterfallModules */
r(strlen($projectModel->getProjectLink('zahost', 'browse', 12)) > 0) && p() && e('1');

/* test design module which in waterfallModules */
r(strlen($projectModel->getProjectLink('design', 'browse', 12)) > 0) && p() && e('1');

/* test reviewissue module */
r(strlen($projectModel->getProjectLink('reviewissue', 'browse', 12)) > 0) && p() && e('1');

/* test programplan module */
r(strlen($projectModel->getProjectLink('programplan', 'execution', 12)) > 0) && p() && e('1');
