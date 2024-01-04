<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = '宿主机';
$lang->zahost->browse         = '宿主机列表';
$lang->zahost->create         = '添加宿主机';
$lang->zahost->view           = '宿主机详情';
$lang->zahost->initTitle      = '初始化宿主机';
$lang->zahost->edit           = '编辑';
$lang->zahost->editAction     = '编辑宿主机';
$lang->zahost->delete         = '删除';
$lang->zahost->cancel         = "取消下载";
$lang->zahost->deleteAction   = '删除宿主机';
$lang->zahost->byQuery        = '搜索';
$lang->zahost->all            = '全部主机';
$lang->zahost->browseNode     = '执行节点列表';
$lang->zahost->deleted        = "已删除";
$lang->zahost->copy           = '复制';
$lang->zahost->copied         = '复制成功';
$lang->zahost->baseInfo       = '基础信息';

$lang->zahost->name        = '名称';
$lang->zahost->IP          = 'IP/域名';
$lang->zahost->extranet    = 'IP/域名';
$lang->zahost->memory      = '内存';
$lang->zahost->cpuCores    = 'CPU';
$lang->zahost->diskSize    = '硬盘容量';
$lang->zahost->desc        = '描述';
$lang->zahost->type        = '类型';
$lang->zahost->status      = '状态';

$lang->zahost->createdBy    = '由谁创建';
$lang->zahost->createdDate  = '创建时间';
$lang->zahost->editedBy     = '由谁修改';
$lang->zahost->editedDate   = '最后修改时间';
$lang->zahost->registerDate = '最后注册时间';

$lang->zahost->memorySize    = $lang->zahost->memory;
$lang->zahost->cpuCoreNum    = $lang->zahost->cpuCores;
$lang->zahost->os            = '操作系统';
$lang->zahost->imageName     = '镜像文件';
$lang->zahost->browseImage   = '镜像列表';
$lang->zahost->downloadImage = '下载镜像';

$lang->zahost->createZanode        = '创建执行节点';
$lang->zahost->initNotice          = '保存成功，请您初始化宿主机或返回列表。';
$lang->zahost->createZanodeNotice  = '初始化成功，您现在可以创建执行节点了。';
$lang->zahost->downloadImageNotice = '初始化成功，请下载镜像用于创建执行节点。';
$lang->zahost->undeletedNotice     = "宿主机下存在执行节点无法删除。";
$lang->zahost->uninitNotice        = '请先初始化宿主机';
$lang->zahost->netError            = '无法连接到宿主机，请检查网络后重试。';

$lang->zahost->init = new stdclass;
$lang->zahost->init->statusTitle = "服务状态";
$lang->zahost->init->checkStatus = "检测服务状态";
$lang->zahost->init->not_install = "未安装";
$lang->zahost->init->not_available = "已安装，未启动";
$lang->zahost->init->ready = "已就绪";
$lang->zahost->init->next = "下一步";

$lang->zahost->init->initFailNotice    = "服务未就绪，在宿主机上执行安装服务命令或<a href='https://github.com/easysoft/zenagent/' target='_blank'>查看帮助</a>.";
$lang->zahost->init->initSuccessNotice = "服务已就绪，您可以在%s后%s。";

$lang->zahost->init->serviceStatus = array();
$lang->zahost->init->serviceStatus['kvm']        = 'not_install';
$lang->zahost->init->serviceStatus['nginx']      = 'not_install';
$lang->zahost->init->serviceStatus['novnc']      = 'not_install';
$lang->zahost->init->serviceStatus['websockify'] = 'not_install';

$lang->zahost->init->title       = "初始化宿主机";
$lang->zahost->init->descTitle   = "请根据引导完成宿主机上的初始化: ";
$lang->zahost->init->initDesc    = "- 在宿主机上执行命令：%s %s <br>- 点击检测服务状态。";
$lang->zahost->init->statusTitle = "服务状态";

$lang->zahost->image = new stdclass;
$lang->zahost->image->browseImage   = '镜像列表';
$lang->zahost->image->createImage   = '创建镜像';
$lang->zahost->image->choseImage    = '选择镜像';
$lang->zahost->image->downloadImage = $lang->zahost->downloadImage;
$lang->zahost->image->startDowload  = '开始下载';

$lang->zahost->image->common     = '镜像';
$lang->zahost->image->name       = '名称';
$lang->zahost->image->desc       = '描述';
$lang->zahost->image->path       = '文件路径';
$lang->zahost->image->memory     = $lang->zahost->memory;
$lang->zahost->image->disk       = $lang->zahost->diskSize;
$lang->zahost->image->os         = $lang->zahost->os;
$lang->zahost->image->imageName  = $lang->zahost->imageName;
$lang->zahost->image->progress   = '下载进度';

