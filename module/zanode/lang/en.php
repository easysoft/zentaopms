<?php
$lang->zanode->common          = 'Execution Node';
$lang->zanode->instruction     = 'Instructions';
$lang->zanode->browse          = 'Execution Node List';
$lang->zanode->nodeList        = 'Execution Node List';
$lang->zanode->create          = 'Create Execution Node';
$lang->zanode->edit            = 'Edit Execution Node';
$lang->zanode->editAction      = 'Edit Execution Node';
$lang->zanode->view            = 'Execution Node Details';
$lang->zanode->initTitle       = 'Initialize Execution Node';
$lang->zanode->suspend         = 'Suspend Execution Node';
$lang->zanode->destroy         = 'Destroy Execution Node';
$lang->zanode->boot            = 'Start Execution Node';
$lang->zanode->reboot          = 'Restart Execution Node';
$lang->zanode->shutdown        = 'Shut Down Execution Node';
$lang->zanode->resume          = 'Resume Execution Node';
$lang->zanode->suspendNode     = 'Suspend';
$lang->zanode->bootNode        = 'Start';
$lang->zanode->rebootNode      = 'Restart';
$lang->zanode->shutdownNode    = 'Shut Down';
$lang->zanode->resumeNode      = 'Resume';
$lang->zanode->getVNC          = 'Remote Console';
$lang->zanode->all             = 'All';
$lang->zanode->byQuery         = 'Search';
$lang->zanode->osName          = 'Operating System';
$lang->zanode->osNamePhysics   = 'Operating System';
$lang->zanode->image           = 'Image';
$lang->zanode->imageName       = 'Image Name';
$lang->zanode->name            = 'Name';
$lang->zanode->start           = 'Start Automatically After Creation';
$lang->zanode->hostName        = 'Associated Host';
$lang->zanode->host            = $lang->zanode->hostName;
$lang->zanode->extranet        = 'IP/Domain';
$lang->zanode->sshCommand      = 'SSH Command';
$lang->zanode->sshAddress      = 'SSH Address';
$lang->zanode->osArch          = 'Architecture';
$lang->zanode->cpuCores        = 'CPU';
$lang->zanode->defaultUser     = 'Default Username';
$lang->zanode->defaultPwd      = 'Default Password';
$lang->zanode->memory          = 'Memory';
$lang->zanode->diskSize        = 'Disk Capacity';
$lang->zanode->desc            = 'Description';
$lang->zanode->status          = 'Status';
$lang->zanode->mac             = 'MAC Address';
$lang->zanode->vnc             = 'VNC Port';
$lang->zanode->destroyAt       = 'Destroyed At';
$lang->zanode->creater         = 'Creator';
$lang->zanode->createdDate     = 'Created At';
$lang->zanode->confirmDelete   = "Are you sure you want to destroy this execution node?";
$lang->zanode->confirmBoot     = "Are you sure you want to start this execution node?";
$lang->zanode->confirmReboot   = "Are you sure you want to restart this execution node?";
$lang->zanode->confirmShutdown = "Are you sure you want to shut down this execution node?";
$lang->zanode->confirmSuspend  = "Are you sure you want to suspend this execution node?";
$lang->zanode->confirmResume   = "Are you sure you want to resume this execution node?";
$lang->zanode->confirmRestore  = "This execution node will be restored to this snapshot. Are you sure you want to proceed?";
$lang->zanode->actionSuccess   = 'Operation successful';
$lang->zanode->deleted         = "Deleted";
$lang->zanode->scriptPath      = "Script path";
$lang->zanode->syncToZentao    = "Sync Script Info to ZenTao";
$lang->zanode->shell           = "Shell Command";
$lang->zanode->automation      = "Automation Settings";
$lang->zanode->install         = "Install";
$lang->zanode->reinstall       = "Reinstall";
$lang->zanode->copy            = 'Copy';
$lang->zanode->copied          = 'Copied';
$lang->zanode->manual          = 'Manual';
$lang->zanode->initializing    = 'Initializing...';
$lang->zanode->showPwd         = 'Show Password';
$lang->zanode->hidePwd         = 'Hide Password';
$lang->zanode->baseInfo        = 'Basic information';
$lang->zanode->cpuUnit         = 'Cores';
$lang->zanode->IP              = 'IP/Domain';

$lang->zanode->typeList['node']    = 'Virtual machine';
$lang->zanode->typeList['physics'] = 'Physical machine';

$lang->automation = new stdClass();
$lang->automation->scriptPath = $lang->zanode->scriptPath;
$lang->automation->node       = $lang->zanode->common;

