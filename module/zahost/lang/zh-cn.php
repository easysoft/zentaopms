<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = '宿主机';
$lang->zahost->browse         = '主机列表';
$lang->zahost->create         = '添加宿主机';
$lang->zahost->view           = '主机详情';
$lang->zahost->edit           = '编辑';
$lang->zahost->editAction     = '编辑宿主机';
$lang->zahost->delete         = '删除';
$lang->zahost->deleteAction   = '删除宿主机';
$lang->zahost->byQuery        = '搜索';
$lang->zahost->all            = '全部主机';
$lang->zahost->browseTemplate = '虚拟机模板列表';
$lang->zahost->createTemplate = '创建虚拟机模板';
$lang->zahost->editTemplate   = '编辑虚拟机模板';
$lang->zahost->deleteTemplate = '删除虚拟机模板';

$lang->zahost->name        = '名称';
$lang->zahost->IP          = 'IP/域名';
$lang->zahost->publicIP    = 'IP';
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
$lang->zahost->osType     = '操作系统版本';
$lang->zahost->osCategory = '操作系统平台';
$lang->zahost->osVersion  = '操作系统版本号';
$lang->zahost->osLang     = '系统语言';
$lang->zahost->imageName  = '镜像文件';

$lang->zahost->initHost = new stdclass;
$lang->zahost->initHost->statusTitle = "服务状态";
$lang->zahost->initHost->checkStatus = "检测服务状态";
$lang->zahost->initHost->not_install = "未安装";
$lang->zahost->initHost->not_available = "已安装，未启动";
$lang->zahost->initHost->ready = "已就绪";
$lang->zahost->initHost->next = "下一步";

$lang->zahost->initHost->initSuccessNotice = "初始化成功，请点击下一步完成后续操作。";
$lang->zahost->initHost->initFailNotice = "初始化失败，请查看初始化脚本执行日志并尝试以下两种解决方案：<br/>1. 重新执行脚本 <br/>2. 查看初始化常见问题";
$lang->zahost->initHost->serviceStatus = [
    "kvm" => 'not_install',
    "novnc" => 'not_install',
    "websockify" => 'not_install',
];
$lang->zahost->initHost->title = "初始化宿主机";
$lang->zahost->initHost->descTitle = "请按照以下步骤在宿主机上完成初始化：";
$lang->zahost->initHost->descLi = [
    "下载初始化脚本至宿主机：wget xxx -o xxx",
    "在宿主机上执行初始化脚本，Ubuntu下执行命令示例：xxxx"
];

$lang->zahost->image = new stdclass;
$lang->zahost->image->list          = '镜像列表';
$lang->zahost->image->browseImage   = '镜像列表';
$lang->zahost->image->createImage   = '创建镜像';
$lang->zahost->image->choseImage    = '选择镜像';
$lang->zahost->image->downloadImage = '下载镜像';
$lang->zahost->image->startDowload  = '开始下载';

$lang->zahost->image->name       = '名称';
$lang->zahost->image->common     = '镜像';
$lang->zahost->image->memory     = $lang->zahost->memory;
$lang->zahost->image->disk       = $lang->zahost->diskSize;
$lang->zahost->image->osType     = $lang->zahost->osType;
$lang->zahost->image->osCategory = $lang->zahost->osCategory;
$lang->zahost->image->osVersion  = $lang->zahost->osVersion;
$lang->zahost->image->osLang     = $lang->zahost->osLang;
$lang->zahost->image->imageName  = $lang->zahost->imageName;

$lang->zahost->image->imageEmpty = '无镜像';

$lang->zahost->image->statusList['waiting']     = '未下载';
$lang->zahost->image->statusList['downloading'] = '下载中';
$lang->zahost->image->statusList['finish']      = '已下载';
$lang->zahost->image->statusList['fail']        = '下载失败';


$lang->zahost->vmTemplate = new stdclass;
$lang->zahost->vmTemplate->name       = '名称';
$lang->zahost->vmTemplate->common     = '虚拟机模板';
$lang->zahost->vmTemplate->cpuCoreNum = $lang->zahost->cpuCores;
$lang->zahost->vmTemplate->memorySize = $lang->zahost->memory;
$lang->zahost->vmTemplate->diskSize   = $lang->zahost->diskSize;
$lang->zahost->vmTemplate->osType     = $lang->zahost->osType;
$lang->zahost->vmTemplate->osCategory = $lang->zahost->osCategory;
$lang->zahost->vmTemplate->osVersion  = $lang->zahost->osVersion;
$lang->zahost->vmTemplate->osLang     = $lang->zahost->osLang;
$lang->zahost->vmTemplate->imageName  = $lang->zahost->imageName;

$lang->zahost->langList = array();
$lang->zahost->langList['zh_cn'] = '简体中文';
$lang->zahost->langList['zh_tw'] = '繁体中文';
$lang->zahost->langList['en_us'] = '美式英语';

$lang->zahost->empty         = '暂时没有主机';
$lang->zahost->templateEmpty = '暂时没有模板';

$lang->zahost->statusList['ready']  = '准备中';
$lang->zahost->statusList['online'] = '已上架';

$lang->zahost->virtualSoftware = '虚拟化软件';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->zaHostType                 = '主机类型';
$lang->zahost->zaHostTypeList['physical'] = '实体主机';

$lang->zahost->confirmDelete           = '是否删除该主机记录？';
$lang->zahost->confirmDeleteVMTemplate = '是否删除该虚拟机模板？';

$lang->zahost->versionList = array();
$lang->zahost->versionList['winxp']['all']          = 'Windows XP';
$lang->zahost->versionList['win7']['home']          = '家庭版';
$lang->zahost->versionList['win7']['professional']  = '专业版';
$lang->zahost->versionList['win7']['enterprise']    = '企业版';
$lang->zahost->versionList['win7']['ultimate']      = '旗舰版';
$lang->zahost->versionList['win10']['home']         = '家庭版';
$lang->zahost->versionList['win10']['professional'] = '专业版';
$lang->zahost->versionList['win10']['enterprise']   = '企业版';
$lang->zahost->versionList['win10']['ultimate']     = '旗舰版';
$lang->zahost->versionList['win11']['home']         = '家庭版';
$lang->zahost->versionList['win11']['professional'] = '专业版';
$lang->zahost->versionList['win11']['enterprise']   = '企业版';
$lang->zahost->versionList['win11']['ultimate']     = '旗舰版';
$lang->zahost->versionList['winServer']['2008']     = '2008';
$lang->zahost->versionList['winServer']['2012']     = '2012';
$lang->zahost->versionList['winServer']['2016']     = '2016';
$lang->zahost->versionList['winServer']['2019']     = '2019';
$lang->zahost->versionList['debian']['9']           = '9';
$lang->zahost->versionList['debian']['10']          = '10';
$lang->zahost->versionList['debian']['11']          = '11';
$lang->zahost->versionList['ubuntu']['16']          = '16';
$lang->zahost->versionList['ubuntu']['18']          = '18';
$lang->zahost->versionList['ubuntu']['20']          = '20';
$lang->zahost->versionList['centos']['6']           = '6';
$lang->zahost->versionList['centos']['7']           = '7';
$lang->zahost->versionList['centos']['8']           = '8';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = '『%s』格式不正确！';
$lang->zahost->notice->registerCommand = '宿主机注册命令：./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = '加载中...';
$lang->zahost->notice->noImage         = '无可用的镜像文件';
