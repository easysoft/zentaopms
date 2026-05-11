<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = 'Host';
$lang->zahost->browse         = 'Host List';
$lang->zahost->create         = 'Add Host';
$lang->zahost->view           = 'Host Details';
$lang->zahost->initTitle      = 'Initialize Host';
$lang->zahost->edit           = 'Edit';
$lang->zahost->editAction     = 'Edit Host';
$lang->zahost->delete         = 'Delete';
$lang->zahost->cancel         = "Cancel Download";
$lang->zahost->deleteAction   = 'Delete Host';
$lang->zahost->byQuery        = 'Search';
$lang->zahost->all            = 'All Hosts';
$lang->zahost->browseNode     = 'Execution Nodes';
$lang->zahost->deleted        = "Deleted";
$lang->zahost->copy           = 'Copy';
$lang->zahost->copied         = 'Copied';
$lang->zahost->baseInfo       = 'Basic information';
$lang->zahost->hostType       = 'Host Type';

$lang->zahost->name        = 'Name';
$lang->zahost->IP          = 'IP/Domain';
$lang->zahost->extranet    = 'External IP/Domain';
$lang->zahost->memory      = 'Memory';
$lang->zahost->cpuCores    = 'CPU';
$lang->zahost->diskSize    = 'Disk Capacity';
$lang->zahost->desc        = 'Description';
$lang->zahost->type        = 'Type';
$lang->zahost->status      = 'Status';

$lang->zahost->createdBy    = 'Creator';
$lang->zahost->createdDate  = 'Created At';
$lang->zahost->editedBy     = 'Last Modified by';
$lang->zahost->editedDate   = 'Last Modified At';
$lang->zahost->registerDate = 'Registration Date';

$lang->zahost->memorySize    = $lang->zahost->memory;
$lang->zahost->cpuCoreNum    = $lang->zahost->cpuCores;
$lang->zahost->os            = 'Operating System';
$lang->zahost->imageName     = 'Image File';
$lang->zahost->browseImage   = 'Image List';
$lang->zahost->downloadImage = 'Download Image';

$lang->zahost->createZanode        = 'Create Execution Node';
$lang->zahost->initNotice          = 'Host saved successfully. Please initialize it or return to the host list.';
$lang->zahost->createZanodeNotice  = 'Initialization successful. You can now create an execution node.';
$lang->zahost->downloadImageNotice = 'Initialization successful. Please download the image to create an execution node.';
$lang->zahost->undeletedNotice     = "Cannot delete this host because it has associated execution nodes.";
$lang->zahost->uninitNotice        = 'Please initialize the host first.';
$lang->zahost->netError            = 'Unable to connect to the host. Please check your network and try again.';

$lang->zahost->init = new stdclass;
$lang->zahost->init->statusTitle = "Status";
$lang->zahost->init->checkStatus   = "Check Service Status";
$lang->zahost->init->not_install   = "Not installed";
$lang->zahost->init->not_available = "Installed, but Not Started";
$lang->zahost->init->ready         = "Ready";
$lang->zahost->init->next          = "Next";

$lang->zahost->init->initFailNotice    = "Service is not ready. Please execute the installation command on the host, or <a href='...' target='_blank'>View Help</a>.";
$lang->zahost->init->initSuccessNotice = "Service is ready. You can %s after %s.";

$lang->zahost->init->serviceStatus = array();
$lang->zahost->init->serviceStatus['kvm']        = 'Not Installed';
$lang->zahost->init->serviceStatus['nginx']      = 'Not Installed';
$lang->zahost->init->serviceStatus['novnc']      = 'Not Installed';
$lang->zahost->init->serviceStatus['websockify'] = 'Not Installed';

$lang->zahost->init->title       = "Initialize Host";
$lang->zahost->init->descTitle   = "Please follow these steps to initialize the host:";
$lang->zahost->init->initDesc    = "Execute the command on the host: %s %s. Then click the Check Service Status button.";
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
$lang->zahost->image->path       = 'File Path';
$lang->zahost->image->memory     = $lang->zahost->memory;
$lang->zahost->image->disk       = $lang->zahost->diskSize;
$lang->zahost->image->os         = $lang->zahost->os;
$lang->zahost->image->imageName  = $lang->zahost->imageName;
$lang->zahost->image->progress   = 'Download Progress';