$lang->zanode->notFoundAgent  = 'Agent service not found.';
$lang->zanode->busy           = 'This node is currently %s. Please wait for the operation to complete.';
$lang->zanode->createVmFail   = 'Failed to create the execution node.';
$lang->zanode->noVncPort      = 'Failed to retrieve the execution node port.';
$lang->zanode->nameValid      = "The name can only contain letters, numbers, hyphens (-), underscores (_), and dots (.), and cannot start with a symbol.";
$lang->zanode->empty          = 'No execution nodes found.';
$lang->zanode->runCaseConfirm = 'The system detected an automation script for the selected test case. Do you want to execute it automatically?';
$lang->zanode->netError       = 'Unable to connect to the physical machine. Please check your network and try again.';

$lang->zanode->createImage        = 'Export Image';
$lang->zanode->createImaging      = 'Exporting Image...';
$lang->zanode->pending            = 'Pending Image Export';
$lang->zanode->createImageNotice  = 'The system will export an image based on the current execution node. This process requires the node to be shut down. Are you sure you want to proceed?';
$lang->zanode->createImageSuccess = 'Image exported successfully. You can use this image to create execution nodes.';
$lang->zanode->createImageFail    = 'Failed to export image';
$lang->zanode->createImageButton  = 'Create image';

$lang->zanode->snapshotName          = 'Snapshot Name';
$lang->zanode->browseSnapshot        = 'Snapshot List';
$lang->zanode->createSnapshot        = 'Create Snapshot';
$lang->zanode->editSnapshot          = 'Edit Snapshot';
$lang->zanode->restoreSnapshot       = 'Restore to this snapshot';
$lang->zanode->deleteSnapshot        = 'Delete Snapshot';
$lang->zanode->snapshotEmpty         = 'No snapshots found';
$lang->zanode->confirmDeleteSnapshot = "Snapshots cannot be recovered from the recycle bin once deleted. Are you sure you want to proceed?";

$lang->zanode->snapshot = new stdClass();
$lang->zanode->snapshot->statusList['creating']          = 'Creating';
$lang->zanode->snapshot->statusList['inprogress']        = 'Creating';
$lang->zanode->snapshot->statusList['completed']         = 'Ready';
$lang->zanode->snapshot->statusList['failed']            = 'Creation Failed';
$lang->zanode->snapshot->statusList['restoring']         = 'Restoring';
$lang->zanode->snapshot->statusList['restore_failed']    = 'Restore Failed';
$lang->zanode->snapshot->statusList['restore_completed'] = 'Ready';

$lang->zanode->snapshot->defaultSnapName = 'Initial Snapshot';
$lang->zanode->snapshot->defaultSnapUser = 'System';

$lang->zanode->imageNameEmpty  = 'Name cannot be empty.';
$lang->zanode->snapStatusError = 'Snapshot is not available.';
$lang->zanode->snapRestoring   = 'Restoring snapshot';

$lang->zanode->runTimeout = 'Automated execution failed. Please check the status of the host and execution node.';

$lang->zanode->apiError['-10100']     = 'Execution node not found.';
$lang->zanode->apiError['fail']       = 'Execution failed. Please check the status of the host and execution node.';
$lang->zanode->apiError['notRunning'] = 'Please check the execution node status.';

$lang->zanode->publicList[0] = 'Private';
$lang->zanode->publicList[1] = 'Public';

$lang->zanode->statusList['created']      = 'Created';
$lang->zanode->statusList['launch']       = 'Starting';
$lang->zanode->statusList['ready']        = 'Ready';
$lang->zanode->statusList['running']      = 'Running';
$lang->zanode->statusList['suspend']      = 'Suspended';
$lang->zanode->statusList['offline']      = 'Offline';
$lang->zanode->statusList['destroy']      = 'Destroyed';
$lang->zanode->statusList['shutoff']      = 'Shut Down';
$lang->zanode->statusList['destroy_fail'] = 'Destruction Failed';
$lang->zanode->statusList['wait']         = 'Initializing...';
$lang->zanode->statusList['online']       = 'Online';
$lang->zanode->statusList['restoring']    = 'Restoring';
$lang->zanode->statusList['creating_snap'] = 'Creating Snapshot';
$lang->zanode->statusList['creating_img']  = 'Exporting Image';

$lang->zanode->initNotice = "Saved successfully. Please initialize the execution node or return to the list.";
$lang->zanode->initButton = "Initialize";

$lang->zanode->init = new stdClass();
$lang->zanode->init->statusTitle   = "Service Status";
$lang->zanode->init->checkStatus   = "Check Service Status";
$lang->zanode->init->not_install   = "Not installed";
$lang->zanode->init->unknown       = "Unknown";
$lang->zanode->init->not_available = "Installed, but Not Started";
$lang->zanode->init->ready         = "Ready";
$lang->zanode->init->next          = "Next";
$lang->zanode->init->button        = "Go To Settings";

