<?php
$lang->system->dashboard       = 'Monitoring';
$lang->system->systemInfo      = 'System information';
$lang->system->dbManagement    = 'Database management';
$lang->system->ldapManagement  = 'LDAP';
$lang->system->dbList          = 'Databases';
$lang->system->configDomain    = 'Domain Management';
$lang->system->ossView         = 'Object Storage';
$lang->system->dbName          = 'Name';
$lang->system->dbStatus        = 'Status';
$lang->system->dbType          = 'Type';
$lang->system->action          = 'Actions';
$lang->system->management      = 'Manage';
$lang->system->visit           = 'Access';
$lang->system->close           = 'Close';
$lang->system->installLDAP     = 'Install LDAP';
$lang->system->editLDAP        = 'Edit';
$lang->system->LDAPInfo        = 'LDAP information';
$lang->system->accountInfo     = 'Account information';
$lang->system->advance         = 'Advanced';
$lang->system->verify          = 'Verify';
$lang->system->copy            = 'Copy';
$lang->system->copySuccess     = 'Copied to clipboard';
$lang->system->cneStatus       = 'Platform status';
$lang->system->cneStatistic    = 'Resource statistics';
$lang->system->latestDynamic   = 'Latest Updates';
$lang->system->nodeQuantity    = 'Nodes';
$lang->system->serviceQuantity = 'Services';
$lang->system->cpuUsage        = 'CPU (Cores)';
$lang->system->memUsage        = 'Memory (GB)';
$lang->system->name            = ucfirst($lang->product->system) . ' name';
$lang->system->integrated      = 'Integrated ' . $lang->product->system;
$lang->system->latestRelease   = 'Latest version';
$lang->system->children        = 'Included ' . $lang->product->system . 's';
$lang->system->latestRelease   = 'Latest version';
$lang->system->status          = 'Status';
$lang->system->desc            = 'Description';
$lang->system->browse          = ucfirst($lang->product->system) . ' list';
$lang->system->create          = 'Create ' . ucfirst($lang->product->system);
$lang->system->edit            = 'Edit ' . ucfirst($lang->product->system);
$lang->system->delete          = 'Delete ' . ucfirst($lang->product->system);
$lang->system->active          = 'Publish ' . $lang->product->system;
$lang->system->inactive        = 'Unpublish ' . $lang->product->system;
$lang->system->integratedLabel = 'Integration';
$lang->system->backupView      = 'Backup details';

$lang->system->integratedList = array();
$lang->system->integratedList[0] = 'No';
$lang->system->integratedList[1] = 'Yes';

$lang->system->statusList = array();
$lang->system->statusList['active']   = 'Published';
$lang->system->statusList['inactive'] = 'Unpublished';

$lang->system->confirmDelete   = 'Are you sure you want to delete this ' . $lang->product->system . '?';
$lang->system->confirmActive   = 'Are you sure you want to publish this ' . $lang->product->system . '?';
$lang->system->confirmInactive = 'Are you sure you want to unpublish this ' . $lang->product->system . '?';
$lang->system->releaseExist    = 'The ' . $lang->product->system . ' associated with a release cannot be deleted.';
$lang->system->buildExist      = 'The ' . $lang->product->system . ' associated with a build cannot be deleted.';

/* LDAP */
$lang->system->LDAP = new stdclass;
$lang->system->LDAP->info             = 'LDAP information';
$lang->system->LDAP->ldapEnabled      = 'Enable LDAP';
$lang->system->LDAP->ldapQucheng      = 'Built-in Qucheng';
$lang->system->LDAP->ldapSource       = 'Source';
$lang->system->LDAP->ldapInstall      = 'Install and Enable';
$lang->system->LDAP->ldapUpdate       = 'Update';
$lang->system->LDAP->accountInfo      = 'Account Information';
$lang->system->LDAP->account          = 'Account';
$lang->system->LDAP->password         = 'Password';
$lang->system->LDAP->ldapUsername     = 'Username';
$lang->system->LDAP->ldapName         = 'Name';
$lang->system->LDAP->host             = 'Host';
$lang->system->LDAP->port             = 'Port';
$lang->system->LDAP->account          = 'Account';
$lang->system->LDAP->password         = 'Password';
$lang->system->LDAP->ldapRoot         = 'Root node';
$lang->system->LDAP->filterUser       = 'User Filter';
$lang->system->LDAP->email            = 'Email Field';
$lang->system->LDAP->extraAccount     = 'Username Field';
$lang->system->LDAP->ldapAdvance      = 'Advanced Settings';
$lang->system->LDAP->updateLDAP       = 'Update LDAP';
$lang->system->LDAP->updateInstance   = 'Update services associated with LDAP';
$lang->system->LDAP->updatingProgress = 'Updating... %s services remaining.';

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = 'Built-in Qucheng';
$lang->system->ldapTypeList['extra']   = 'External mapping';

