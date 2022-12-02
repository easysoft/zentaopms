<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = '宿主机';
$lang->zahost->browse         = '宿主机列表';
$lang->zahost->create         = '添加宿主机';
$lang->zahost->view           = '宿主机详情';
$lang->zahost->init           = '初始化宿主机';
$lang->zahost->edit           = '编辑';
$lang->zahost->editAction     = '编辑宿主机';
$lang->zahost->delete         = '删除';
$lang->zahost->cancel         = "取消下载";
$lang->zahost->deleteAction   = '删除宿主机';
$lang->zahost->byQuery        = '搜索';
$lang->zahost->all            = '全部主机';
$lang->zahost->browseNode     = '执行节点列表';
$lang->zahost->deleted        = "已删除";

$lang->zahost->name        = '名称';
$lang->zahost->IP          = 'IP/域名';
$lang->zahost->extranet    = 'IP/域名';
$lang->zahost->memory      = '内存';
$lang->zahost->cpuCores    = 'CPU核心数';
$lang->zahost->diskSize    = '硬盘容量';
$lang->zahost->desc        = '描述';
$lang->zahost->type        = '类型';
$lang->zahost->status      = '状态';

$lang->zahost->createdBy    = '由谁创建';
$lang->zahost->createdDate  = '创建时间';
$lang->zahost->editedBy     = '由谁修改';
$lang->zahost->editedDate   = '最后修改时间';
$lang->zahost->registerDate = '最后注册时间';

$lang->zahost->memorySize = $lang->zahost->memory;
$lang->zahost->cpuCoreNum = $lang->zahost->cpuCores;
$lang->zahost->os         = '操作系统';
$lang->zahost->imageName  = '镜像文件';

$lang->zahost->createZanode        = '创建执行节点';
$lang->zahost->initHostNotice      = '保存成功，请您初始化宿主机或返回列表。';
$lang->zahost->createZanodeNotice  = '初始化成功，您现在可以创建执行节点了。';
$lang->zahost->downloadImageNotice = '初始化成功，请下载镜像用于创建执行节点。';

$lang->zahost->initHost = new stdclass;
$lang->zahost->initHost->statusTitle = "服务状态";
$lang->zahost->initHost->checkStatus = "检测服务状态";
$lang->zahost->initHost->not_install = "未安装";
$lang->zahost->initHost->not_available = "已安装，未启动";
$lang->zahost->initHost->ready = "已就绪";
$lang->zahost->initHost->next = "下一步";

$lang->zahost->initHost->initSuccessNotice = "初始化成功，请点击下一步完成后续操作。";
$lang->zahost->initHost->initFailNoticeTitle = "初始化失败，请查看初始化脚本执行日志并尝试以下两种解决方案：";
$lang->zahost->initHost->initFailNoticeDesc = "1. 重新执行脚本 <br/>2. 查看初始化常见问题";
$lang->zahost->initHost->serviceStatus = [
    "kvm" => 'not_install',
    "novnc" => 'not_install',
    "websockify" => 'not_install',
];
$lang->zahost->initHost->title = "初始化宿主机";
$lang->zahost->initHost->descTitle = "请按照以下步骤在宿主机上完成初始化：";
$lang->zahost->initHost->initDesc = "在宿主机上执行：bash <(curl -s -S -L https://pkg.qucheng.com/zenagent/zagent.sh) -k ";

$lang->zahost->image = new stdclass;
$lang->zahost->image->list          = '镜像仓库';
$lang->zahost->image->browseImage   = '镜像列表';
$lang->zahost->image->createImage   = '创建镜像';
$lang->zahost->image->choseImage    = '选择镜像';
$lang->zahost->image->downloadImage = '下载镜像';
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

$lang->zahost->image->statusList['notDownloaded'] = '待下载';
$lang->zahost->image->statusList['created']       = '下载中';
$lang->zahost->image->statusList['canceled']      = '待下载';
$lang->zahost->image->statusList['inprogress']    = '下载中';
$lang->zahost->image->statusList['completed']     = '已下载';
$lang->zahost->image->statusList['failed']        = '下载失败';

$lang->zahost->image->imageEmpty           = '无镜像';
$lang->zahost->image->downloadImageFail    = '创建下载镜像任务失败';
$lang->zahost->image->downloadImageSuccess = '创建下载镜像任务成功';
$lang->zahost->image->cancelDownloadFail    = '取消下载镜像任务失败';
$lang->zahost->image->cancelDownloadSuccess = '取消下载镜像任务成功';

$lang->zahost->empty         = '暂时没有宿主机';

$lang->zahost->statusList['ready']  = '准备中';
$lang->zahost->statusList['online'] = '已上架';

$lang->zahost->vsoft = '虚拟化软件';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->zaHostType                 = '主机类型';
$lang->zahost->zaHostTypeList['physical'] = '实体主机';

$lang->zahost->confirmDelete           = '是否删除该宿主机记录？';
$lang->zahost->cancelDelete            = '是否取消该下载任务？';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = '『%s』格式不正确！';
$lang->zahost->notice->registerCommand = '宿主机注册命令：./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = '加载中...';
$lang->zahost->notice->noImage         = '无可用的镜像文件';