$lang->zanode->init->initSuccessNoticeTitle  = "The service is ready. Two more steps are required to run automated tests on the execution node:<br/>1. Configure the automated testing environment according to the %s. <br/>2. Proceed to %s.";
$lang->zanode->init->initFailNotice          = "Service is not ready. Please execute the installation command on the execution node. <a href='https://github.com/easysoft/zenagent/' target='_blank'>View Help</a>";
$lang->zanode->init->initFailNoticeOnPhysics = "The service is not installed. Please check the service status after executing the following command on the execution node. <a href='https://github.com/easysoft/zenagent/' target='_blank'>View Help</a>";

$lang->zanode->init->serviceStatus = array(
    "ZenAgent" => 'not_install',
    "ZTF"      => 'not_install',
);
$lang->zanode->init->title          = "Initialize Execution Node";
$lang->zanode->init->descTitle      = "Please follow these steps to initialize the execution node:";
$lang->zanode->init->initDesc       = "Execute the command on the execution node: %s %s <br>- Click the Check Service Status button.";

$lang->zanode->tips           = "An execution node is a virtual machine or container instance created by the host. It serves as the test environment for executing test tasks. Once the automated testing environment is configured on the node, scripts can be executed automatically, and the results can be viewed in the corresponding ZenTao test case execution results.";
$lang->zanode->scriptTips     = 'Enter the directory path where the automated test scripts are located on the execution node.';
$lang->zanode->shellTips      = 'Before running the automated test script on the execution node, you can execute a custom shell command.';
$lang->zanode->automationTips = "Before executing test tasks on the node, you need to configure the execution node corresponding to the {$lang->productCommon}, the directory of the automated test scripts, and any custom shell commands to execute.
";
$lang->zanode->nameUnique     = $lang->zanode->name . ' already exists';

$lang->zanode->instructionPage = new stdClass();
$lang->zanode->instructionPage->title            = "ZenTao Test Automation Solution";
$lang->zanode->instructionPage->desc             = "The ZenTao test automation solution provides centralized management of test cases, test scripts, script execution, test results, and test environments. It reduces test management costs and improves execution efficiency. This solution helps you easily establish an automated testing system tailored to your current project management and development workflows, minimizing manual testing effort.";
$lang->zanode->instructionPage->imageInstruction = 'Architecture diagram: ';
$lang->zanode->instructionPage->image            = 'static/svg/zanode_instruction_en.svg';
$lang->zanode->instructionPage->concept          = '1. Core Concepts';
$lang->zanode->instructionPage->conceptDesc      = 'Hosts include physical hosts, Kubernetes (K8s) clusters, cloud servers, and cloud container instances, which are mainly used to create virtual machines or container instances. The recommended operating system for the host is Ubuntu or a CentOS LTS release. An execution node is a virtual machine or container instance created by the host, serving as the test environment where tasks are executed.';
$lang->zanode->instructionPage->appIntroduction  = '2. Application Overview';
$lang->zanode->instructionPage->ZAgentDesc       = 'ZAgent is ZenTao\'s open-source automated testing and scheduling platform. By leveraging virtualization technology, it provides users with a distributed and centrally managed test environment.';
$lang->zanode->instructionPage->ZAgentUrl        = 'https://github.com/easysoft/zagent/blob/main/guide/deploy/index.md';
$lang->zanode->instructionPage->ZTFDesc          = "ZTF is ZenTao's open-source automated testing framework, helping users uniformly manage test scripts. ZTF is deeply integrated with ZenTao; each script can be linked to a test case in the system, allowing step and case information to sync seamlessly.";
$lang->zanode->instructionPage->ZTFUrl           = 'https://ztf.im/';
$lang->zanode->instructionPage->KVMDesc          = 'KVM (Kernel-based Virtual Machine) is a full virtualization solution for Linux on x86 hardware containing virtualization extensions (Intel VT or AMD-V).';
$lang->zanode->instructionPage->KVMUrl           = 'https://www.linux-kvm.org/page/Documents';
$lang->zanode->instructionPage->NginxDesc        = 'Nginx is a high-performance HTTP and reverse proxy web server that also provides IMAP/POP3/SMTP services.';
$lang->zanode->instructionPage->NginxUrl         = 'http://nginx.org/en/docs/';
$lang->zanode->instructionPage->noVNCDesc        = 'noVNC is an HTML VNC client JavaScript library and application. It works smoothly on any major web browser, including mobile browsers (iOS and Android).';
$lang->zanode->instructionPage->noVNCUrl         = 'https://novnc.com/info.html';
$lang->zanode->instructionPage->WebsockifyDesc   = 'Websockify只是将WebSockets流量转换为正常的socket流量。Websockify接受WebSockets握手，解析它，然后开始在客户端和目标之间双向转发流量。';
$lang->zanode->instructionPage->WebsockifyUrl    = 'https://github.com/novnc/websockify';
