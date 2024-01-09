<?php
$lang->solution->market = new stdclass;
$lang->solution->market->browse = 'Solution Market';
$lang->solution->market->view   = 'Solution Detail';

$lang->solution->name = 'Name';

$lang->solution->browse        = 'Installed';
$lang->solution->view          = 'Solution Detail';
$lang->solution->detail        = 'Detail';
$lang->solution->progress      = 'Progress';
$lang->solution->install       = 'Install';
$lang->solution->background    = 'Background';
$lang->solution->cancelInstall = 'Cancel';
$lang->solution->uninstall     = 'Uninstall';
$lang->solution->retryInstall  = 'Retry';
$lang->solution->nextStep      = 'Next';
$lang->solution->config        = 'Config';

$lang->solution->introduction = 'Introduction';
$lang->solution->scenes       = 'Scenes';
$lang->solution->diagram      = 'Diagram';
$lang->solution->includedApp  = 'Included App';
$lang->solution->features     = 'Features';
$lang->solution->relatedLinks = 'Related Links';
$lang->solution->customers    = 'Customers';
$lang->solution->apps         = 'Installed Apps';
$lang->solution->externalApps = 'External Apps';
$lang->solution->resources    = 'Resources';

$lang->solution->editName = 'Edit Name';

$lang->solution->chooseApp           = 'Choose App';
$lang->solution->noInstalledSolution = 'No installed solution';
$lang->solution->toInstall           = 'To Install';

$lang->solution->notices = new stdclass;
$lang->solution->notices->fail                 = 'Fail';
$lang->solution->notices->success              = 'Success';
$lang->solution->notices->creatingSolution     = 'Creating solution...';
$lang->solution->notices->uninstallingSolution = 'Uninstalling solution...';
$lang->solution->notices->installingApp        = 'Installing: ';
$lang->solution->notices->installationSuccess  = 'Installation success!';
$lang->solution->notices->cancelInstall        = 'Are you sure to cancel installation?';
$lang->solution->notices->confirmToUninstall   = 'Are you sure to uninstall?';
$lang->solution->notices->confirmReinstall     = 'Are you sure to retry installation?';

$lang->solution->errors = new stdclass;
$lang->solution->errors->error                = 'Error';
$lang->solution->errors->notFound             = 'Not found';
$lang->solution->errors->failToInstallApp     = 'Fail to install %s';
$lang->solution->errors->timeout              = 'Timeout';
$lang->solution->errors->failToUninstallApp   = 'Fail to uninstall %s';
$lang->solution->errors->hasInstallationError = 'Has installation error';
$lang->solution->errors->notFoundAppByVersion = 'Not found %s version of %s';
$lang->solution->errors->notEnoughResource    = 'Not enough resource, please increase the configuration or release other resources and try again.';

$lang->solution->installationErrors = array();
$lang->solution->installationErrors['waiting']           = 'Installation not started.';
$lang->solution->installationErrors['installing']        = 'Installing.';
$lang->solution->installationErrors['installed']         = 'Installed';
$lang->solution->installationErrors['uninstalling']      = 'Installation canceled.';
$lang->solution->installationErrors['cneError']          = 'Installation failed.';
$lang->solution->installationErrors['timeout']           = 'Installation timeout.';
$lang->solution->installationErrors['notFoundApp']       = 'Not found app to be installed.';
$lang->solution->installationErrors['notEnoughResource'] = 'Not enough resource, please increase the configuration or release other resources and try again.';
