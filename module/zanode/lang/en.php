<?php
$lang->zanode->common          = 'ZenAgent Node';
$lang->zanode->browse          = 'ZenAgent Node List';
$lang->zanode->nodeList        = 'ZenAgent Node List';
$lang->zanode->create          = 'Create ZenAgent Node';
$lang->zanode->edit            = 'Edit ZenAgent Node';
$lang->zanode->editAction      = 'Edit ZenAgent Node';
$lang->zanode->view            = 'View ZenAgent Node';
$lang->zanode->initTitle       = 'Init ZenAgent Node';
$lang->zanode->suspend         = 'Suspend ZenAgent Node';
$lang->zanode->destroy         = 'Destroy ZenAgent Node';
$lang->zanode->boot            = 'Start ZenAgent Node';
$lang->zanode->reboot          = 'Restart ZenAgent Node';
$lang->zanode->shutdown        = 'Shutdown ZenAgent Node';
$lang->zanode->resume          = 'Resume ZenAgent Node';
$lang->zanode->suspendNode     = 'Suspend';
$lang->zanode->bootNode        = 'Boot';
$lang->zanode->rebootNode      = 'Reboot';
$lang->zanode->shutdownNode    = 'Shutdown';
$lang->zanode->resumeNode      = 'Resume';
$lang->zanode->getVNC          = 'Remote management';
$lang->zanode->all             = 'All';
$lang->zanode->byQuery         = 'Search';
$lang->zanode->osName          = 'System';
$lang->zanode->osNamePhysics   = 'System';
$lang->zanode->image           = 'VM Image';
$lang->zanode->imageName       = 'Image Name';
$lang->zanode->name            = 'Name';
$lang->zanode->start           = 'Start After Created';
$lang->zanode->hostName        = 'Host Name';
$lang->zanode->host            = $lang->zanode->hostName;
$lang->zanode->extranet        = 'IP/Domain';
$lang->zanode->sshCommand      = 'SSH Command';
$lang->zanode->sshAddress      = 'SSH Address';
$lang->zanode->osArch          = 'Arch';
$lang->zanode->cpuCores        = 'CPU';
$lang->zanode->defaultUser     = 'Default Account Name';
$lang->zanode->defaultPwd      = 'Default Password';
$lang->zanode->memory          = 'Memory Size';
$lang->zanode->diskSize        = 'Disk Size';
$lang->zanode->desc            = 'Description';
$lang->zanode->status          = 'Status';
$lang->zanode->mac             = 'MAC';
$lang->zanode->vnc             = 'VNC Port';
$lang->zanode->destroyAt       = 'Destroy Time';
$lang->zanode->creater         = 'Creator';
$lang->zanode->createdDate     = 'Create Date';
$lang->zanode->confirmDelete   = "Are you sure about destroying the ZenAgent Node?";
$lang->zanode->confirmBoot     = "Are you sure to start the ZenAgent Node?";
$lang->zanode->confirmReboot   = "Are you sure to restart the ZenAgent Node?";
$lang->zanode->confirmShutdown = "Are you sure to shutdown the ZenAgent Node?";
$lang->zanode->confirmSuspend  = "Are you sure to suspend the ZenAgent Node?";
$lang->zanode->confirmResume   = "Are you sure to resume the ZenAgent Node?";
$lang->zanode->confirmRestore  = "The ZenAgent Node will be restored to this snapshot state, are you sure you want to continue?";
$lang->zanode->actionSuccess   = 'Success';
$lang->zanode->deleted         = "Deleted";
$lang->zanode->scriptPath      = "Script path";
$lang->zanode->syncToZentao    = "Sync cases in the directory to Zentao";
$lang->zanode->shell           = "Shell";
$lang->zanode->automation      = "Automation";
$lang->zanode->install         = "Install";
$lang->zanode->reinstall       = "Reinstall";
$lang->zanode->copy            = 'Click to copy';
$lang->zanode->copied          = 'Copy successful';
$lang->zanode->manual          = 'Manual';
$lang->zanode->initializing    = 'Initializing';
$lang->zanode->showPwd         = 'Show Password';
$lang->zanode->hidePwd         = 'Hide Password';
$lang->zanode->baseInfo        = 'Basic information';
$lang->zanode->cpuUnit         = 'CORE';
$lang->zanode->IP              = 'Extranet Address';

$lang->zanode->typeList['node']    = 'Virtual machine';
$lang->zanode->typeList['physics'] = 'Physical machine';

$lang->automation = new stdClass();
$lang->automation->scriptPath = $lang->zanode->scriptPath;
$lang->automation->node       = $lang->zanode->common;

$lang->zanode->notFoundAgent  = 'No Agent service is found';
$lang->zanode->busy           = 'This Node is %s, please wait for the operation to complete.';
$lang->zanode->createVmFail   = 'Failed to create a ZenAgent Node';
$lang->zanode->noVncPort      = 'Failed to get vnc port';
$lang->zanode->nameValid      = "The name can only be letters, numbers, '-', '_', '.', and cannot start with a symbol";
$lang->zanode->empty          = 'No zanode is found';
$lang->zanode->runCaseConfirm = 'The system detects the presence of an automation script. Whether to execute?';
$lang->zanode->netError       = 'Unable to connect to the physical machine, please check the network and try again.';

$lang->zanode->createImage        = 'Create Image';
$lang->zanode->createImaging      = 'Creating';
$lang->zanode->pending            = 'Waiting for create';
$lang->zanode->createImageNotice  = 'The system will be created based on the current node，This process requires the ZenAgent Node to be shut down. Do you want to continue?';
$lang->zanode->createImageSuccess = 'Successed, You can use this image to create node.';
$lang->zanode->createImageFail    = 'Failed to create';
$lang->zanode->createImageButton  = 'Create image';