/* OSS */
$lang->system->oss = new stdclass;
$lang->system->oss->common    = 'Object Storage';
$lang->system->oss->appURL    = 'App URL';
$lang->system->oss->user      = 'Username';
$lang->system->oss->password  = 'Password';
$lang->system->oss->manage    = 'Manage';
$lang->system->oss->apiURL    = 'API URL';
$lang->system->oss->accessKey = 'Access Key';
$lang->system->oss->secretKey = 'Secret Key';

/* SMTP */
$lang->system->SMTP = new stdclass;
$lang->system->SMTP->common   = 'Email Configuration';
$lang->system->SMTP->enabled  = 'Enable SMTP';
$lang->system->SMTP->install  = 'Install';
$lang->system->SMTP->update   = 'Update';
$lang->system->SMTP->edit     = 'Edit';
$lang->system->SMTP->editSMTP = 'Edit SMTP';
$lang->system->SMTP->account  = 'Sender Email';
$lang->system->SMTP->password = 'Password';
$lang->system->SMTP->host     = 'SMTP Server';
$lang->system->SMTP->port     = 'SMTP Port';
$lang->system->SMTP->save     = 'Save';

/* Domain */
$lang->system->customDomain = 'New domain name';
$lang->system->certPem      = 'Public Key Certificate';
$lang->system->certKey      = 'Private Key';

$lang->system->domain = new stdclass;
$lang->system->domain->common        = 'Domain management';
$lang->system->domain->editDomain    = 'Edit Domain Settings';
$lang->system->domain->config        = 'Configure Domain and Certificate';
$lang->system->domain->currentDomain = 'Current Domain';
$lang->system->domain->oldDomain     = 'Previous Domain';
$lang->system->domain->newDomain     = 'New Domain';
$lang->system->domain->expiredDate   = 'Certificate Expiration Date';
$lang->system->domain->uploadCert    = 'Upload Certificate (Wildcard certificates only)';

$lang->system->domain->notReuseOldDomain     = 'Cannot revert to the default domain after using a custom domain.';
$lang->system->domain->setDNS                = 'Please configure DNS resolution before modifying the domain.';
$lang->system->domain->dnsHelperLink         = 'View Help Documentation';
$lang->system->domain->updateInstancesDomain = 'Update Domain for Installed Services';
$lang->system->domain->totalOldDomain        = 'Total: %s.';
$lang->system->domain->updatingProgress      = 'Updating... %s remaining.';
$lang->system->domain->updating              = 'Updating...';

$lang->system->SLB = new stdclass;
$lang->system->SLB->common        = 'Load Balancing';
$lang->system->SLB->config        = 'Configure load balancing';
$lang->system->SLB->edit          = 'Edit Load Balancing';
$lang->system->SLB->ipPool        = 'IP Range';
$lang->system->SLB->ipPoolExample = 'Example: 192.168.10.0/24 or 192.168.10.0-192.168.10.100';
$lang->system->SLB->installing    = 'Configuring Load Balancing...';
$lang->system->SLB->leftSeconds   = 'Estimated Time Remaining';
$lang->system->SLB->second        = 'Seconds';

