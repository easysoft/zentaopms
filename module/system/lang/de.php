<?php
$lang->system->common          = 'Monitor';
$lang->system->dashboard       = 'Monitor';
$lang->system->systemInfo      = 'System information';
$lang->system->dbManagement    = 'Database management';
$lang->system->ldapManagement  = 'LDAP';
$lang->system->dbList          = 'Database list';
$lang->system->configDomain    = 'Config Domain';
$lang->system->ossView         = 'OSS';
$lang->system->dbName          = 'Name';
$lang->system->dbStatus        = 'Status';
$lang->system->dbType          = 'Type';
$lang->system->action          = 'Action';
$lang->system->management      = 'Management';
$lang->system->visit           = 'Visit';
$lang->system->close           = 'Close';
$lang->system->installLDAP     = 'Install LDAP';
$lang->system->editLDAP        = 'Edit';
$lang->system->LDAPInfo        = 'LDAP information';
$lang->system->accountInfo     = 'Account information';
$lang->system->advance         = 'Senior';
$lang->system->verify          = 'Check';
$lang->system->copy            = 'Copy';
$lang->system->copySuccess     = 'Copied to clipboard';
$lang->system->cneStatus       = 'Platform status';
$lang->system->cneStatistic    = 'Resource statistics';
$lang->system->latestDynamic   = 'Latest News';
$lang->system->nodeQuantity    = 'Nodes';
$lang->system->serviceQuantity = 'Number of services';
$lang->system->cpuUsage        = 'CPU（Core）';
$lang->system->memUsage        = 'Memory（GB）';
$lang->system->name            = ucfirst($lang->product->system) . ' name';
$lang->system->integrated      = 'Integrated ' . $lang->product->system . '';
$lang->system->latestRelease   = 'Latest version';
$lang->system->children        = 'Included ' . $lang->product->system . 's';
$lang->system->latestRelease   = 'Latest version';
$lang->system->status          = 'Status';
$lang->system->desc            = 'Description';
$lang->system->browse          = ucfirst($lang->product->system) . ' list';
$lang->system->create          = 'Create ' . $lang->product->system . '';
$lang->system->edit            = 'Edit ' . $lang->product->system . '';
$lang->system->delete          = 'Delete ' . $lang->product->system . '';
$lang->system->active          = 'Online ' . $lang->product->system . '';
$lang->system->inactive        = 'Offline ' . $lang->product->system . '';
$lang->system->integratedLabel = 'Integrated';

$lang->system->integratedList = array();
$lang->system->integratedList[0] = 'No';
$lang->system->integratedList[1] = 'Yes';

$lang->system->statusList = array();
$lang->system->statusList['active']   = 'Active';
$lang->system->statusList['inactive'] = 'Inactive';

$lang->system->confirmDelete   = 'Are you sure to delete the ' . $lang->product->system . '?';
$lang->system->confirmActive   = 'Are you sure to online the ' . $lang->product->system . '?';
$lang->system->confirmInactive = 'Are you sure to offline the ' . $lang->product->system . '?';
$lang->system->releaseExist    = 'Associated release ' . $lang->product->system . 's cannot be deleted';
$lang->system->buildExist      = 'Associated build ' . $lang->product->system . 's cannot be deleted';

/* LDAP */
$lang->system->LDAP = new stdclass;
$lang->system->LDAP->info             = 'LDAP information';
$lang->system->LDAP->ldapEnabled      = 'Enable LDAP';
$lang->system->LDAP->ldapQucheng      = 'Built in Qucheng';
$lang->system->LDAP->ldapSource       = 'Source';
$lang->system->LDAP->ldapInstall      = 'Install and Enable';
$lang->system->LDAP->ldapUpdate       = 'Update';
$lang->system->LDAP->accountInfo      = 'Account info';
$lang->system->LDAP->account          = 'Account';
$lang->system->LDAP->password         = 'Password';
$lang->system->LDAP->ldapUsername     = 'Username';
$lang->system->LDAP->ldapName         = 'Name';
$lang->system->LDAP->host             = 'Host';
$lang->system->LDAP->port             = 'Port';
$lang->system->LDAP->account          = 'Account';
$lang->system->LDAP->password         = 'Password';
$lang->system->LDAP->ldapRoot         = 'Root node';
$lang->system->LDAP->filterUser       = 'User filtering';
$lang->system->LDAP->email            = 'Mail Fields';
$lang->system->LDAP->extraAccount     = 'Account Field';
$lang->system->LDAP->ldapAdvance      = 'Advanced setting';
$lang->system->LDAP->updateLDAP       = 'Update LDAP';
$lang->system->LDAP->updateInstance   = 'Update services associated with LDAP';
$lang->system->LDAP->updatingProgress = 'Updating %s services remaining.';

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = 'Built in Qucheng';
$lang->system->ldapTypeList['extra']   = 'External mapping';

