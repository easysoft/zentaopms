<?php
$lang->provider->browse = 'Browse Providers';
$lang->provider->create = 'Create Provider';
$lang->provider->edit   = 'Edit Provider';
$lang->provider->delete = 'Delete Provider';

$lang->provider->name    = 'Name';
$lang->provider->type    = 'Type';
$lang->provider->url     = 'URL';
$lang->provider->token   = 'Token';
$lang->provider->account = 'Account';

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
