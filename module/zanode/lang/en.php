<?php
$lang->zanode->common          = 'Execution Node';
$lang->zanode->browse          = 'Execution Node List';
$lang->zanode->create          = 'Create Execution Node';
$lang->zanode->editAction      = 'Edit Execution Node';
$lang->zanode->view            = 'View Execution Node';
$lang->zanode->initTitle       = 'Init ZenAgent Node';
$lang->zanode->suspend         = 'Suspend Execution Node';
$lang->zanode->destroy         = 'Destroy Execution Node';
$lang->zanode->handleVM        = 'Restart Execution Node';
$lang->zanode->boot            = 'Start Execution Node';
$lang->zanode->reboot          = 'Restart Execution Node';
$lang->zanode->shutdown        = 'Shutdown Execution Node';
$lang->zanode->resume          = 'Resume Execution Node';
$lang->zanode->getVNC          = 'Remote management';
$lang->zanode->all             = 'All';
$lang->zanode->byQuery         = 'Search';
$lang->zanode->osName          = 'System';
$lang->zanode->image           = 'VM Image';
$lang->zanode->imageName       = 'Image Name';
$lang->zanode->name            = 'Name';
$lang->zanode->start           = 'Start After Created';
$lang->zanode->hostName        = 'Host Name';
$lang->zanode->host            = $lang->zanode->hostName;
$lang->zanode->extranet        = 'IP/Domain';
$lang->zanode->osArch          = 'Arch';
$lang->zanode->cpuCores        = 'CPU Cores';
$lang->zanode->memory          = 'Memory Size';
$lang->zanode->desc            = 'Description';
$lang->zanode->diskSize        = 'Disk Size';
$lang->zanode->status          = 'Status';
$lang->zanode->mac             = 'MAC';
$lang->zanode->vnc             = 'VNC Port';
$lang->zanode->destroyAt       = 'Destroy Time';
$lang->zanode->creater         = 'Creator';
$lang->zanode->createdDate     = 'Create Date';
$lang->zanode->confirmDelete   = "Are you sure about destroying the execution node？";
$lang->zanode->confirmBoot     = "Are you sure to start the execution node？";
$lang->zanode->confirmReboot   = "Are you sure to restart the execution node？";
$lang->zanode->confirmShutdown = "Are you sure to shutdown the execution node？";
$lang->zanode->confirmSuspend  = "Are you sure to suspend the execution node？";
$lang->zanode->confirmResume   = "Are you sure to resume the execution node？";
$lang->zanode->actionSuccess   = 'Success';
$lang->zanode->deleted         = "Deleted";
$lang->zanode->scriptPath      = "Script path";
$lang->zanode->shell           = "Shell";
$lang->zanode->automation      = "Automation";

$lang->automation = new stdClass();
$lang->automation->path = $lang->zanode->scriptPath;
$lang->automation->node = $lang->zanode->common;

$lang->zanode->notFoundAgent = 'No Agent service is found';
$lang->zanode->createVmFail  = 'Failed to create a execution node';
$lang->zanode->noVncPort     = 'Failed to get vnc port';
$lang->zanode->nameValid     = "The name can only be letters, numbers, '-', '_', '.', and cannot start with a symbol";
$lang->zanode->empty         = 'No zanode is found';

$lang->zanode->empty              = 'No ZenAgent Node';
$lang->zanode->createImaging      = 'Creating';
$lang->zanode->createImageNotice  = 'The system will be created based on the current node，This process requires the execution node to be shut down. Do you want to continue?';
$lang->zanode->createImageSuccess = 'Successed, You can use this image to create node.';
$lang->zanode->createImageFail    = 'Failed to create';
$lang->zanode->createImageButton  = 'Create image';

$lang->zanode->imageNameEmpty = 'Name can not be empty.';

$lang->zanode->apiError['-10100'] = 'Execution node not found.';

$lang->zanode->publicList[0] = 'Private';
$lang->zanode->publicList[1] = 'Public';

$lang->zanode->statusList['created']      = 'Created';
$lang->zanode->statusList['launch']       = 'Launch';
$lang->zanode->statusList['ready']        = 'Ready';
$lang->zanode->statusList['running']      = 'Running';
$lang->zanode->statusList['suspend']      = 'Suspend';
$lang->zanode->statusList['offline']      = 'Offline';
$lang->zanode->statusList['destroy']      = 'Destroyed';
$lang->zanode->statusList['destroy_fail'] = 'Destroy Fail';

$lang->zanode->init = new stdclass;
$lang->zanode->init->checkStatus   = "Check Service Status";
$lang->zanode->init->not_install   = "Not installed";
$lang->zanode->init->not_available = "Installed, Not Started";
$lang->zanode->init->ready         = "Ready";
$lang->zanode->init->next          = "Next";

$lang->zanode->init->initFailNoticeTitle = "Initialization failed, check the init script execution log and try the following two solutions:";
$lang->zanode->init->initFailNoticeDesc  = "1. Re-execute the script <br/>2. Review the initialization FAQ";

$lang->zanode->init->serviceStatus = [
    "ZenAgent" => 'not_install',
    "ZTF"      => 'not_install',
];
$lang->zanode->init->title          = "Initialize Node";
$lang->zanode->init->descTitle      = "Follow these steps to complete the initialization on the node:";
$lang->zanode->init->initDesc       = "Execute the init script on the node: bash <(curl -s -S -L https://pkg.qucheng.com/zenagent/zagent.sh) -s vm ";
$lang->zanode->init->statusTitle    = "Service Status";