/* OSS */
$lang->system->oss = new stdclass;
$lang->system->oss->common    = 'Object Storage';
$lang->system->oss->appURL    = 'Application address';
$lang->system->oss->user      = 'User name';
$lang->system->oss->password  = 'Password';
$lang->system->oss->manage    = 'Manage';
$lang->system->oss->apiURL    = 'API address';
$lang->system->oss->accessKey = 'Access Key';
$lang->system->oss->secretKey = 'Secret Key';

/* SMTP */
$lang->system->SMTP = new stdclass;
$lang->system->SMTP->common   = 'Mailbox configuration';
$lang->system->SMTP->enabled  = 'Enable SMTP';
$lang->system->SMTP->install  = 'Install';
$lang->system->SMTP->update   = 'Update';
$lang->system->SMTP->edit     = 'Edit';
$lang->system->SMTP->editSMTP = 'Edit SMTP';
$lang->system->SMTP->account  = 'Sending email';
$lang->system->SMTP->password = 'Password';
$lang->system->SMTP->host     = 'SMTP Server';
$lang->system->SMTP->port     = 'SMTP Port';
$lang->system->SMTP->save     = 'Save';

/* Domain */
$lang->system->customDomain = 'New domain name';
$lang->system->certPem      = 'Public-key certificate';
$lang->system->certKey      = 'Private-key';

$lang->system->domain = new stdclass;
$lang->system->domain->common        = 'Domain management';
$lang->system->domain->editDomain    = 'Modifying Domain name Configuration';
$lang->system->domain->config        = 'Configure domain name and certificate';
$lang->system->domain->currentDomain = 'Current domain name';
$lang->system->domain->oldDomain     = 'Old domain name';
$lang->system->domain->newDomain     = 'New domain name';
$lang->system->domain->expiredDate   = 'Certificate expiration time';
$lang->system->domain->uploadCert    = 'Upload certificate (only supports pan domain name certificate)';

$lang->system->domain->notReuseOldDomain     = 'The default domain name cannot be changed back after using the custom domain name';
$lang->system->domain->setDNS                = 'It is recommended to perform DNS resolution before modifying the domain name,';
$lang->system->domain->dnsHelperLink         = 'Click to view the help document';
$lang->system->domain->updateInstancesDomain = 'Update the domain name of the installed service';
$lang->system->domain->totalOldDomain        = 'A total of %s.';
$lang->system->domain->updatingProgress      = 'Updating, %s remaining,';
$lang->system->domain->updating              = 'Updating';

$lang->system->SLB = new stdclass;
$lang->system->SLB->common        = 'Load Balance';
$lang->system->SLB->config        = 'Configure load balancing';
$lang->system->SLB->edit          = 'Modify load balancing';
$lang->system->SLB->ipPool        = 'IP segment';
$lang->system->SLB->ipPoolExample = 'Example: 192.168.10.0/24 or 192.168.10.0-192.168.10.100';
$lang->system->SLB->installing    = 'Configuring load balancing';
$lang->system->SLB->leftSeconds   = 'Expected Remaining';
$lang->system->SLB->second        = 'Second';

