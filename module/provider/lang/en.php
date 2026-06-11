<?php
$lang->provider->browse = 'Browse Providers';
$lang->provider->create = 'Create Provider';
$lang->provider->edit   = 'Edit Provider';
$lang->provider->delete = 'Delete Provider';

$lang->provider->browseAction = 'Browse Providers';
$lang->provider->createAction = 'Create Provider';
$lang->provider->editAction   = 'Edit Provider';
$lang->provider->deleteAction = 'Delete Provider';

$lang->provider->name        = 'Name';
$lang->provider->type        = 'Type';
$lang->provider->url         = 'URL';
$lang->provider->token       = 'Token';
$lang->provider->account     = 'Account';
$lang->provider->createdBy   = 'Created By';
$lang->provider->createdDate = 'Created Date';

$lang->provider->error = new stdclass();
$lang->provider->error->api            = 'The server URL cannot reach the provider API.';
$lang->provider->error->apiWithMessage = 'The server URL cannot reach the provider API: %s';

$lang->provider->typeList = array();
$lang->provider->typeList['GitLab']     = 'GitLab';
$lang->provider->typeList['GitHub']     = 'Github';
$lang->provider->typeList['Gitea']      = 'Gitea';
$lang->provider->typeList['Gogs']       = 'Gogs';
$lang->provider->typeList['Subversion'] = 'Subversion';
$lang->provider->typeList['Jenkins']    = 'Jenkins';

$lang->provider->notice = new stdclass();
$lang->provider->notice->confirmDelete = 'Are you sure to delete this provider?';
$lang->provider->notice->emptyProvider = 'No providers.';
