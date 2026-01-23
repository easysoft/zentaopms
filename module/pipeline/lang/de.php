<?php
$lang->pipeline->browse        = 'Browse Pipeline';
$lang->pipeline->create        = 'Create Pipeline';
$lang->pipeline->edit          = 'Edit Pipeline';
$lang->pipeline->exec          = 'Execute Pipeline';
$lang->pipeline->runPipeline   = 'Run pipeline';
$lang->pipeline->view          = 'Pipeline Details';
$lang->pipeline->delete        = 'Delete Pipeline';
$lang->pipeline->confirmDelete = 'Do you want to delete this job?';
$lang->pipeline->dirChange     = 'Directory Changed';
$lang->pipeline->buildTag      = 'Build Tag';
$lang->pipeline->execSuccess   = 'Build success';
$lang->pipeline->trigger       = 'Trigger';
$lang->pipeline->autoRun       = 'Is it triggered';
$lang->pipeline->addTrigger    = 'Add Trigger';
$lang->pipeline->createType    = 'Create Type';
$lang->pipeline->existPipeline = 'Existing Pipeline';

$lang->pipeline->browseAction = 'Pipeline List';

$lang->pipeline->id              = 'ID';
$lang->pipeline->name            = 'Name';
$lang->pipeline->desc            = 'Description';
$lang->pipeline->repo            = 'Repo';
$lang->pipeline->branch          = 'Branch';
$lang->pipeline->product         = $lang->productCommon;
$lang->pipeline->svnDir          = 'SVN Tag Watch Path';
$lang->pipeline->jenkins         = 'Jenkins';
$lang->pipeline->jkHost          = 'Jenkins Server';
$lang->pipeline->jkJob           = 'Jenkins Task';
$lang->pipeline->buildSpec       = 'Build Target'; // 'pipeline@server'
$lang->pipeline->engine          = 'Engine';
$lang->pipeline->server          = 'Server';
$lang->pipeline->pipeline        = 'Pipeline';
$lang->pipeline->buildType       = 'Build Type';
$lang->pipeline->frame           = 'Frame';
$lang->pipeline->useZentao       = 'Trigger by ZenTao';
$lang->pipeline->triggerType     = 'Trigger';
$lang->pipeline->atDay           = 'Custom Days';
$lang->pipeline->atTime          = 'At Time';
$lang->pipeline->lastStatus      = 'Last Status';
$lang->pipeline->lastExec        = 'Last Executed';
$lang->pipeline->comment         = 'Match Keywords';
$lang->pipeline->customParam     = 'Benutzerdefinierte Bauparameter';
$lang->pipeline->paramName       = 'Name';
$lang->pipeline->paramValue      = 'Wert';
$lang->pipeline->custom          = 'Custom';
$lang->pipeline->createdBy       = 'Created By';
$lang->pipeline->createdDate     = 'Created Date';
$lang->pipeline->editedBy        = 'Edited By';
$lang->pipeline->editedDate      = 'Edited Date';
$lang->pipeline->lastTag         = 'Last Tag';
$lang->pipeline->deleted         = 'Deleted';
$lang->pipeline->repoServer      = 'Repo Server';
$lang->pipeline->sonarqubeServer = 'SonarQube Server';
$lang->pipeline->projectKey      = 'SonarQube Project';
$lang->pipeline->space           = 'Space';

$lang->pipeline->lblBasic = 'Basic Info';

$lang->pipeline->auto           = 'Auto';
$lang->pipeline->example        = 'e.g.';
$lang->pipeline->commitEx       = "Used to match the keywords used to create a compile. Multiple keywords are separated by ','";
$lang->pipeline->cronSample     = 'e.g. 0 0 2 * * 2-6/1 means 2:00 a.m. every weekday.';
$lang->pipeline->sendExec       = 'Send execute request success.';
$lang->pipeline->inputName      = 'Bitte geben Sie den Parameternamen ein.';
$lang->pipeline->invalidName    = 'Die Parameternamen sollten Buchstaben, Zahlen oder Unterstriche sein.';
$lang->pipeline->repoExists     = 'This repository has a build task associated with it『%s』';
$lang->pipeline->projectExists  = 'This SonarQube Project has a build task associated with it『%s』';
$lang->pipeline->mustUseJenkins = 'SonarQube frame is only used if the build engine is JenKins.';
$lang->pipeline->jobIsDeleted   = 'This repository is associated with a build task, please view the data from the recycle bin';
$lang->pipeline->selectPipeline = 'Please select a pipeline';
$lang->pipeline->triggerRepeat  = 'Trigger cannot be repeat';

$lang->pipeline->buildTypeList['build']          = 'Only Build';
$lang->pipeline->buildTypeList['buildAndDeploy'] = 'Build And Deploy';
$lang->pipeline->buildTypeList['buildAndTest']   = 'Build And Test';

$lang->pipeline->triggerTypeList['tag']      = 'Tag';
$lang->pipeline->triggerTypeList['commit']   = 'Code Commit';
$lang->pipeline->triggerTypeList['schedule'] = 'Schedule';

$lang->pipeline->frameList['']          = '';
$lang->pipeline->frameList['junit']     = 'JUnit';
$lang->pipeline->frameList['testng']    = 'TestNG';
$lang->pipeline->frameList['phpunit']   = 'PHPUnit';
$lang->pipeline->frameList['pytest']    = 'Pytest';
$lang->pipeline->frameList['jtest']     = 'JTest';
$lang->pipeline->frameList['cppunit']   = 'CppUnit';
$lang->pipeline->frameList['gtest']     = 'GTest';
$lang->pipeline->frameList['qtest']     = 'QTest';
$lang->pipeline->frameList['sonarqube'] = 'SonarQube';

$lang->pipeline->paramValueList['']                 = '';
$lang->pipeline->paramValueList['$zentao_version']  = 'Current version';
$lang->pipeline->paramValueList['$zentao_account']  = 'Current user';
$lang->pipeline->paramValueList['$zentao_product']  = "Current {$lang->productCommon} ID";
$lang->pipeline->paramValueList['$zentao_repopath'] = 'Current version library path';

$lang->pipeline->engineList = array();
$lang->pipeline->engineList['']        = '';
$lang->pipeline->engineList['gitlab']  = 'GitLab';
$lang->pipeline->engineList['jenkins'] = 'Jenkins';

$lang->pipeline->engineTips = new stdclass;
$lang->pipeline->engineTips->success = 'Build engine will use the built pipeline in GitLab.';
$lang->pipeline->engineTips->error   = 'No pipeline is currently available in the GitLab project, please go to GitLab configuration first.  ';

$lang->pipeline->pipelineTips                      = "Run for branch name or tag";
$lang->pipeline->pipelineVariables                 = "Variables";
$lang->pipeline->pipelineVariablesKeyPlaceHolder   = "Input variable key";
$lang->pipeline->pipelineVariablesValuePlaceHolder = "Input variable value";
$lang->pipeline->pipelineVariablesTips             = "Specify variable values to be used in this run. The values specified in CI/CD settings will be used by default.";
$lang->pipeline->setReferenceTips                  = "Before performing a build, please set up the branch information of the code base.";

$lang->pipeline->featureBar['browse']['job']     = 'List';
$lang->pipeline->featureBar['browse']['compile'] = 'History';

$lang->pipeline->createTypeList = array();
$lang->pipeline->createTypeList['new']  = 'Create Empty Pipeline';
$lang->pipeline->createTypeList['copy'] = 'Copy From Existing Pipeline';
