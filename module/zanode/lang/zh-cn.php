<?php
$lang->zanode->common          = '执行节点';
$lang->zanode->browse          = '执行节点列表';
$lang->zanode->create          = '创建执行节点';
$lang->zanode->editAction      = '编辑执行节点';
$lang->zanode->view            = '执行节点详情';
$lang->zanode->initTitle       = '初始化执行节点';
$lang->zanode->suspend         = '暂停执行节点';
$lang->zanode->destroy         = '销毁执行节点';
$lang->zanode->handleVM        = '重启执行节点';
$lang->zanode->boot            = '启动执行节点';
$lang->zanode->reboot          = '重启执行节点';
$lang->zanode->shutdown        = '关闭执行节点';
$lang->zanode->resume          = '恢复执行节点';
$lang->zanode->getVNC          = '远程管理';
$lang->zanode->all             = '全部';
$lang->zanode->byQuery         = '搜索';
$lang->zanode->osName          = '操作系统';
$lang->zanode->image           = '镜像';
$lang->zanode->imageName       = '镜像名称';
$lang->zanode->name            = '执行节点名称';
$lang->zanode->start           = '创建后自动开启';
$lang->zanode->hostName        = '所属宿主机';
$lang->zanode->host            = $lang->zanode->hostName;
$lang->zanode->extranet        = 'IP/域名';
$lang->zanode->osArch          = '架构';
$lang->zanode->cpuCores        = 'CPU核心数';
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
$lang->zanode->confirmSuspend  = "您确定暂停执行节点吗？";
$lang->zanode->confirmResume   = "您确定恢复执行节点吗？";
$lang->zanode->actionSuccess   = '操作成功';
$lang->zanode->deleted         = "已删除";
$lang->zanode->scriptPath      = "脚本目录";
$lang->zanode->shell           = "shell命令";
$lang->zanode->automation      = "自动化测试";

$lang->automation = new stdClass();
$lang->automation->path = $lang->zanode->scriptPath;
$lang->automation->node = $lang->zanode->common;

$lang->zanode->notFoundAgent = '没有发现Agent服务';
$lang->zanode->createVmFail  = '创建执行节点失败';
$lang->zanode->noVncPort     = '无法获取执行节点端口';
$lang->zanode->nameValid     = "名称只能是字母、数字，'-'，'_'，'.'，且不能以符号开头";
$lang->zanode->empty         = '暂时没有执行节点';

$lang->zanode->createImage        = '创建镜像';
$lang->zanode->createImaging      = '正在创建镜像';
$lang->zanode->createImageNotice  = '系统将基于当前执行节点创建镜像，该过程需要关闭该执行节点，确定要继续么？';
$lang->zanode->createImageSuccess = '镜像创建成功，您可以使用此镜像创建执行节点。';
$lang->zanode->createImageFail    = '镜像创建失败';
$lang->zanode->createImageButton  = '去创建';

$lang->zanode->imageNameEmpty = '名称不能为空';

$lang->zanode->apiError['-10100'] = '执行节点不存在';

$lang->zanode->publicList[0] = '不共享';
$lang->zanode->publicList[1] = '共享';

$lang->zanode->statusList['created']      = '已创建';
$lang->zanode->statusList['launch']       = '启动中';
$lang->zanode->statusList['ready']        = '运行中';
$lang->zanode->statusList['running']      = '运行中';
$lang->zanode->statusList['suspend']      = '暂停';
$lang->zanode->statusList['offline']      = '下线';
$lang->zanode->statusList['destroy']      = '已销毁';
$lang->zanode->statusList['destroy_fail'] = '销毁失败';

$lang->zanode->init = new stdclass;
$lang->zanode->init->statusTitle = "服务状态";
$lang->zanode->init->checkStatus = "检测服务状态";
$lang->zanode->init->not_install = "未安装";
$lang->zanode->init->not_available = "已安装，未启动";
$lang->zanode->init->ready = "已就绪";
$lang->zanode->init->next = "下一步";

$lang->zanode->init->initFailNoticeTitle = "初始化失败，请查看初始化脚本执行日志并尝试以下两种解决方案：";
$lang->zanode->init->initFailNoticeDesc = "1. 重新执行脚本 <br/>2. 查看初始化常见问题";
$lang->zanode->init->serviceStatus = [
    "ZenAgent" => 'not_install',
    "ZTF"      => 'not_install',
];
$lang->zanode->init->title          = "初始化执行节点";
$lang->zanode->init->descTitle      = "请根据引导完成执行节点上的初始化: ";
$lang->zanode->init->initDesc       = "1. 在执行节点上执行：bash <(curl -s -S -L https://pkg.qucheng.com/zenagent/zagent.sh) -s vm ";
$lang->zanode->init->statusTitle    = "Service Status";