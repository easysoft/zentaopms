<?php
$lang->zanode->common          = '执行节点';
$lang->zanode->instruction     = '说明';
$lang->zanode->browse          = '执行节点列表';
$lang->zanode->nodeList        = '执行节点列表';
$lang->zanode->create          = '创建执行节点';
$lang->zanode->edit            = '编辑执行节点';
$lang->zanode->editAction      = '编辑执行节点';
$lang->zanode->view            = '执行节点详情';
$lang->zanode->initTitle       = '初始化执行节点';
$lang->zanode->suspend         = '休眠执行节点';
$lang->zanode->destroy         = '销毁执行节点';
$lang->zanode->boot            = '启动执行节点';
$lang->zanode->reboot          = '重启执行节点';
$lang->zanode->shutdown        = '关闭执行节点';
$lang->zanode->resume          = '恢复执行节点';
$lang->zanode->suspendNode     = '休眠';
$lang->zanode->bootNode        = '启动';
$lang->zanode->rebootNode      = '重启';
$lang->zanode->shutdownNode    = '关闭';
$lang->zanode->resumeNode      = '恢复';
$lang->zanode->getVNC          = '远程';
$lang->zanode->all             = '全部';
$lang->zanode->byQuery         = '搜索';
$lang->zanode->osName          = '操作系统';
$lang->zanode->osNamePhysics   = '操作系统';
$lang->zanode->image           = '镜像';
$lang->zanode->imageName       = '镜像名称';
$lang->zanode->name            = '名称';
$lang->zanode->start           = '创建后自动开启';
$lang->zanode->hostName        = '所属宿主机';
$lang->zanode->host            = $lang->zanode->hostName;
$lang->zanode->extranet        = 'IP/域名';
$lang->zanode->sshCommand      = 'SSH命令';
$lang->zanode->sshAddress      = 'SSH地址';
$lang->zanode->osArch          = '架构';
$lang->zanode->cpuCores        = 'CPU';
$lang->zanode->defaultUser     = '默认用户';
$lang->zanode->defaultPwd      = '默认密码';
$lang->zanode->memory          = '内存';
$lang->zanode->diskSize        = '硬盘';
$lang->zanode->desc            = '描述';
$lang->zanode->status          = '状态';
$lang->zanode->mac             = 'MAC地址';
$lang->zanode->vnc             = 'VNC端口';
$lang->zanode->destroyAt       = '销毁时间';
$lang->zanode->creater         = '创建人';
$lang->zanode->createdDate     = '创建日期';
$lang->zanode->confirmDelete   = "您确定销毁执行节点吗？";
$lang->zanode->confirmBoot     = "您确定启动执行节点吗？";
$lang->zanode->confirmReboot   = "您确定重启执行节点吗？";
$lang->zanode->confirmShutdown = "您确定关闭执行节点吗？";
$lang->zanode->confirmSuspend  = "您确定休眠执行节点吗？";
$lang->zanode->confirmResume   = "您确定恢复执行节点吗？";
$lang->zanode->confirmRestore  = "执行节点将会还原至此快照状态，您确定要继续么？";
$lang->zanode->actionSuccess   = '操作成功';
$lang->zanode->deleted         = "已删除";
$lang->zanode->scriptPath      = "脚本目录";
$lang->zanode->syncToZentao    = "同步脚本信息到禅道";
$lang->zanode->shell           = "shell命令";
$lang->zanode->automation      = "自动化设置";
$lang->zanode->install         = "安装";
$lang->zanode->reinstall       = "重装";
$lang->zanode->copy            = '复制';
$lang->zanode->copied          = '复制成功';
$lang->zanode->manual          = '手册';
$lang->zanode->initializing    = '初始化中';
$lang->zanode->showPwd         = '显示密码';
$lang->zanode->hidePwd         = '隐藏密码';
$lang->zanode->baseInfo        = '基础信息';
$lang->zanode->cpuUnit         = '核';
$lang->zanode->IP              = 'IP/域名';

$lang->zanode->typeList['node']    = '虚拟机';
$lang->zanode->typeList['physics'] = '物理机';

$lang->automation = new stdClass();
$lang->automation->scriptPath = $lang->zanode->scriptPath;
$lang->automation->node       = $lang->zanode->common;

$lang->zanode->notFoundAgent  = '没有发现Agent服务';
$lang->zanode->busy           = '节点正在%s, 请等待操作完成';
$lang->zanode->createVmFail   = '创建执行节点失败';
$lang->zanode->noVncPort      = '无法获取执行节点端口';
$lang->zanode->nameValid      = "名称只能是字母、数字，'-'，'_'，'.'，且不能以符号开头";
$lang->zanode->empty          = '暂时没有执行节点';
$lang->zanode->runCaseConfirm = '系统检测到选择的用例存在自动化测试脚本，是否自动执行用例？';
$lang->zanode->netError       = '无法连接到物理机，请检查网络后重试。';

