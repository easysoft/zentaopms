<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = 'ZAhost';
$lang->zahost->browse         = 'Host List';
$lang->zahost->create         = 'Add Host';
$lang->zahost->view           = 'Host View';
$lang->zahost->initTitle      = 'Init Host';
$lang->zahost->edit           = 'Edit';
$lang->zahost->editAction     = 'Edit Host';
$lang->zahost->delete         = 'Delete';
$lang->zahost->cancel         = "Cancel";
$lang->zahost->deleteAction   = 'Delete Host';
$lang->zahost->byQuery        = 'Search';
$lang->zahost->all            = 'All';
$lang->zahost->browseNode     = 'ZAnode Browse';
$lang->zahost->deleted        = "Deleted";
$lang->zahost->copy           = 'Click to copy';
$lang->zahost->copied         = 'Copy successful';
$lang->zahost->baseInfo       = 'Basic information';

$lang->zahost->name        = 'Name';
$lang->zahost->IP          = 'Extranet Address';
$lang->zahost->extranet    = 'Extranet Address';
$lang->zahost->memory      = 'Memory Size';
$lang->zahost->cpuCores    = 'CPU';
$lang->zahost->diskSize    = 'Disk Size';
$lang->zahost->desc        = 'Description';
$lang->zahost->type        = 'Type';
$lang->zahost->status      = 'Status';

$lang->zahost->createdBy    = 'CreatedBy';
$lang->zahost->createdDate  = 'CreatedDate';
$lang->zahost->editedBy     = 'EditedBy';
$lang->zahost->editedDate   = 'EditedDate';
$lang->zahost->registerDate = 'RegisterDate';

$lang->zahost->memorySize    = $lang->zahost->memory;
$lang->zahost->cpuCoreNum    = $lang->zahost->cpuCores;
$lang->zahost->os            = 'System';
$lang->zahost->imageName     = 'Image File';
$lang->zahost->browseImage   = 'Image List';
$lang->zahost->downloadImage = 'Download Image';

$lang->zahost->createZanode        = 'Create Node';
$lang->zahost->initNotice          = 'Save successfully, initialize the ZAhost or return list';
$lang->zahost->createZanodeNotice  = 'Initialization successful, ready to create the zanode';
$lang->zahost->downloadImageNotice = 'Initialization successful, download the image to create zanode';
$lang->zahost->undeletedNotice     = "The host has nodes that cannot be deleted.";
$lang->zahost->uninitNotice        = 'Please init the host first';
$lang->zahost->netError            = 'Unable to connect to the host, please check the network and try again.';

$lang->zahost->init = new stdclass;
$lang->zahost->init->statusTitle = "Status";
$lang->zahost->init->checkStatus   = "Check Service Status";
$lang->zahost->init->not_install   = "Not installed";
$lang->zahost->init->not_available = "Installed, Not Started";
$lang->zahost->init->ready         = "Ready";
$lang->zahost->init->next          = "Next";

$lang->zahost->init->initFailNotice    = "Fail，Execute the installation service command on the host or <a href='https://github.com/easysoft/zenagent/' target='_blank'>See Help</a>.";
$lang->zahost->init->initSuccessNotice = "Succeed，%s and %s。";

$lang->zahost->init->serviceStatus = array();
$lang->zahost->init->serviceStatus['kvm']        = 'not_install';
$lang->zahost->init->serviceStatus['nginx']      = 'not_install';
$lang->zahost->init->serviceStatus['novnc']      = 'not_install';
$lang->zahost->init->serviceStatus['websockify'] = 'not_install';

$lang->zahost->init->title       = "Initialize Host";
$lang->zahost->init->descTitle   = "Follow these steps to complete the initialization on the host:";
$lang->zahost->init->initDesc    = "Execute the init script on the host: %s %s  <br>- Click check service status button.";
$lang->zahost->init->statusTitle = "Service Status";

$lang->zahost->image = new stdclass;
$lang->zahost->image->browseImage   = 'Image List';
$lang->zahost->image->createImage   = 'Create Image';
$lang->zahost->image->choseImage    = 'Select Image';
$lang->zahost->image->downloadImage = $lang->zahost->downloadImage;
$lang->zahost->image->startDowload  = 'Start Download';

$lang->zahost->image->common     = 'Image';
$lang->zahost->image->name       = 'Name';
$lang->zahost->image->desc       = 'Description';
$lang->zahost->image->path       = 'Image Path';
$lang->zahost->image->memory     = $lang->zahost->memory;
$lang->zahost->image->disk       = $lang->zahost->diskSize;
$lang->zahost->image->os         = $lang->zahost->os;
$lang->zahost->image->imageName  = $lang->zahost->imageName;
$lang->zahost->image->progress   = 'Download Progress';