$lang->system->notices = new stdclass;
$lang->system->notices->success               = 'Success';
$lang->system->notices->fail                  = 'Fail';
$lang->system->notices->attention             = 'Attention';
$lang->system->notices->noLDAP                = 'Unable to find LDAP configuration data';
$lang->system->notices->ldapUsed              = '%s services have been associated with LDAP';
$lang->system->notices->ldapInstallSuccess    = 'LDAP installation successful';
$lang->system->notices->ldapUpdateSuccess     = 'LDAP update successful';
$lang->system->notices->confirmUpdateLDAP     = 'After modifying LDAP, it will automatically update and restart the associated services. Are you sure you want to modify it?';
$lang->system->notices->verifyLDAPSuccess     = 'Verifying LDAP successfully!';
$lang->system->notices->fillAllRequiredFields = 'Please fill in all required items!';
$lang->system->notices->smtpInstallSuccess    = 'LDAP installation successful';
$lang->system->notices->smtpUpdateSuccess     = 'LDAP update successful';
$lang->system->notices->smtpWhiteList         = "To prevent emails from being blocked, please set the sender's email address as a whitelist in the email server";
$lang->system->notices->smtpAuthCode          = 'Some email addresses require separate authorization codes to be filled out. Please refer to the relevant email settings for specific inquiries';
$lang->system->notices->smtpUsed              = '%s services are associated with SMTP';
$lang->system->notices->verifySMTPSuccess     = 'Verification successful!';
$lang->system->notices->pleaseCheckSMTPInfo   = 'Verification failed! Please check if the username and password are correct';
$lang->system->notices->confirmUpdateDomain   = 'After modifying the domain name, the domain name of the installed service will be automatically updated. Are you sure you want to modify it?';
$lang->system->notices->updateDomainSuccess   = 'The domain name was successfully modified.';
$lang->system->notices->configSLBSuccess      = 'Successfully configured load balancing.';
$lang->system->notices->validCert             = 'Verification successful';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB                  = 'The database cannot be found';
$lang->system->errors->notFoundLDAP                = 'Unable to find LDAP data';
$lang->system->errors->dbNameIsEmpty               = 'Database name is empty';
$lang->system->errors->notSupportedLDAP            = 'Currently, this type of LDAP is not supported';
$lang->system->errors->failToInstallLDAP           = 'Installation of built-in LDAP failed';
$lang->system->errors->failToInstallExtraLDAP      = 'Failed to connect to external LDAP';
$lang->system->errors->failToUpdateExtraLDAP       = 'Failed to update external LDAP';
$lang->system->errors->failToUninstallQuChengLDAP  = 'Failed to uninstall channel into internal LDAP';
$lang->system->errors->failToUninstallExtraLDAP    = 'Uninstalling external LDAP failed';
$lang->system->errors->failToDeleteLDAPSnippet     = 'Failed to delete LDAP fragment';
$lang->system->errors->verifyLDAPFailed            = 'Verify LDAP failed';
$lang->system->errors->LDAPLinked                  = 'A service has already been associated with LDAP';
$lang->system->errors->SMTPLinked                  = 'A service has already been associated with an SMTP service';
$lang->system->errors->failGetOssAccount           = 'Failed to obtain the Object storage account';
$lang->system->errors->failToInstallSMTP           = 'Failed to install SMTP';
$lang->system->errors->failToUninstallSMTP         = 'Uninstalling SMTP failed';
$lang->system->errors->failToUpdateSMTP            = 'Failed to update SMTP';
$lang->system->errors->verifySMTPFailed            = 'Verify SMTP failed';
$lang->system->errors->notFoundSMTPApp             = 'Unable to find SMTP proxy application';
$lang->system->errors->notFoundSMTPService         = 'Unable to find SMTP proxy service';
$lang->system->errors->domainIsRequired            = 'Domain name must be filled in';
$lang->system->errors->invalidDomain               = 'Invalid domain name or formatting error. The domain name only allows Minuscule, numbers, dots (.) and middle horizontal lines (-)';
$lang->system->errors->failToUpdateDomain          = 'Failed to update domain name';
$lang->system->errors->forbiddenOriginalDomain     = 'Cannot be modified as the platform default domain name';
$lang->system->errors->newDomainIsSameWithOld      = 'The new domain name cannot be the same as the original domain name';
$lang->system->errors->failedToConfigSLB           = 'Configuration load balancing failed';
$lang->system->errors->wrongIPRange                = 'IP segment format error, please refer to the example format' . $lang->system->SLB->ipPoolExample;
$lang->system->errors->ippoolRequired              = 'IP segment cannot be empty';
$lang->system->errors->failedToInstallSLBComponent = 'Failed to install load balancing component';
$lang->system->errors->tryReinstallSLB             = 'The installation of load balancing components timed out, please try again.';

$lang->system->backup = new stdclass();
$lang->system->backup->common       = 'System backup';
$lang->system->backup->shortCommon  = 'Backup';
$lang->system->backup->systemInfo   = 'System Information';
$lang->system->backup->index        = 'Backup homepage';
$lang->system->backup->history      = 'Backup records';
$lang->system->backup->delete       = 'Delete backup';
$lang->system->backup->backup       = 'Backup';
$lang->system->backup->change       = 'Retention time';
$lang->system->backup->changeAB     = 'modify';
$lang->system->backup->rmPHPHeader  = 'Remove security settings';
$lang->system->backup->setting      = 'Setting';
$lang->system->backup->creator      = 'Backup creator';
$lang->system->backup->type         = 'Backup type';

$lang->system->backup->settingAction = 'Backup Settings';

$lang->system->backup->name           = 'Name';
$lang->system->backup->currentVersion = 'Current version';
$lang->system->backup->latestVersion  = 'Latest Version';

$lang->system->backup->files    = 'Backup files';
$lang->system->backup->allCount = 'Total number of files';
$lang->system->backup->count    = 'Number of backup files';
$lang->system->backup->size     = 'size';
$lang->system->backup->status   = 'Status';
$lang->system->backup->running  = 'Running';
$lang->system->backup->done     = 'Done';

