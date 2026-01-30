<?php
$lang->pipeline->browse        = 'Browse Pipeline';
$lang->pipeline->create        = 'Create Pipeline';
$lang->pipeline->edit          = 'Edit Pipeline';
$lang->pipeline->exec          = 'Execute Pipeline';
$lang->pipeline->runPipeline   = 'Run pipeline';
$lang->pipeline->view          = 'Pipeline Details';
$lang->pipeline->delete        = 'Delete Pipeline';
$lang->pipeline->confirmDelete = 'Voullez-vous supprimer ce job ?';
$lang->pipeline->dirChange     = 'Directory Changed';
$lang->pipeline->buildTag      = 'Build Tag';
$lang->pipeline->execSuccess   = 'Build success';
$lang->pipeline->trigger       = 'Trigger';
$lang->pipeline->autoRun       = 'Is it triggered';
$lang->pipeline->addTrigger    = 'Add Trigger';
$lang->pipeline->createType    = 'Create Type';
$lang->pipeline->existPipeline = 'Existing Pipeline';
$lang->pipeline->execution     = 'Execution History';
$lang->pipeline->log           = 'Execution Log';

$lang->pipeline->browseAction = 'Pipeline List';

$lang->pipeline->id              = 'ID';
$lang->pipeline->name            = 'Nom';
$lang->pipeline->desc            = 'Description';
$lang->pipeline->repo            = 'Repo';
$lang->pipeline->branch          = 'Branch';
$lang->pipeline->product         = $lang->productCommon;
$lang->pipeline->svnDir          = 'SVN Tag Watch Path';
$lang->pipeline->jenkins         = 'Jenkins';
$lang->pipeline->jkHost          = 'Serveur Jenkins';
$lang->pipeline->jkJob           = 'T鈉he Jenkins';
$lang->pipeline->buildSpec       = 'Build Target'; // 'pipeline@server'
$lang->pipeline->engine          = 'Engine';
$lang->pipeline->server          = 'Serveur';
$lang->pipeline->pipeline        = 'Pipeline';
$lang->pipeline->buildType       = 'Type Build';
$lang->pipeline->frame           = 'Cadre';
$lang->pipeline->useZentao       = 'Trigger by ZenTao';
$lang->pipeline->triggerType     = 'D閏lencheur';
$lang->pipeline->atDay           = 'Jours Person.';
$lang->pipeline->atTime          = 'heure';
$lang->pipeline->lastStatus      = 'Dern Statut';
$lang->pipeline->lastExec        = 'Dern Exec';
$lang->pipeline->comment         = 'Corres Mots cl閟';
$lang->pipeline->customParam     = 'Paramètres de construction personnalisés';
$lang->pipeline->paramName       = 'Nom';
$lang->pipeline->paramValue      = 'PH';
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
$lang->pipeline->status          = 'Status';
$lang->pipeline->triggerPerson   = 'Trigger Person';
$lang->pipeline->triggerDate     = 'Trigger Date';
$lang->pipeline->duration        = 'Duration';
$lang->pipeline->pipelineName    = 'Pipeline Name';

$lang->pipeline->lblBasic = 'Infos de Base';

$lang->pipeline->auto           = 'Auto';
$lang->pipeline->example        = 'ex.';
$lang->pipeline->commitEx       = "Utilis pour faire correspondre les mots cl閟 utilis閟 pour cr閑r une compilation. Plusieurs mots cl閟 sont s閜ar閟 par ','";
$lang->pipeline->cronSample     = 'ex: 0 0 2 * * 2-6/1 signifie 2:00 a.m. chaque jour de la semaine.';
$lang->pipeline->sendExec       = "Envoi de la demande d'ex閏ution r閡ssie.";
$lang->pipeline->inputName      = 'Veuillez saisir le nom du paramètre.';
$lang->pipeline->invalidName    = 'Les noms des paramètres doivent être alphabétiques, numériques ou soulignés.';
$lang->pipeline->repoExists     = 'This repository has a build task associated with it『%s』';
$lang->pipeline->projectExists  = 'This SonarQube Project has a build task associated with it『%s』';
$lang->pipeline->mustUseJenkins = 'SonarQube frame is only used if the build engine is JenKins.';
$lang->pipeline->jobIsDeleted   = 'This repository is associated with a build task, please view the data from the recycle bin';
$lang->pipeline->selectPipeline = 'Please select a pipeline';
$lang->pipeline->triggerRepeat  = 'Trigger cannot be repeat';

$lang->pipeline->buildTypeList['build']          = 'Seulement Build';
$lang->pipeline->buildTypeList['buildAndDeploy'] = 'Build et D閜loiement';
$lang->pipeline->buildTypeList['buildAndTest']   = 'Build et Test';

$lang->pipeline->triggerTypeList['tag']      = 'Tag';
$lang->pipeline->triggerTypeList['commit']   = 'Code Commit';
$lang->pipeline->triggerTypeList['schedule'] = 'Plannif';

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

$lang->pipeline->featureBar['browse']['all']   = 'All';
$lang->pipeline->featureBar['browse']['space'] = 'Space Pipeline';
$lang->pipeline->featureBar['browse']['repo']  = 'Repo Pipeline';

$lang->pipeline->featureBar['execution']['all']   = 'All';
$lang->pipeline->featureBar['execution']['space'] = 'Space Execution History';
$lang->pipeline->featureBar['execution']['repo']  = 'Repo Execution History';

$lang->pipeline->createTypeList = array();
$lang->pipeline->createTypeList['new']  = 'Create Empty Pipeline';
$lang->pipeline->createTypeList['copy'] = 'Copy From Existing Pipeline';

$lang->pipeline->execStatusList = array();
$lang->pipeline->execStatusList['success']  = 'Success';
$lang->pipeline->execStatusList['failure']  = 'Failure';
$lang->pipeline->execStatusList['running']  = 'Running';
$lang->pipeline->execStatusList['pending']  = 'Pending';
$lang->pipeline->execStatusList['error']    = 'Error';
$lang->pipeline->execStatusList['skipped']  = 'Skipped';
$lang->pipeline->execStatusList['blocked']  = 'Blocked';
$lang->pipeline->execStatusList['declined'] = 'Declined';

$lang->pipeline->triggerTypeList = array();
$lang->pipeline->triggerTypeList['push']         = 'Code Push';
$lang->pipeline->triggerTypeList['manual']       = 'Manual';
$lang->pipeline->triggerTypeList['cron']         = 'Cron';
$lang->pipeline->triggerTypeList['pull_request'] = 'Pull Request';
$lang->pipeline->triggerTypeList['tag']          = 'Tag';

$lang->pipeline->notice = new stdclass();
$lang->pipeline->notice->saveFailed = "Save Failed, Please Retry.";