$lang->zanode->snapshotName          = 'Snapshot Name';
$lang->zanode->browseSnapshot        = 'Snapshot List';
$lang->zanode->createSnapshot        = 'Create Snapshot';
$lang->zanode->editSnapshot          = 'Edit Snapshot';
$lang->zanode->restoreSnapshot       = 'Revert From This Snapshot';
$lang->zanode->deleteSnapshot        = 'Delete Snapshot';
$lang->zanode->snapshotEmpty         = 'No snapshots';
$lang->zanode->confirmDeleteSnapshot = "The snapshot cannot be restored from the recycle bin after being deleted. Are you sure to continue?";

$lang->zanode->snapshot = new stdClass();
$lang->zanode->snapshot->statusList['creating']          = 'Creating';
$lang->zanode->snapshot->statusList['inprogress']        = 'Creating';
$lang->zanode->snapshot->statusList['completed']         = 'Usable';
$lang->zanode->snapshot->statusList['failed']            = 'Create Failed';
$lang->zanode->snapshot->statusList['restoring']         = 'Restoring';
$lang->zanode->snapshot->statusList['restore_failed']    = 'Restore Failed';
$lang->zanode->snapshot->statusList['restore_completed'] = 'Usable';

$lang->zanode->snapshot->defaultSnapName = 'DefaultSnapshot';
$lang->zanode->snapshot->defaultSnapUser = 'System';

$lang->zanode->imageNameEmpty  = 'Name can not be empty.';
$lang->zanode->snapStatusError = 'Snapshot is not ready.';
$lang->zanode->snapRestoring   = 'Snapshot is restoring.';

$lang->zanode->runTimeout = 'Network connection timeout, please check the host and execution node status.';

$lang->zanode->apiError['-10100']     = 'ZenAgent Node not found.';
$lang->zanode->apiError['fail']       = 'Connection timeout, Please check the host and execution node status.';
$lang->zanode->apiError['notRunning'] = 'Please check the execution node status.';

$lang->zanode->publicList[0] = 'Private';
$lang->zanode->publicList[1] = 'Public';

$lang->zanode->statusList['created']      = 'Created';
$lang->zanode->statusList['launch']       = 'Launch';
$lang->zanode->statusList['ready']        = 'Ready';
$lang->zanode->statusList['running']      = 'Running';
$lang->zanode->statusList['suspend']      = 'Suspend';
$lang->zanode->statusList['offline']      = 'Offline';
$lang->zanode->statusList['destroy']      = 'Destroyed';
$lang->zanode->statusList['shutoff']      = 'Shutoff';
$lang->zanode->statusList['shutodown']    = 'shutdown';
$lang->zanode->statusList['destroy_fail'] = 'Destroy Fail';
$lang->zanode->statusList['wait']         = 'Initializing';
$lang->zanode->statusList['online']       = 'Online';
$lang->zanode->statusList['restoring']    = 'Restoring';
$lang->zanode->statusList['creating_snap'] = 'Creating Snapshot';
$lang->zanode->statusList['creating_img']  = 'Creating Image';

$lang->zanode->initNotice = "Succeeded. Please initialize the execution node or return to the list.";
$lang->zanode->initButton = "Initialize";

$lang->zanode->init = new stdClass();
$lang->zanode->init->statusTitle   = "Service Status";
$lang->zanode->init->checkStatus   = "Check Service Status";
$lang->zanode->init->not_install   = "Not installed";
$lang->zanode->init->unknown       = "Unknown";
$lang->zanode->init->not_available = "Installed, Not Started";
$lang->zanode->init->ready         = "Ready";
$lang->zanode->init->next          = "Next";
$lang->zanode->init->button        = "Go To Settings";

$lang->zanode->init->initSuccessNoticeTitle  = "The service is ready, and it takes two steps to execute the automated test on the execution node: <br/>1. Configure the automated test running environment according to the %s<br/>2. Go to %s";
$lang->zanode->init->initFailNotice          = "Fail，Execute the installation service command on the node or <a href='https://github.com/easysoft/zenagent/' target='_blank'>See Help</a>.";
$lang->zanode->init->initFailNoticeOnPhysics = "The service has not been installed yet, please check the service status after executing the following command on the execution node。<a href='https://github.com/easysoft/zenagent/' target='_blank'>See Help</a>";

$lang->zanode->init->serviceStatus = array(
    "ZenAgent" => 'not_install',
    "ZTF"      => 'not_install',
);
$lang->zanode->init->title          = "Initialize Node";
$lang->zanode->init->descTitle      = "Follow these steps to complete the initialization on the node:";
$lang->zanode->init->initDesc       = "Execute the init script on the node: %s %s   <br>- Click check service status button.";

$lang->zanode->tips           = "The execution node is a virtual machine or container instance created by the host machine, which is a test environment for executing test tasks. After the execution node is configured with the automated test environment, the script can be automatically executed, and the results can be viewed in the execution results of Zen Dao's application cases.";
$lang->zanode->scriptTips     = 'Write the directory where the script is located on the execution node.';
$lang->zanode->shellTips      = 'Before running the automated test script on the execution node, you can execute a custom shell command.';
$lang->zanode->automationTips = "Before executing the test task on the execution node, you need to set up the execution node corresponding to the {$lang->productCommon}, the directory of the automated test script, and the custom Shell command to execute.";
$lang->zanode->nameUnique     = $lang->zanode->name . 'already exist';