$lang->system->notices = new stdclass;
$lang->system->notices->success               = 'Success';
$lang->system->notices->fail                  = 'Failed';
$lang->system->notices->attention             = 'Note';
$lang->system->notices->noLDAP                = 'LDAP configuration data not found.';
$lang->system->notices->ldapUsed              = '%s services are associated with LDAP.';
$lang->system->notices->ldapInstallSuccess    = 'LDAP installed successfully.';
$lang->system->notices->ldapUpdateSuccess     = 'LDAP updated successfully.';
$lang->system->notices->confirmUpdateLDAP     = 'Modifying LDAP will automatically update and restart associated services. Are you sure you want to proceed?';
$lang->system->notices->verifyLDAPSuccess     = 'LDAP verification successful!';
$lang->system->notices->fillAllRequiredFields = 'Please fill in all required fields.';
$lang->system->notices->smtpInstallSuccess    = 'SMTP installed successfully.';
$lang->system->notices->smtpUpdateSuccess     = 'SMTP updated successfully.';
$lang->system->notices->smtpWhiteList         = "To prevent emails from being blocked, please add the sender's email to your server's whitelist.";
$lang->system->notices->smtpAuthCode          = 'Some email providers require an app-specific password. Please check your provider\'s settings.';
$lang->system->notices->smtpUsed              = '%s services are associated with SMTP.';
$lang->system->notices->verifySMTPSuccess     = 'Verification successful!';
$lang->system->notices->pleaseCheckSMTPInfo   = 'Verification failed! Please check your username and password.';
$lang->system->notices->confirmUpdateDomain   = 'Modifying the domain will automatically update it for all installed services. Are you sure you want to proceed?';
$lang->system->notices->updateDomainSuccess   = 'Domain updated successfully.';
$lang->system->notices->configSLBSuccess      = 'Load balancing configured successfully.';
$lang->system->notices->validCert             = 'Verification successful.';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB                  = 'Database not found.';
$lang->system->errors->notFoundLDAP                = 'LDAP data not found.';
$lang->system->errors->dbNameIsEmpty               = 'Database name cannot be empty.';
$lang->system->errors->notSupportedLDAP            = 'This LDAP type is currently not supported.';
$lang->system->errors->failToInstallLDAP           = 'Failed to install built-in LDAP.';
$lang->system->errors->failToInstallExtraLDAP      = 'Failed to connect to external LDAP.';
$lang->system->errors->failToUpdateExtraLDAP       = 'Failed to update external LDAP.';
$lang->system->errors->failToUninstallQuChengLDAP  = 'Failed to uninstall built-in Qucheng LDAP.';
$lang->system->errors->failToUninstallExtraLDAP    = 'Failed to uninstall external LDAP.';
$lang->system->errors->failToDeleteLDAPSnippet     = 'Failed to delete LDAP snippet.';
$lang->system->errors->verifyLDAPFailed            = 'LDAP verification failed.';
$lang->system->errors->LDAPLinked                  = 'A service is already associated with LDAP.';
$lang->system->errors->SMTPLinked                  = 'A service is already associated with SMTP.';
$lang->system->errors->failGetOssAccount           = 'Failed to retrieve Object Storage account.';
$lang->system->errors->failToInstallSMTP           = 'Failed to install SMTP.';
$lang->system->errors->failToUninstallSMTP         = 'Failed to uninstall SMTP.';
$lang->system->errors->failToUpdateSMTP            = 'Failed to update SMTP.';
$lang->system->errors->verifySMTPFailed            = 'SMTP verification failed.';
$lang->system->errors->notFoundSMTPApp             = 'SMTP proxy application not found.';
$lang->system->errors->notFoundSMTPService         = 'SMTP proxy service not found.';
$lang->system->errors->domainIsRequired            = 'Domain is required.';
$lang->system->errors->invalidDomain               = 'Invalid domain format. Only lowercase letters, numbers, dots (.), and hyphens (-) are allowed.';
$lang->system->errors->failToUpdateDomain          = 'Failed to update domain.';
$lang->system->errors->forbiddenOriginalDomain     = 'Cannot be changed to the platform\'s default domain.';
$lang->system->errors->newDomainIsSameWithOld      = 'The new domain cannot be the same as the current one.';
$lang->system->errors->failedToConfigSLB           = 'Failed to configure load balancing.';
$lang->system->errors->wrongIPRange                = 'Invalid IP range format. Please refer to the example: ' . $lang->system->SLB->ipPoolExample;
$lang->system->errors->ippoolRequired              = 'IP range is required.';
$lang->system->errors->failedToInstallSLBComponent = 'Failed to install the load balancing component.';
$lang->system->errors->tryReinstallSLB             = 'Load balancing component installation timed out. Please try again.';

$lang->system->backup = new stdclass();
$lang->system->backup->common       = 'System backup';
$lang->system->backup->shortCommon  = 'Backup';
$lang->system->backup->systemInfo   = 'System Information';
$lang->system->backup->index        = 'Backup Overview';
$lang->system->backup->history      = 'Backup History';
$lang->system->backup->delete       = 'Delete backup';
$lang->system->backup->backup       = 'Backup';
$lang->system->backup->change       = 'Retention Period';
$lang->system->backup->changeAB     = 'Edit';
$lang->system->backup->rmPHPHeader  = 'Remove security settings';
$lang->system->backup->setting      = 'Settings';
$lang->system->backup->creator      = 'Creator';
$lang->system->backup->type         = 'Backup type';

$lang->system->backup->settingAction = 'Backup Settings';

$lang->system->backup->name           = 'Name';
$lang->system->backup->currentVersion = 'Current version';
$lang->system->backup->latestVersion  = 'Latest Version';

$lang->system->backup->files    = 'Backup files';
$lang->system->backup->allCount = 'Total Files';
$lang->system->backup->count    = 'Backup File Count';
$lang->system->backup->size     = 'size';
$lang->system->backup->status   = 'Status';
$lang->system->backup->running  = 'Running';
$lang->system->backup->done     = 'Completed';

$lang->system->backup->backupName   = 'Backup name:';
$lang->system->backup->backupSql    = 'Backup database:';
$lang->system->backup->backupFile   = 'Backup Attachments:';
$lang->system->backup->restoreImage = 'Rollback platform image:';
$lang->system->backup->restoreSQL   = 'Rollback database:';
$lang->system->backup->restoreFile  = 'Rollback Attachments:';
$lang->system->backup->checkService = 'Check Services:';

