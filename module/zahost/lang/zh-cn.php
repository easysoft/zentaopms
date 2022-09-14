<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = '宿主机';
$lang->zahost->browse         = '主机列表';
$lang->zahost->create         = '添加宿主机';
$lang->zahost->view           = '主机详情';
$lang->zahost->edit           = '编辑';
$lang->zahost->editAction     = '编辑主机';
$lang->zahost->delete         = '删除';
$lang->zahost->deleteAction   = '删除主机';
$lang->zahost->byQuery        = '搜索';
$lang->zahost->all            = '所有主机';
$lang->zahost->browseTemplate = '虚拟机模板列表';
$lang->zahost->createTemplate = '创建虚拟机模板';

$lang->zahost->name        = '名称';
$lang->zahost->IP          = 'IP';
$lang->zahost->publicIP    = 'IP';
$lang->zahost->memory      = '内存';
$lang->zahost->cpuCores    = 'CPU核心数';
$lang->zahost->diskSize    = '硬盘容量';
$lang->zahost->instanceNum = '最大实例数';
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
$lang->zahost->imageFile  = '镜像文件';

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
$lang->zahost->vmTemplate->imageFile  = $lang->zahost->imageFile;

$lang->zahost->langList = array();
$lang->zahost->langList['zh_cn'] = '简体中文';
$lang->zahost->langList['zh_tw'] = '繁体中文';
$lang->zahost->langList['en_us'] = '美式英语';

$lang->zahost->empty         = '暂时没有主机';
$lang->zahost->templateEmpty = '暂时没有模板';

$lang->zahost->statusList['online'] = '已上架';

$lang->zahost->virtualSoftware = '虚拟化软件';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->zaHostType                 = '主机类型';
$lang->zahost->zaHostTypeList['physical'] = '实体主机';
//$lang->zahost->zaHostTypeList['virtual']  = '虚拟主机';

$lang->zahost->confirmDelete = '是否删除该主机记录？';

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
$lang->zahost->notice->ip                = '『%s』格式不正确！';
$lang->zahost->notice->pingError         = '无法连接！请检查IP地址、服务器进程和端口状态。';
$lang->zahost->notice->registerHostError = '向ZAgenteb服务器注册宿主机失败！';