$lang->zahost->image->statusList['notDownloaded'] = '可下载';
$lang->zahost->image->statusList['created']       = '下载中';
$lang->zahost->image->statusList['canceled']      = '可下载';
$lang->zahost->image->statusList['inprogress']    = '下载中';
$lang->zahost->image->statusList['pending']       = '排队下载中';
$lang->zahost->image->statusList['completed']     = '可使用';
$lang->zahost->image->statusList['failed']        = '下载失败';

$lang->zahost->image->imageEmpty           = '无镜像';
$lang->zahost->image->downloadImageFail    = '创建下载镜像任务失败';
$lang->zahost->image->downloadImageSuccess = '创建下载镜像任务成功';
$lang->zahost->image->cancelDownloadFail    = '取消下载镜像任务失败';
$lang->zahost->image->cancelDownloadSuccess = '取消下载镜像任务成功';

$lang->zahost->empty         = '暂时没有宿主机';

$lang->zahost->statusList['wait']    = '待初始化';
$lang->zahost->statusList['ready']   = '就绪';
$lang->zahost->statusList['online']  = '在线';
$lang->zahost->statusList['offline'] = '离线';
$lang->zahost->statusList['busy']    = '繁忙';

$lang->zahost->vsoft = '虚拟化软件';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->cpuUnit = '核';

$lang->zahost->zaHostType                 = '主机类型';
$lang->zahost->zaHostTypeList['physical'] = '实体主机';

$lang->zahost->confirmDelete           = '是否删除该宿主机记录？';
$lang->zahost->cancelDelete            = '是否取消该下载任务？';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = '『%s』格式不正确！';
$lang->zahost->notice->registerCommand = '宿主机注册命令：./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = '加载中...';
$lang->zahost->notice->noImage         = '无可用的镜像文件';

$lang->zahost->tips = '宿主机包括实体主机、K8s集群、云服务器以及云容器实例，主要用于创建虚拟机或容器实例。宿主机推荐安装的操作系统为Ubuntu或CentOS的LTS版本。';

$lang->zahost->automation = new stdclass();
$lang->zahost->automation->title = '自动化测试解决方案';
$lang->zahost->automation->abstract      = '简介';
$lang->zahost->automation->abstractSpec  = '禅道自动化测试解决方案实现了对测试用例，测试脚本、脚本执行、测试结果以及测试环境的集中化管理，在降低测试管理成本的同时提高了测试执行的效率。通过解决方案你可以更容易地建立起适配当前项目管理和研发流程的自动化测试体系，借助自动化技术减少测试工作的投入。';
$lang->zahost->automation->framework     = '架构';
$lang->zahost->automation->frameworkSpec = '基于KVM虚拟化软件的解决方案架构：';

$lang->zahost->automation->feature1           = '1、核心概念';
$lang->zahost->automation->feature1Spec       = "宿主机包括实体主机、K8s集群、云服务器以及云容器实例，主要用于创建虚拟机或容器实例。宿主机推荐安装的操作系统为Ubuntu或CentOS的LTS版本。<br/> 执行节点是由宿主机创建的虚拟机或容器实例，是执行测试任务的测试环境。";
$lang->zahost->automation->feature2           = '2、应用介绍';
$lang->zahost->automation->feature2ZenAgent   = 'ZenAgent是禅道开源的软件自动化测试调度平台，它借助虚拟化技术，为用户提供了一个分布式、集中管理的的测试环境。';
$lang->zahost->automation->feature2ZTF        = 'ZTF是禅道开源的自动化测试管理框架，它帮助用户将测试脚本统一管理。ZTF与禅道深度集成，每一个脚本都可以和测试管理系统里面的一个用例进行关联，脚本里面的步骤信息和管理系统里面的用例信息可以互相同步。';
$lang->zahost->automation->feature2KVM        = 'KVM(for Kernel-based Virtual Machine)是x86硬件上Linux的完整虚拟化解决方案，包含虚拟化扩展(Intel VT或AMD-V)。';
$lang->zahost->automation->feature2Nginx      = 'Nginx是一个高性能的HTTP和反向代理web服务器，同时也提供了IMAP/POP3/SMTP服务。';
$lang->zahost->automation->feature2noVNC      = 'noVNC是一个HTML VNC客户端JavaScript类库和构建在该类库上的应用程序。 noVNC在任何主流浏览器(包括移动浏览器(iOS和Android)上运行良好。';
$lang->zahost->automation->feature2Websockify = 'Websockify只是将WebSockets流量转换为正常的socket流量。Websockify接受WebSockets握手，解析它，然后开始在客户端和目标之间双向转发流量。';
$lang->zahost->automation->support            = '支持';
$lang->zahost->automation->supportSpec        = '您可以访问禅道官网获取帮助手册：';
$lang->zahost->automation->groupTitle         = "欢迎扫描二维码<br/>获取帮助";