$lang->system->backup->backupName   = 'Backup name:';
$lang->system->backup->backupSql    = 'Backup database:';
$lang->system->backup->backupFile   = 'Backup attachment:';
$lang->system->backup->restoreImage = 'Rollback platform image:';
$lang->system->backup->restoreSQL   = 'Rollback database:';
$lang->system->backup->restoreFile  = 'Rollback attachment:';
$lang->system->backup->checkService = 'Check Service:';

$lang->system->backup->upgrade  = 'Upgrade';
$lang->system->backup->rollback = 'Rollback';
$lang->system->backup->restart  = 'Restart';
$lang->system->backup->delete   = 'Delete';

$lang->system->backup->statusList['pending']       = 'Waiting';
$lang->system->backup->statusList['inprogress']    = 'In progress';
$lang->system->backup->statusList['completed']     = 'Complete';
$lang->system->backup->statusList['failed']        = 'Fail';
$lang->system->backup->statusList['deleting']      = 'Deleting';
$lang->system->backup->statusList['executeFailed'] = 'Execute failed';

$lang->system->backup->restoreProgress['doing'] = 'Doing';
$lang->system->backup->restoreProgress['done']  = 'Done';

$lang->system->backup->typeList['manual']  = 'Manual backup';
$lang->system->backup->typeList['upgrade'] = 'Automatic backup before upgrade';
$lang->system->backup->typeList['restore'] = 'Automatic backup before rollback';

$lang->system->backup->waiting         = 'Backup is in progress, please wait...';
$lang->system->backup->waitingStore    = 'Restoring app data, please wait...';
$lang->system->backup->progress        = 'Backup in progress(%d/%d)';
$lang->system->backup->progressStore   = 'Restoring, progress(%d/%d)';
$lang->system->backup->progressSQL     = 'In backup，%s has been backed up';
$lang->system->backup->progressAttach  = 'There are a total of %s files in the backup, and %s files have already been backed up';
$lang->system->backup->progressCode    = 'There are a total of %s files in the backup, and %s files have already been backed up';
$lang->system->backup->confirmDelete   = 'Do you want to delete the backup';
$lang->system->backup->confirmRestore  = 'A restart is required during the platform restore process, which will cause all your current operations to be interrupted and cannot be restored. Are you sure you want to continue?';
$lang->system->backup->holdDays        = 'Backup has been retained for the last %s days';
$lang->system->backup->copiedFail      = 'Files that failed to copy:';
$lang->system->backup->restoreTip      = 'The restore function only restores the database.';
$lang->system->backup->versionInfo     = 'Click to view the new version introduction';
$lang->system->backup->confirmUpgrade  = 'Users will not be able to use ZenTao during the upgrade, please confirm whether to upgrade?';
$lang->system->backup->confirmBackup   = 'Other users will not be able to use ZenTao during the backup, please confirm whether to backup?';
$lang->system->backup->upgrading       = 'Upgrading';
$lang->system->backup->backupTitle     = 'Backing up the channel platform...';
$lang->system->backup->restoreTitle    = 'Rolling back the channel platform...';
$lang->system->backup->backingUp       = 'In progress';
$lang->system->backup->restoring       = 'In progress';
$lang->system->backup->backupSucceed   = 'Backup succeed';
$lang->system->backup->restoreSucceed  = 'Restore succeed';

$lang->system->backup->success = new stdclass();
$lang->system->backup->success->upgrade = 'Upgrade successful!';
$lang->system->backup->success->degrade = 'Successfully downgraded!';

$lang->system->backup->error = new stdclass();
$lang->system->backup->error->backupFail        = "Backup failed!";
$lang->system->backup->error->restoreFail       = "Restore failed!";
$lang->system->backup->error->upgradeFail       = "Upgrade failed!";
$lang->system->backup->error->upgradeOvertime   = "Upgrade timed out!";
$lang->system->backup->error->degradeFail       = "Downgrade failed!";
$lang->system->backup->error->beenLatestVersion = "It is already the latest version, no upgrade required!";
$lang->system->backup->error->requireVersion    = "Version number must be uploaded!";

$lang->system->maintenance = new stdclass();
$lang->system->maintenance->reason['backup']  = 'The platform is under backup, please visit later';
$lang->system->maintenance->reason['restore'] = 'The platform is under restore, please visit later';
$lang->system->maintenance->reason['upgrade'] = 'The platform is under upgrade, please visit later';

$lang->system->platform = new stdclass();
$lang->system->platform->navs['dblist']     = 'Database';
$lang->system->platform->navs['domainView'] = 'Domain';
$lang->system->platform->navs['ossview']    = 'OSS';