$lang->zahost->image->statusList['notDownloaded'] = 'Not Downloaded';
$lang->zahost->image->statusList['created']       = 'Inprogress';
$lang->zahost->image->statusList['canceled']      = 'Not Downloaded';
$lang->zahost->image->statusList['inprogress']    = 'Inprogress';
$lang->zahost->image->statusList['pending']       = 'Waiting for download';
$lang->zahost->image->statusList['completed']     = 'Usable';
$lang->zahost->image->statusList['failed']        = 'Failed';

$lang->zahost->image->imageEmpty            = 'No Image';
$lang->zahost->image->downloadImageFail     = 'Failed to create image task';
$lang->zahost->image->downloadImageSuccess  = 'Successed to create image task';
$lang->zahost->image->cancelDownloadFail    = 'Failed to cancel image task';
$lang->zahost->image->cancelDownloadSuccess = 'Successed to cancel image task';

$lang->zahost->empty         = 'No Host';

$lang->zahost->statusList['wait']    = 'Wait';
$lang->zahost->statusList['ready']   = 'Ready';
$lang->zahost->statusList['online']  = 'Online';
$lang->zahost->statusList['offline'] = 'Offline';
$lang->zahost->statusList['busy']    = 'Busy';

$lang->zahost->vsoft = 'VM Software';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->cpuUnit = 'CORE';

$lang->zahost->zaHostType                 = 'Type';
$lang->zahost->zaHostTypeList['physical'] = 'Physical';

$lang->zahost->confirmDelete           = 'Do you want to delete this host?';
$lang->zahost->cancelDelete            = 'Do you want to cancel this download task?';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = '『%s』incorrect format!';
$lang->zahost->notice->registerCommand = 'Register command: ./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = 'loading...';
$lang->zahost->notice->noImage         = 'No available image';

$lang->zahost->tips = 'Host include physical host, K8s clusters, cloud servers, and cloud container instances. Host used to create VMS or container instances. The recommended host OS is Ubuntu or CentOS LTS.';

$lang->zahost->automation = new stdclass();
$lang->zahost->automation->title = 'Test Automation Solutions';
$lang->zahost->automation->abstract      = 'Abstract';
$lang->zahost->automation->abstractSpec  = 'The Zen Tao automated test solution realizes the centralized management of test cases, test scripts, script execution, test results and test environment, which reduces the cost of test management and improves the efficiency of test execution. Through the solution, you can easily establish an automated test system suitable for the current project management and development process, and reduce the investment of testing work with automation technology.';
$lang->zahost->automation->framework     = 'Framework';
$lang->zahost->automation->frameworkSpec = 'The solution architecture based on the KVM virtualization software:';

$lang->zahost->automation->feature1           = '1、Core concepts';
$lang->zahost->automation->feature1Spec       = "Hosts include physical hosts, K8s clusters, cloud servers, and cloud container instances, which are mainly used to create virtual machines or container instances. The recommended operating system for the host is Ubuntu or the LTS version of CentOS.<br/> An execution node is a virtual machine or container instance created by the host and is a test environment where test tasks are executed.";
$lang->zahost->automation->feature2           = '2、Application introduction';
$lang->zahost->automation->feature2ZenAgent   = 'ZenAgent is an open source software test automation and scheduling platform, which provides users with a distributed and centrally managed test environment with the help of virtualization technology.';
$lang->zahost->automation->feature2ZTF        = 'ZTF is an open source automated test management framework of Zen Tao, which helps users to manage test scripts in a unified way. ZTF is deeply integrated with Zen Tao, each script can be associated with a use case in the test management system, and the step information in the script and the use case information in the management system can be synchronized with each other.';
$lang->zahost->automation->feature2KVM        = 'KVM(for Kernel-based Virtual Machine) is a complete virtualization solution for Linux on x86 hardware, including virtualization extensions (Intel VT or AMD-V).';
$lang->zahost->automation->feature2Nginx      = 'Nginx is a high-performance HTTP and reverse proxy web server that also provides IMAP/POP3/SMTP services.';
$lang->zahost->automation->feature2noVNC      = 'noVNC is an HTML VNC client-side JavaScript library and applications built on top of it. noVNC works well on any major browser, including mobile browsers (iOS and Android).';
$lang->zahost->automation->feature2Websockify = 'Websockify simply converts WebSockets traffic to normal socket traffic. Websockify accepts the WebSockets handshake, parses it, and then starts forwarding traffic in both directions between the client and the destination.';
$lang->zahost->automation->support            = 'Support';
$lang->zahost->automation->supportSpec        = 'You can visit the Zen Do website for the help manual:';
$lang->zahost->automation->groupTitle         = "Welcome to scan the QR code <br/> Get Help";
