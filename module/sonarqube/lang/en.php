<?php
$lang->sonarqube = new stdclass;
$lang->sonarqube->common            = 'SonarQube';
$lang->sonarqube->browse            = 'SonarQube Browse';
$lang->sonarqube->search            = 'Search';
$lang->sonarqube->create            = 'Create SonarQube';
$lang->sonarqube->edit              = 'Edit SonarQube';
$lang->sonarqube->delete            = 'Delete SonarQube';
$lang->sonarqube->serverFail        = 'Connect to SonarQube server failed, please check the SonarQube server.';
$lang->sonarqube->browseProject     = "SonarQube Project List";
$lang->sonarqube->createProject     = "Create SonarQube Project";
$lang->sonarqube->deleteProject     = "Delete SonarQube Project";
$lang->sonarqube->placeholderSearch = 'Project name';
$lang->sonarqube->execJob           = "Exec SonarQube Job";
$lang->sonarqube->desc              = 'Description';
$lang->sonarqube->reportView        = "SonarQube Report";
$lang->sonarqube->browseIssue       = "SonarQube Issue List";

$lang->sonarqube->id             = 'ID';
$lang->sonarqube->name           = "Server Name";
$lang->sonarqube->url            = 'Server Address';
$lang->sonarqube->account        = 'Username';
$lang->sonarqube->password       = 'Password';
$lang->sonarqube->token          = 'Token';
$lang->sonarqube->defaultProject = 'Default Project';
$lang->sonarqube->private        = 'MD5 Verify';

$lang->sonarqube->createServer  = 'Create SonarQube Server';
$lang->sonarqube->editServer    = 'Edit SonarQube Server';
$lang->sonarqube->createSuccess = 'Create success';

$lang->sonarqube->placeholder = new stdclass;
$lang->sonarqube->placeholder->name    = '';
$lang->sonarqube->placeholder->url     = "Please fill in the access address of the SonarQube Server homepage, as: https://sonarqube.zentao.net.";
$lang->sonarqube->placeholder->account = "Please fill in the SonarQube user information with Administrator privileges";

$lang->sonarqube->nameRepeatError      = "Server name  already exists!";
$lang->sonarqube->urlRepeatError       = 'Server address already exists!';
$lang->sonarqube->validError           = 'SonarQube user authority authentication failed!';
$lang->sonarqube->hostError            = "Invalid SonarQube service address.";
$lang->sonarqube->confirmDelete        = 'Do you want to delete this SonarQube server?';
$lang->sonarqube->confirmDeleteProject = 'Do you want to delete this SonarQube project?';
$lang->sonarqube->noReport             = "No Reprot";
$lang->sonarqube->notAdminer           = "Please fill in the SonarQube user information with Administrator privileges";

$lang->sonarqube->projectKey          = 'Project Key';
$lang->sonarqube->projectName         = 'Project Name';
$lang->sonarqube->projectlastAnalysis = 'Last analysis time';

$lang->sonarqube->report = new stdclass();
$lang->sonarqube->report->bugs                       = 'Bugs';
$lang->sonarqube->report->vulnerabilities            = 'Vulnerabilities';
$lang->sonarqube->report->security_hotspots_reviewed = 'Hotspots Reviewed';
$lang->sonarqube->report->code_smells                = 'Code Smells';
$lang->sonarqube->report->coverage                   = 'Coverage';
$lang->sonarqube->report->duplicated_lines_density   = 'Duplications';
$lang->sonarqube->report->ncloc                      = 'Lines';

$lang->sonarqube->qualitygateList = array();
$lang->sonarqube->qualitygateList['OK']    = 'Passed';
$lang->sonarqube->qualitygateList['WARN']  = 'Warning';
$lang->sonarqube->qualitygateList['ERROR'] = 'Failed';
