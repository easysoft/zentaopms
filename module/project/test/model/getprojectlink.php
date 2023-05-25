#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zdTable('project')->config('project');
$project->gen(4);

/**

title=测试 projectModel->getProjectLink();
timeout=0
cid=1

*/

global $tester;
$projectModel = $tester->loadModel('project');

/** test project module */
r($projectModel->getProjectLink('project', 'execution', 11))      && p() && e('~f:m=project&f=index&projectID=%%s$~'); //test project->multiple=0
r($projectModel->getProjectLink('project', 'execution', 12))      && p() && e('~f:m=project&f=execution&status=all&projectID=%s$~');
r($projectModel->getProjectLink('project', 'test', 12))           && p() && e('~f:m=project&f=index&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'bug', 12))            && p() && e('~f:m=project&f=bug&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'testcase', 12))       && p() && e('~f:m=project&f=testcase&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'testtask', 12))       && p() && e('~f:m=project&f=testtask&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'testreport', 12))     && p() && e('~f:m=project&f=testreport&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'build', 12))          && p() && e('~f:m=project&f=build&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'dynamic', 12))        && p() && e('~f:m=project&f=dynamic&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'view', 12))           && p() && e('~f:m=project&f=view&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'manageproducts', 12)) && p() && e('~f:m=project&f=manageproducts&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'team', 12))           && p() && e('~f:m=project&f=team&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'managemembers', 12))  && p() && e('~f:m=project&f=managemembers&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'whitelist', 12))      && p() && e('~f:m=project&f=whitelist&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'addwhitelist', 12))   && p() && e('~f:m=project&f=addwhitelist&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'group', 12))          && p() && e('~f:m=project&f=group&projectID=%%s$~');
r($projectModel->getProjectLink('project', 'managePriv', 12))     && p() && e('~f:m=project&f=group&projectID=%%s$~');

/** test product module */
r($projectModel->getProjectLink('product', 'showerrornone', 12))  && p() && e('~f:m=projectstory&f=story&projectID=%%s$~');

/** test projectstory module */
r($projectModel->getProjectLink('projectstory', 'story', 12))     && p() && e('~f:m=projectstory&f=story&projectID=%%s$~');
r($projectModel->getProjectLink('projectstory', 'linkstory', 12)) && p() && e('~f:m=projectstory&f=linkstory&projectID=%%s$~');
r($projectModel->getProjectLink('projectstory', 'track', 12))     && p() && e('~f:m=projectstory&f=track&projectID=%%s$~');

/** test bug module */
r($projectModel->getProjectLink('bug', 'create', 12)) && p() && e('~f:m=bug&f=create&productID=0&branch=0&extras=projectID=%%s$~');
r($projectModel->getProjectLink('bug', 'edit', 12))   && p() && e('~f:m=project&f=bug&projectID=%%s$~');

/** test story module */
r($projectModel->getProjectLink('story', 'change', 12))   && p() && e('~f:m=projectstory&f=story&projectID=%%s$~');
r($projectModel->getProjectLink('story', 'create', 12))   && p() && e('~f:m=projectstory&f=story&projectID=%%s$~');
r($projectModel->getProjectLink('story', 'zerocase', 12)) && p() && e('~f:m=project&f=testcase&projectID=%%s$~');

/** test testcase module */
r($projectModel->getProjectLink('testcase', 'browse', 12)) && p() && e('~f:m=project&f=testcase&projectID=%%s$~');

/** test testtask module */
r($projectModel->getProjectLink('testtask', 'browseunits', 12)) && p() && e('~f:m=project&f=testcase&projectID=%%s$~');
r($projectModel->getProjectLink('testtask', 'browse', 12))      && p() && e('~f:m=project&f=testtask&projectID=%%s$~');

/** test testreport module */
r($projectModel->getProjectLink('testreport', 'browse', 12)) && p() && e('~f:m=project&f=testreport&projectID=%%s$~');

/** test repo module */
r($projectModel->getProjectLink('repo', 'browse', 12)) && p() && e('~f:m=repo&f=browse&repoID=&branchID=&objectID=%%s#app=project$~');

/** test repo module */
r($projectModel->getProjectLink('doc', 'browse', 12)) && p() && e('~f:m=doc&f=projectSpace&objectID=%%s#app=project$~');

/** test api module */
r($projectModel->getProjectLink('api', 'browse', 12)) && p() && e('~f:m=project&f=index&projectID=%%s$~');

/** test build module */
r($projectModel->getProjectLink('build', 'create', 12)) && p() && e('~f:m=build&f=create&executionID=&productID=&projectID=%%s#app=project$~');

$tester->app->tab = 'project';
r($projectModel->getProjectLink('build', 'browse', 12)) && p() && e('~f:m=projectbuild&f=browse&projectID=%%s$~');

$tester->app->tab = 'my';
r($projectModel->getProjectLink('build', 'browse', 12)) && p() && e('~f:m=project&f=build&projectID=%%s$~');

/** test projectrelease module */
r($projectModel->getProjectLink('projectrelease', 'create', 12)) && p() && e('~f:m=projectrelease&f=create&projectID=%%s$~');
r($projectModel->getProjectLink('projectrelease', 'browse', 12)) && p() && e('~f:m=projectrelease&f=browse&projectID=%%s$~');

/** test projectrelease module */
r($projectModel->getProjectLink('stakeholder', 'create', 12)) && p() && e('~f:m=stakeholder&f=create&projectID=%%s$~');
r($projectModel->getProjectLink('stakeholder', 'browse', 12)) && p() && e('~f:m=stakeholder&f=browse&projectID=%%s$~');

/** test issue module */
r($projectModel->getProjectLink('issue', 'projectsummary', 12)) && p() && e('~f:m=issue&f=projectsummary&projectID=%%s#app=project$~');
r($projectModel->getProjectLink('issue', 'browse', 12))         && p() && e('~f:m=issue&f=browse&projectID=%%s$~');

/** test zahost module which not in waterfallModules */
r($projectModel->getProjectLink('zahost', 'browse', 12)) && p() && e('~f:m=project&f=index&projectID=%%s$~');

/** test design module which in waterfallModules */
r($projectModel->getProjectLink('design', 'browse', 12)) && p() && e('~f:m=design&f=browse&projectID=%%s$~');

/** test reviewissue module */
r($projectModel->getProjectLink('reviewissue', 'browse', 12)) && p() && e('~f:m=reviewissue&f=issue&projectID=%%s$~');

/** test programplan module */
r($projectModel->getProjectLink('programplan', 'execution', 12)) && p() && e('~f:m=project&f=execution&type=all&projectID=%s$~');