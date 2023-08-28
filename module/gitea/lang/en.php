<?php
$lang->gitea->common        = 'Gitea';
$lang->gitea->browse        = 'Gitea Browse';
$lang->gitea->search        = 'Search';
$lang->gitea->create        = 'Create Gitea';
$lang->gitea->edit          = 'Edit Gitea';
$lang->gitea->view          = 'Gitea Details';
$lang->gitea->delete        = 'Delete Gitea';
$lang->gitea->confirmDelete = 'Do you want to delete this Gitea server?';
$lang->gitea->bindUser      = 'Bind User';
$lang->gitea->giteaAvatar   = 'Avatar';
$lang->gitea->giteaAccount  = 'Gitea Account';
$lang->gitea->giteaEmail    = 'Email';
$lang->gitea->zentaoAccount = 'Zentao Account';
$lang->gitea->bindingStatus = 'Binding Status';
$lang->gitea->all           = 'All';
$lang->gitea->notBind       = 'Not bind';
$lang->gitea->binded        = 'Binded';
$lang->gitea->bindDynamic   = '%s and Zentao user %s';
$lang->gitea->bindedError   = 'The bound user has been deleted or modified. Please bind again.';
$lang->gitea->zentaoEmail   = 'Zentao User\'s Email';
$lang->gitea->accountDesc   = '(Automatically match users with the same email)';

$lang->gitea->bindStatus['binded']      = $lang->gitea->binded;
$lang->gitea->bindStatus['notBind']     = "<span class='text-danger'>{$lang->gitea->notBind}</span>";
$lang->gitea->bindStatus['bindedError'] = "<span class='text-danger'>{$lang->gitea->bindedError}</span>";

$lang->gitea->browseAction = 'Gitea List';
$lang->gitea->deleteAction = 'Delete Gitea';

$lang->gitea->id    = 'ID';
$lang->gitea->name  = "Application Name";
$lang->gitea->url   = 'Server URL';
$lang->gitea->token = 'Token';

$lang->gitea->tokenLimit    = "The current token has no admin privilege. Please regenerate one with root user in Gitea.";
$lang->gitea->hostError     = "So the current Gitea server address is invalid, please confirm that the current server can be accessed and try again.";
$lang->gitea->bindUserError = "Can not bind users repeatedly %s";

$lang->gitea->server        = "Server List";
$lang->gitea->lblCreate     = 'Create Gitea Server';
$lang->gitea->emptyError    = " cannot be empty";
$lang->gitea->createSuccess = "Create success";
