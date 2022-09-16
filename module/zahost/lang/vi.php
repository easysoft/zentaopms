<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = 'ZAhost';
$lang->zahost->browse         = 'Host List';
$lang->zahost->create         = 'Add Host';
$lang->zahost->view           = 'Host View';
$lang->zahost->edit           = 'Edit';
$lang->zahost->editAction     = 'Edit Host';
$lang->zahost->delete         = 'Delete';
$lang->zahost->deleteAction   = 'Delete Host';
$lang->zahost->byQuery        = 'Search';
$lang->zahost->all            = 'All';
$lang->zahost->browseTemplate = 'Template Browse';
$lang->zahost->createTemplate = 'Create Template';

$lang->zahost->name        = 'Name';
$lang->zahost->IP          = 'IP';
$lang->zahost->publicIP    = 'IP';
$lang->zahost->cpuCores    = 'CPU Cores';
$lang->zahost->memory      = 'Memory Size';
$lang->zahost->diskSize    = 'Disk Size';
$lang->zahost->instanceNum = 'Instance Number';
$lang->zahost->type        = 'Type';
$lang->zahost->status      = 'Status';

$lang->zahost->createdBy    = 'CreatedBy';
$lang->zahost->createdDate  = 'CreatedDate';
$lang->zahost->editedBy     = 'EditedBy';
$lang->zahost->editedDate   = 'EditedDate';
$lang->zahost->registerDate = 'RegisterDate';

$lang->zahost->memorySize = $lang->zahost->memory;
$lang->zahost->cpuCoreNum = $lang->zahost->cpuCores;
$lang->zahost->osType     = 'Os Version';
$lang->zahost->osCategory = 'System';
$lang->zahost->osVersion  = 'Os Version No';
$lang->zahost->osLang     = 'Language';
$lang->zahost->imageName  = 'Image File';

$lang->zahost->vmTemplate = new stdclass;
$lang->zahost->vmTemplate->name       = 'Name';
$lang->zahost->vmTemplate->common     = 'VM Template';
$lang->zahost->vmTemplate->cpuCoreNum = $lang->zahost->cpuCores;
$lang->zahost->vmTemplate->memorySize = $lang->zahost->memory;
$lang->zahost->vmTemplate->diskSize   = $lang->zahost->diskSize;
$lang->zahost->vmTemplate->osType     = $lang->zahost->osType;
$lang->zahost->vmTemplate->osCategory = $lang->zahost->osCategory;
$lang->zahost->vmTemplate->osVersion  = $lang->zahost->osVersion;
$lang->zahost->vmTemplate->osLang     = $lang->zahost->osLang;
$lang->zahost->vmTemplate->imageName  = $lang->zahost->imageName;

$lang->zahost->empty         = 'No Host';
$lang->zahost->templateEmpty = 'No Template';

$lang->zahost->langList = array();
$lang->zahost->langList['zh_cn'] = 'Simplified Chinese';
$lang->zahost->langList['zh_tw'] = 'Traditional Chinese';
$lang->zahost->langList['en_us'] = 'American English';

$lang->zahost->statusList['online'] = 'Online';

$lang->zahost->virtualSoftware = 'VM Software';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->zaHostType                 = 'Type';
$lang->zahost->zaHostTypeList['physical'] = 'Physical';
//$lang->zahost->zaHostTypeList['virtual']  = '虚拟主机';

$lang->zahost->confirmDelete = 'Do you want to delete this host?';

$lang->zahost->versionList = array();
$lang->zahost->versionList['winxp']['all']          = 'Windows XP';
$lang->zahost->versionList['win7']['home']          = 'Home Basic';
$lang->zahost->versionList['win7']['professional']  = 'Professional';
$lang->zahost->versionList['win7']['enterprise']    = 'Enterprise';
$lang->zahost->versionList['win7']['ultimate']      = 'Ultimate';
$lang->zahost->versionList['win10']['home']         = 'Home Basic';
$lang->zahost->versionList['win10']['professional'] = 'Professional';
$lang->zahost->versionList['win10']['enterprise']   = 'Enterprise';
$lang->zahost->versionList['win10']['ultimate']     = 'Ultimate';
$lang->zahost->versionList['win11']['home']         = 'Home Basic';
$lang->zahost->versionList['win11']['professional'] = 'Professional';
$lang->zahost->versionList['win11']['enterprise']   = 'Enterprise';
$lang->zahost->versionList['win11']['ultimate']     = 'Ultimate';
$lang->zahost->versionList['winServer']['2008']     = '2008';
$lang->zahost->versionList['winServer']['2012']     = '2012';
$lang->zahost->versionList['winServer']['2016']     = '2016';
$lang->zahost->versionList['winServer']['2019']     = '2019';
$lang->zahost->versionList['debian']['9']           = '9';
$lang->zahost->versionList['debian']['10']          = '10';
$lang->zahost->versionList['debian']['11']          = '11';
$lang->zahost->versionList['ubuntu']['16']          = '16';
$lang->zahost->versionList['ubuntu']['18']          = '18';
$lang->zahost->versionList['ubuntu']['20']          = '20';
$lang->zahost->versionList['centos']['6']           = '6';
$lang->zahost->versionList['centos']['7']           = '7';
$lang->zahost->versionList['centos']['8']           = '8';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = '『%s』incorrect format!';
$lang->zahost->notice->registerCommand = 'Register command: ./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