$lang->zanode->createImage        = '导出镜像';
$lang->zanode->createImaging      = '正在导出镜像';
$lang->zanode->pending            = '等待导出镜像';
$lang->zanode->createImageNotice  = '系统将基于当前执行节点导出镜像，该过程需要关闭该执行节点，确定要继续么？';
$lang->zanode->createImageSuccess = '镜像导出成功，您可以使用此镜像创建执行节点。';
$lang->zanode->createImageFail    = '镜像导出失败';
$lang->zanode->createImageButton  = '去创建';

$lang->zanode->snapshotName          = '快照名称';
$lang->zanode->browseSnapshot        = '快照列表';
$lang->zanode->createSnapshot        = '创建快照';
$lang->zanode->editSnapshot          = '编辑快照';
$lang->zanode->restoreSnapshot       = '还原到此快照';
$lang->zanode->deleteSnapshot        = '删除快照';
$lang->zanode->snapshotEmpty         = '无快照';
$lang->zanode->confirmDeleteSnapshot = "快照被删除后无法从回收站中还原，您确定继续么？";

$lang->zanode->snapshot = new stdClass();
$lang->zanode->snapshot->statusList['creating']          = '创建中';
$lang->zanode->snapshot->statusList['inprogress']        = '创建中';
$lang->zanode->snapshot->statusList['completed']         = '可使用';
$lang->zanode->snapshot->statusList['failed']            = '创建失败';
$lang->zanode->snapshot->statusList['restoring']         = '还原中';
$lang->zanode->snapshot->statusList['restore_failed']    = '还原失败';
$lang->zanode->snapshot->statusList['restore_completed'] = '可使用';

$lang->zanode->snapshot->defaultSnapName = '初始快照';
$lang->zanode->snapshot->defaultSnapUser = '系统';

$lang->zanode->imageNameEmpty  = '名称不能为空';
$lang->zanode->snapStatusError = '快照不可用';
$lang->zanode->snapRestoring   = '快照正在还原中';

$lang->zanode->runTimeout = '自动执行失败，请检查宿主机和执行节点状态';

$lang->zanode->apiError['-10100']     = '执行节点不存在';
$lang->zanode->apiError['fail']       = '执行失败，请检查宿主机和执行节点状态';
$lang->zanode->apiError['notRunning'] = '请检查执行节点状态';

$lang->zanode->publicList[0] = '不共享';
$lang->zanode->publicList[1] = '共享';

$lang->zanode->statusList['created']       = '已创建';
$lang->zanode->statusList['launch']        = '启动中';
$lang->zanode->statusList['ready']         = '就绪';
$lang->zanode->statusList['running']       = '运行中';
$lang->zanode->statusList['suspend']       = '休眠';
$lang->zanode->statusList['offline']       = '离线';
$lang->zanode->statusList['destroy']       = '已销毁';
$lang->zanode->statusList['shutoff']       = '已关机';
$lang->zanode->statusList['destroy_fail']  = '销毁失败';
$lang->zanode->statusList['wait']          = '初始化中';
$lang->zanode->statusList['online']        = '在线';
$lang->zanode->statusList['restoring']     = '还原中';
$lang->zanode->statusList['creating_snap'] = '创建快照中';
$lang->zanode->statusList['creating_img']  = '导出镜像中';

$lang->zanode->initNotice = "保存成功，请初始化执行节点或返回列表。";
$lang->zanode->initButton = "去初始化";

$lang->zanode->init = new stdClass();
$lang->zanode->init->statusTitle   = "服务状态";
$lang->zanode->init->checkStatus   = "检测服务状态";
$lang->zanode->init->not_install   = "未安装";
$lang->zanode->init->unknown       = "未知";
$lang->zanode->init->not_available = "已安装，未启动";
$lang->zanode->init->ready         = "已就绪";
$lang->zanode->init->next          = "下一步";
$lang->zanode->init->button        = "去设置";

$lang->zanode->init->initSuccessNoticeTitle  = "服务已就绪，还需两步即可在执行节点上执行自动化测试：<br/>1、根据%s配置自动化测试运行环境。<br/>2、进行%s";
$lang->zanode->init->initFailNotice          = "服务未就绪，在执行节点上执行安装服务命令或<a href='https://github.com/easysoft/zenagent/' target='_blank'>查看帮助</a>.";
$lang->zanode->init->initFailNoticeOnPhysics = "服务还没有安装，请在执行节点执行以下命令后检测服务状态。<a href='https://github.com/easysoft/zenagent/' target='_blank'>查看帮助</a>";

