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
$lang->zahost->imageFile  = 'Image File';

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
$lang->zahost->vmTemplate->imageFile  = $lang->zahost->imageFile;

$lang->zahost->empty         = 'No Host';
$lang->zahost->templateEmpty = 'No Template';

$lang->zahost->statusList['online'] = 'Online';

$lang->zahost->virtualSoftware = 'VM Software';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->zaHostType                 = 'Type';
$lang->zahost->zaHostTypeList['physical'] = 'Physical';
//$lang->zahost->zaHostTypeList['virtual']  = '虚拟主机';

$lang->zahost->confirmDelete = 'Do you want to delete this host?';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip = '『%s』incorrect format!';
