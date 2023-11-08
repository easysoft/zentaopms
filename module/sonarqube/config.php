<?php
$config->sonarqube = new stdclass();

$config->sonarqube->create = new stdclass();
$config->sonarqube->create->requiredFields = 'name,url,account,password';

$config->sonarqube->edit = new stdclass();
$config->sonarqube->edit->requiredFields = 'name,url,account,password';

$config->sonarqube->projectStatusClass = array();
$config->sonarqube->projectStatusClass['OK']    = 'success';
$config->sonarqube->projectStatusClass['WARN']  = 'warning';
$config->sonarqube->projectStatusClass['ERROR'] = 'danger';

$config->sonarqube->createproject = new stdclass();
$config->sonarqube->createproject->requiredFields = 'projectName,projectKey';

$config->sonarqube->cacheTime = 10;

global $lang;
$config->sonarqube->actionList = array();
$config->sonarqube->actionList['list']['icon'] = 'list';
$config->sonarqube->actionList['list']['text'] = $lang->sonarqube->browseProject;
$config->sonarqube->actionList['list']['hint'] = $lang->sonarqube->browseProject;
$config->sonarqube->actionList['list']['url']  = helper::createLink('sonarqube', 'browseProject',"sonarqubeID={id}");

$config->sonarqube->actionList['edit']['icon'] = 'edit';
$config->sonarqube->actionList['edit']['text'] = $lang->sonarqube->edit;
$config->sonarqube->actionList['edit']['hint'] = $lang->sonarqube->edit;
$config->sonarqube->actionList['edit']['url']  = helper::createLink('sonarqube', 'edit',"sonarqubeID={id}");

$config->sonarqube->actionList['delete']['icon']       = 'trash';
$config->sonarqube->actionList['delete']['text']       = $lang->sonarqube->delete;
$config->sonarqube->actionList['delete']['hint']       = $lang->sonarqube->delete;
$config->sonarqube->actionList['delete']['ajaxSubmit'] = true;
$config->sonarqube->actionList['delete']['url']        = helper::createLink('sonarqube', 'delete',"sonarqubeID={id}");

$config->sonarqube->actionList['deleteProject']['icon']         = 'trash';
$config->sonarqube->actionList['deleteProject']['text']         = $lang->sonarqube->deleteProject;
$config->sonarqube->actionList['deleteProject']['hint']         = $lang->sonarqube->deleteProject;
$config->sonarqube->actionList['deleteProject']['ajaxSubmit']   = true;
$config->sonarqube->actionList['deleteProject']['data-confirm'] = $lang->sonarqube->confirmDeleteProject;
$config->sonarqube->actionList['deleteProject']['url']          = helper::createLink('sonarqube', 'deleteProject',"sonarqubeID={sonarqubeID}&projectID={projectKey}");

$config->sonarqube->actionList['execJob']['icon'] = 'sonarqube';
$config->sonarqube->actionList['execJob']['text'] = $lang->sonarqube->execJob;
$config->sonarqube->actionList['execJob']['hint'] = $lang->sonarqube->execJob;
$config->sonarqube->actionList['execJob']['url']  = helper::createLink('sonarqube', 'execJob',"jobID={jobID}");

$config->sonarqube->actionList['reportView']['icon']        = 'audit';
$config->sonarqube->actionList['reportView']['text']        = $lang->sonarqube->reportView;
$config->sonarqube->actionList['reportView']['hint']        = $lang->sonarqube->reportView;
$config->sonarqube->actionList['reportView']['data-toggle'] = 'modal';
$config->sonarqube->actionList['reportView']['url']         = helper::createLink('sonarqube', 'reportView',"jobID={jobID}");