$lang->system->backup->upgrade  = 'Upgrade';
$lang->system->backup->rollback = 'Rollback';
$lang->system->backup->restart  = 'Restart';
$lang->system->backup->delete   = 'Delete';

$lang->system->backup->statusList['pending']       = 'Pending';
$lang->system->backup->statusList['inprogress']    = 'In progress';
$lang->system->backup->statusList['completed']     = 'Completed';
$lang->system->backup->statusList['failed']        = 'Failed';
$lang->system->backup->statusList['deleting']      = 'Deleting';
$lang->system->backup->statusList['executeFailed'] = 'Execution Failed';

$lang->system->backup->restoreProgress['doing'] = 'In Progress';
$lang->system->backup->restoreProgress['done']  = 'Completed';

$lang->system->backup->typeList['manual']  = 'Manual backup';
$lang->system->backup->typeList['upgrade'] = 'Automatic backup before upgrade';
$lang->system->backup->typeList['restore'] = 'Automatic Backup Before Restore';

$lang->system->backup->waiting         = 'Backup is in progress. Please wait...';
$lang->system->backup->waitingStore    = 'Restoring app data. Please wait...';
$lang->system->backup->progress        = 'Backing up... Progress: %d/%d';
$lang->system->backup->progressStore   = 'Restoring... Progress: %d/%d';
$lang->system->backup->progressSQL     = 'Backing up... %s backed up.';
$lang->system->backup->progressAttach  = 'Backing up... %s of %s files backed up.';
$lang->system->backup->progressCode    = 'Backing up code... %s of %s files backed up.';
$lang->system->backup->confirmDelete   = 'Are you sure you want to delete this backup?';
$lang->system->backup->confirmRestore  = 'The platform will restart during the restore process, interrupting all current operations. Are you sure you want to proceed?';
$lang->system->backup->holdDays        = 'Backups are retained for the last %s days.';
$lang->system->backup->copiedFail      = 'Files that failed to copy:';
$lang->system->backup->restoreTip      = 'Note: The restore function only applies to the database.';
$lang->system->backup->versionInfo     = 'View New Version Details';
$lang->system->backup->confirmUpgrade  = 'ZenTao will be unavailable during the upgrade. Are you sure you want to proceed?';
$lang->system->backup->confirmBackup   = 'ZenTao will be unavailable to regular users during the backup. Are you sure you want to start the backup?';
$lang->system->backup->upgrading       = 'Upgrading...';
$lang->system->backup->backupTitle     = 'Backing up Qucheng Platform...';
$lang->system->backup->restoreTitle    = 'Rolling back Qucheng Platform...';
$lang->system->backup->backingUp       = 'In progress';
$lang->system->backup->restoring       = 'In progress';
$lang->system->backup->backupSucceed   = 'Backup completed successfully.';
$lang->system->backup->restoreSucceed  = 'Restore completed successfully.';

$lang->system->backup->success = new stdclass();
$lang->system->backup->success->upgrade = 'Upgrade completed successfully!';
$lang->system->backup->success->degrade = 'Downgrade completed successfully!';

$lang->system->backup->error = new stdclass();
$lang->system->backup->error->backupFail        = "Backup failed.";
$lang->system->backup->error->restoreFail       = "Restore failed.";
$lang->system->backup->error->upgradeFail       = "Upgrade failed.";
$lang->system->backup->error->upgradeOvertime   = "Upgrade timed out.";
$lang->system->backup->error->degradeFail       = "Downgrade failed.";
$lang->system->backup->error->beenLatestVersion = "You are already on the latest version. No upgrade is needed.";
$lang->system->backup->error->requireVersion    = "Version number is required.";
$lang->system->backup->error->backupFailNotice  = "Backup failed. Reason: %s";

$lang->system->backup->backupTypeList = array();
$lang->system->backup->backupTypeList['db']     = 'Database';
$lang->system->backup->backupTypeList['volume'] = 'Data Volume';

$lang->system->maintenance = new stdclass();
$lang->system->maintenance->reason['backup']  = 'The platform is currently backing up. Please check back later.';
$lang->system->maintenance->reason['restore'] = 'The platform is currently being restored. Please check back later.';
$lang->system->maintenance->reason['upgrade'] = 'The platform is currently upgrading. Please check back later.';

$lang->system->platform = new stdclass();
$lang->system->platform->navs['dblist']     = 'Databases';
$lang->system->platform->navs['domainView'] = 'Domains';
$lang->system->platform->navs['ossview']    = 'Object Storage';

$lang->system->runningStatus['normal'] = 'Normal';
$lang->system->runningStatus['error']  = 'Error';

$lang->system->serviceNotice = 'Only services installed from the Marketplace are included in the statistics. Manually configured services are excluded.';
$lang->system->nodeNotice    = 'Node (%s) Error Reason: %s';