$lang->zahost->image->statusList['notDownloaded'] = 'Available for Download';
$lang->zahost->image->statusList['created']       = 'Downloading';
$lang->zahost->image->statusList['canceled']      = 'Available for Download';
$lang->zahost->image->statusList['inprogress']    = 'Downloading';
$lang->zahost->image->statusList['pending']       = 'Waiting for download';
$lang->zahost->image->statusList['completed']     = 'Ready';
$lang->zahost->image->statusList['failed']        = 'Failed';

$lang->zahost->image->imageEmpty            = 'No images found.';
$lang->zahost->image->downloadImageFail     = 'Failed to create the image download task.';
$lang->zahost->image->downloadImageSuccess  = 'Image download task created successfully.';
$lang->zahost->image->cancelDownloadFail    = 'Failed to cancel the image download task.';
$lang->zahost->image->cancelDownloadSuccess = 'Image download task canceled successfully.';

$lang->zahost->empty         = 'No hosts found.';

$lang->zahost->statusList['wait']    = 'Pending Initialization';
$lang->zahost->statusList['ready']   = 'Ready';
$lang->zahost->statusList['online']  = 'Online';
$lang->zahost->statusList['offline'] = 'Offline';
$lang->zahost->statusList['busy']    = 'Busy';

$lang->zahost->vsoft = 'Virtualization Software';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->cpuUnit = 'Cores';

$lang->zahost->zaHostType                 = 'Host Type';
$lang->zahost->zaHostTypeList['physical'] = 'Physical Host';

$lang->zahost->confirmDelete           = 'Are you sure you want to delete this host?';
$lang->zahost->cancelDelete            = 'Are you sure you want to cancel this download task?';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = 'Invalid format for "%s".';
$lang->zahost->notice->registerCommand = 'Host Registration Command: ./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = 'loading...';
$lang->zahost->notice->noImage         = 'No image files available.';

$lang->zahost->tips = 'Hosts include physical hosts, Kubernetes (K8s) clusters, cloud servers, and cloud container instances. They are primarily used to create virtual machines or container instances. The recommended operating system for the host is Ubuntu or a CentOS LTS release.';

$lang->zahost->automation = new stdclass();
$lang->zahost->automation->title = 'Test Automation Solution';
$lang->zahost->automation->abstract      = 'Introduction';
$lang->zahost->automation->abstractSpec  = 'The ZenTao test automation solution provides centralized management of test cases, test scripts, script execution, test results, and test environments. It reduces test management costs and improves execution efficiency. This solution helps you easily establish an automated testing system tailored to your current project management and development workflows, minimizing manual testing effort.';
$lang->zahost->automation->framework     = 'Architecture';
$lang->zahost->automation->frameworkSpec = 'Solution architecture based on KVM virtualization:';

$lang->zahost->automation->feature1           = '1. Core Concepts';
$lang->zahost->automation->feature1Spec       = "Hosts include physical hosts, Kubernetes (K8s) clusters, cloud servers, and cloud container instances, which are mainly used to create virtual machines or container instances. The recommended operating system for the host is Ubuntu or a CentOS LTS release.<br/> An execution node is a virtual machine or container instance created by the host, serving as the test environment where tasks are executed.
";
$lang->zahost->automation->feature2           = '2. Application Overview';
$lang->zahost->automation->feature2ZenAgent   = 'ZAgent is ZenTao\'s open-source automated testing and scheduling platform. By leveraging virtualization technology, it provides users with a distributed and centrally managed test environment.';
$lang->zahost->automation->feature2ZTF        = 'ZTF is ZenTao\'s open-source automated testing framework, helping users uniformly manage test scripts. ZTF is deeply integrated with ZenTao; each script can be linked to a test case in the system, allowing step and case information to sync seamlessly.';
$lang->zahost->automation->feature2KVM        = 'KVM (Kernel-based Virtual Machine) is a full virtualization solution for Linux on x86 hardware containing virtualization extensions (Intel VT or AMD-V).';
$lang->zahost->automation->feature2Nginx      = 'Nginx is a high-performance HTTP and reverse proxy web server that also provides IMAP/POP3/SMTP services.';
$lang->zahost->automation->feature2noVNC      = 'noVNC is an HTML VNC client JavaScript library and application. It works smoothly on any major web browser, including mobile browsers (iOS and Android).';
$lang->zahost->automation->feature2Websockify = 'Websockify acts as a bridge, converting WebSockets traffic to normal socket traffic. It accepts the WebSockets handshake, parses it, and then forwards traffic in both directions between the client and the target server.';
$lang->zahost->automation->support            = 'Support';
$lang->zahost->automation->supportSpec        = 'You can visit the ZenTao official website for the user manual:';
$lang->zahost->automation->groupTitle         = "Scan the QR code to get help";
