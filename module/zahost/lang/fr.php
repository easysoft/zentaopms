<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = 'ZAhost';
$lang->zahost->browse         = 'Hôte Liste';
$lang->zahost->create         = 'Ajouter Hôte';
$lang->zahost->view           = 'Hôte Détail';
$lang->zahost->edit           = 'Éditer';
$lang->zahost->editAction     = 'Éditer Hôte';
$lang->zahost->delete         = 'Supprimer';
$lang->zahost->deleteAction   = 'Supprimer Hôte';
$lang->zahost->byQuery        = 'Recherche';
$lang->zahost->all            = 'Tous les hôtes';
$lang->zahost->browseTemplate = 'Template Browse';
$lang->zahost->createTemplate = 'Create Template';
$lang->zahost->editTemplate   = 'Edit Template';
$lang->zahost->deleteTemplate = 'Delete Template';

$lang->zahost->name        = 'Nom';
$lang->zahost->IP          = 'IP/Domain';
$lang->zahost->address     = 'IP';
$lang->zahost->cpu         = 'CPU Cores';
$lang->zahost->memory      = 'Mémoire Espace';
$lang->zahost->disk    = 'Disque Espace';
$lang->zahost->desc        = 'Description';
$lang->zahost->type        = 'Type';
$lang->zahost->status      = 'Status';

$lang->zahost->createdBy    = 'Créé par';
$lang->zahost->createdDate  = 'Date de création';
$lang->zahost->editedBy     = 'Édité par';
$lang->zahost->editedDate   = "Date d'édition";
$lang->zahost->registerDate = "RegisterDate";

$lang->zahost->memorySize = $lang->zahost->memory;
$lang->zahost->cpuCoreNum = $lang->zahost->cpu         ;
$lang->zahost->osType     = 'Os Version';
$lang->zahost->osCategory = 'System';
$lang->zahost->osVersion  = 'Os Version No';
$lang->zahost->osLang     = 'Language';
$lang->zahost->imageName  = 'Image File';

$lang->zahost->initHost = new stdclass;
$lang->zahost->initHost->checkStatus = "Check Service Status";
$lang->zahost->initHost->not_install = "Not installed";
$lang->zahost->initHost->not_available = "Installed, Not Started";
$lang->zahost->initHost->ready = "Ready";
$lang->zahost->initHost->next = "Next";
$lang->zahost->initHost->initSuccessNotice = "The initialization was successful, click Next to complete the next steps.";
$lang->zahost->initHost->initFailNoticeTitle = "Initialization failed, check the init script execution log and try the following two solutions:";
$lang->zahost->initHost->initFailNoticeDesc = "1. Re-execute the script <br/>2. Review the initialization FAQ";
$lang->zahost->initHost->serviceStatus = [
    "kvm" => 'not_install',
    "novnc" => 'not_install',
    "websockify" => 'not_install',
];
$lang->zahost->initHost->title = "Initialize Host";
$lang->zahost->initHost->descTitle = "Follow these steps to complete the initialization on the host:";
$lang->zahost->initHost->descLi = [
    "Download the init script to the host：wget https://pkg-1308438674.cos.ap-shanghai.myqcloud.com/zenagent/zagentenv",
    "Execute the init script on the host and execute the command example under Ubuntu：./zagentenv"
];
$lang->zahost->initHost->statusTitle = "Service Status";

$lang->zahost->vmTemplate = new stdclass;
$lang->zahost->vmTemplate->name       = 'Nom';
$lang->zahost->vmTemplate->common     = 'VM Template';
$lang->zahost->vmTemplate->cpuCoreNum = $lang->zahost->cpu         ;
$lang->zahost->vmTemplate->memorySize = $lang->zahost->memory;
$lang->zahost->vmTemplate->diskSize   = $lang->zahost->disk;
$lang->zahost->vmTemplate->osType     = $lang->zahost->osType;
$lang->zahost->vmTemplate->osCategory = $lang->zahost->osCategory;
$lang->zahost->vmTemplate->osVersion  = $lang->zahost->osVersion;
$lang->zahost->vmTemplate->osLang     = $lang->zahost->osLang;
$lang->zahost->vmTemplate->imageName  = $lang->zahost->imageName;

$lang->zahost->langList = array();
$lang->zahost->langList['zh_cn'] = 'Simplified Chinese';
$lang->zahost->langList['zh_tw'] = 'Traditional Chinese';
$lang->zahost->langList['en_us'] = 'American English';

$lang->zahost->empty         = 'No Host';
$lang->zahost->templateEmpty = 'No Template';

$lang->zahost->statusList['ready']  = 'Ready';
$lang->zahost->statusList['online'] = 'Online';

$lang->zahost->virtualSoftware = 'VM Software';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->zaHostType                 = 'Type Hôte';
$lang->zahost->zaHostTypeList['physical'] = 'Physique';

$lang->zahost->confirmDelete           = 'Voulez-vous supprimer cet hôte?';
$lang->zahost->confirmDeleteVMTemplate = 'Do you want to delete this VM template？';

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
$lang->zahost->notice->ip              = '『%s』format incorrect!';
$lang->zahost->notice->registerCommand = 'Register command: ./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = 'loading...';
$lang->zahost->notice->noImage         = 'No available image';