$lang->zanode->init->serviceStatus = array(
    "ZenAgent" => 'not_install',
    "ZTF"      => 'not_install',
);
$lang->zanode->init->title          = "初始化执行节点";
$lang->zanode->init->descTitle      = "请根据引导完成执行节点上的初始化: ";
$lang->zanode->init->initDesc       = "- 在执行节点上执行命令：%s %s  <br>- 点击检测服务状态。";$lang->zanode->init->statusTitle    = "服务状态";

$lang->zanode->tips           = '执行节点是由宿主机创建的虚拟机或容器实例，是执行测试任务的测试环境，在执行节点配置自动化测试环境后可以自动执行脚本，结果可以在禅道对应用例执行结果中查看。';
$lang->zanode->scriptTips     = '填写执行节点上自动化测试脚本所在的目录。';
$lang->zanode->shellTips      = '在执行节点上运行自动化测试脚本前，可以执行自定义的shell命令。';
$lang->zanode->automationTips = "在执行节点上执行测试任务前，需要设置{$lang->productCommon}对应的执行节点，自动化测试脚本的目录以及需要执行的自定义Shell命令。";
$lang->zanode->nameUnique     = $lang->zanode->name . '已存在';

$lang->zanode->instructionPage = new stdClass();
$lang->zanode->instructionPage->title            = "禅道自动化测试解决方案";
$lang->zanode->instructionPage->desc             = "禅道自动化测试解决方案实现了对测试用例，测试脚本、脚本执行、测试结果以及测试环境的集中化管理，在降低测试管理成本的同时提高了测试执行的效率。通过解决方案你可以更容易地建立起适配当前项目管理和研发流程的自动化测试体系，借助自动化技术减少测试工作的投入。";
$lang->zanode->instructionPage->imageInstruction = '架构图如下:';
$lang->zanode->instructionPage->image            = 'static/svg/zanode_instruction.svg';
$lang->zanode->instructionPage->concept          = '1、核心概念';
$lang->zanode->instructionPage->conceptDesc      = '宿主机包括实体主机、K8s集群、云服务器以及云容器实例，主要用于创建虚拟机或容器实例。宿主机推荐安装的操作系统为Ubuntu或CentOS的LTS版本。
执行节点是由宿主机创建的虚拟机或容器实例，是执行测试任务的测试环境。';
$lang->zanode->instructionPage->AppIntroduction  = '2、应用介绍';
$lang->zanode->instructionPage->ZAgentDesc       = 'ZAgent是禅道开源的软件自动化测试调度平台，它借助虚拟化技术，为用户提供了一个分布式、集中管理的的测试环境。';
$lang->zanode->instructionPage->ZAgentUrl        = 'https://github.com/easysoft/zenagent/blob/main/guide/deploy/index.md';
$lang->zanode->instructionPage->ZTFDesc          = 'ZTF是禅道开源的自动化测试管理框架，它帮助用户将测试脚本统一管理。ZTF与禅道深度集成，每一个脚本都可以和测试管理系统里面的一个用例进行关联，脚本里面的步骤信息和管理系统里面的用例信息可以互相同步。';
$lang->zanode->instructionPage->ZTFUrl           = 'https://ztf.im/';
$lang->zanode->instructionPage->KVMDesc          = 'KVM(for Kernel-based Virtual Machine)是x86硬件上Linux的完整虚拟化解决方案，包含虚拟化扩展(Intel VT或AMD-V)。';
$lang->zanode->instructionPage->KVMUrl           = 'https://www.linux-kvm.org/page/Documents';
$lang->zanode->instructionPage->NginxDesc        = 'Nginx是一个高性能的HTTP和反向代理web服务器，同时也提供了IMAP/POP3/SMTP服务。';
$lang->zanode->instructionPage->NginxUrl         = 'http://nginx.org/en/docs/';
$lang->zanode->instructionPage->noVNCDesc        = 'noVNC是一个HTML VNC客户端JavaScript类库和构建在该类库上的应用程序。 noVNC在任何主流浏览器(包括移动浏览器(iOS和Android)上运行良好。';
$lang->zanode->instructionPage->noVNCUrl         = 'https://novnc.com/info.html';
$lang->zanode->instructionPage->WebsockifyDesc   = 'Websockify只是将WebSockets流量转换为正常的socket流量。Websockify接受WebSockets握手，解析它，然后开始在客户端和目标之间双向转发流量。';
$lang->zanode->instructionPage->WebsockifyUrl    = 'https://github.com/novnc/websockify';
