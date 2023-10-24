<?php
$lang->host->common       = '主机';
$lang->host->browse       = '主机列表';
$lang->host->create       = '添加主机';
$lang->host->view         = '主机详情';
$lang->host->edit         = '编辑';
$lang->host->editAction   = '编辑主机';
$lang->host->delete       = '删除';
$lang->host->deleteAction = '删除主机';
$lang->host->treemap      = '主机拓扑图';
$lang->host->changeStatus = '上架/下架';
$lang->host->byQuery      = '搜索';
$lang->host->reason       = '原因';

$lang->host->name         = '名称';
$lang->host->type         = '类型';
$lang->host->hardwareType = '硬件类型';
$lang->host->group        = '主机分组';
$lang->host->admin        = '管理账号';
$lang->host->cabinet      = '机柜';
$lang->host->intranet     = '内网IP';
$lang->host->extranet     = '外网IP';
$lang->host->mac          = 'MAC';
$lang->host->cpuBrand     = 'CPU品牌';
$lang->host->cpuModel     = 'CPU型号';
$lang->host->cpuNumber    = 'CPU数量';
$lang->host->cpuCores     = '单CPU核心数';
$lang->host->cpuRate      = 'CPU频率';
$lang->host->memory       = '内存大小';
$lang->host->serverRoom   = '机房';

$lang->host->createdBy   = '由谁创建';
$lang->host->createdDate = '创建时间';
$lang->host->editedBy    = '由谁编辑';
$lang->host->editedDate  = '编辑时间';
$lang->host->all         = '全部主机';

$lang->host->empty = '暂时没有主机';

$lang->host->groupMaintenance = '分组维护';

$lang->host->status = '状态';
$lang->host->statusList['online']  = '已上架';
$lang->host->statusList['busy']    = '已超载';
$lang->host->statusList['offline'] = '已下架';

$lang->host->online  = '上架';
$lang->host->busy    = '超载';
$lang->host->offline = '下架';

$lang->host->onlineReason  = '上架原因';
$lang->host->offlineReason = '下架原因';

$lang->host->unitList['GB'] = 'GB';
$lang->host->unitList['TB'] = 'TB';

$lang->host->cpuBrandList[''] = '';
$lang->host->cpuBrandList['intel'] = '英特尔';
$lang->host->cpuBrandList['amd']   = 'AMD';

$lang->host->database = '数据库';
$lang->host->databaseList['']             = '';
$lang->host->databaseList['mysql55']      = 'MySQL 5.5';
$lang->host->databaseList['mysql56']      = 'MySQL 5.6';
$lang->host->databaseList['mysql57']      = 'MySQL 5.7';
$lang->host->databaseList['mysql80']      = 'MySQL 8.0';
$lang->host->databaseList['oracle10g']    = 'Oracle 10g';
$lang->host->databaseList['oracle11g']    = 'Oracle 11g';
$lang->host->databaseList['mongodb30']    = 'MongoDB 3.0';
$lang->host->databaseList['mongodb34']    = 'MongoDB 3.4';
$lang->host->databaseList['postgresql95'] = 'PostgreSQL 9.5';
$lang->host->databaseList['postgresql96'] = 'PostgreSQL 9.6';

$lang->host->webserver = 'Web Server';
$lang->host->webserverList['']           = '';
$lang->host->webserverList['apache2425'] = 'Apache 2.4.25';
$lang->host->webserverList['apache2426'] = 'Apache 2.4.26';
$lang->host->webserverList['apache2427'] = 'Apache 2.4.27';
$lang->host->webserverList['nginx181']   = 'Nginx 1.8.1';
$lang->host->webserverList['nginx1102']  = 'Nginx 1.10.2';
$lang->host->webserverList['nginx1115']  = 'Nginx 1.11.5';
$lang->host->webserverList['iis6']       = 'IIS 6';
$lang->host->webserverList['iis7']       = 'IIS 7';
$lang->host->webserverList['iis8']       = 'IIS 8';
$lang->host->webserverList['iis10']      = 'IIS 10';

$lang->host->language = '语言环境';
$lang->host->languageList['']   = '';
$lang->host->languageList['php56'] = 'PHP 5.6';
$lang->host->languageList['php70'] = 'PHP 7.0';
$lang->host->languageList['java7'] = 'JAVA 7';
$lang->host->languageList['java8'] = 'JAVA 8';
$lang->host->languageList['c#3']   = 'C# 3.0';
$lang->host->languageList['c#4']   = 'C# 4.0';

$lang->host->featureBar['browse']['all']        = $lang->host->all;
$lang->host->featureBar['browse']['serverroom'] = '物理拓扑图';
$lang->host->featureBar['browse']['group']      = '分组拓扑图';

$lang->host->serverModel              = '服务器型号';
$lang->host->hostType                 = '主机类型';
$lang->host->hostTypeList['physical'] = '实体主机';
$lang->host->hostTypeList['virtual']  = '虚拟主机';

$lang->host->osVersion   = '系统版本';
$lang->host->osName      = '操作系统';
$lang->host->zap         = 'agent端口';
$lang->host->instanceNum = '最大实列数';
$lang->host->pri         = '优先级';
$lang->host->tags        = '平台标签';
$lang->host->provider    = '供应商';
$lang->host->bridgeID    = '虚拟网桥';

$lang->host->osNameList['linux']   = 'Linux';
$lang->host->osNameList['windows'] = 'Microsoft Windows';
$lang->host->osNameList['solaris'] = 'Solaris';
$lang->host->osNameList['netware'] = 'Novell NetWare';
$lang->host->osNameList['esx']     = 'VMware ESX';
$lang->host->osNameList['other']   = 'Other';

$lang->host->tagsList['vm']     = 'VM';
$lang->host->tagsList['native'] = 'Native';
$lang->host->providerList['native'] = 'Native';

$lang->host->linux   = 'Linux';
$lang->host->windows = 'Microsoft Windows';
$lang->host->solaris = 'Solaris';
$lang->host->netware = 'Novell NetWare';
$lang->host->esx     = 'VMware ESX';
$lang->host->other   = 'Other';

$lang->host->linuxList['']   = '';
$lang->host->linuxList['centos65'] = 'CentOS 6.5';
$lang->host->linuxList['centos66'] = 'CentOS 6.6';
$lang->host->linuxList['centos67'] = 'CentOS 6.7';
$lang->host->linuxList['centos70'] = 'CentOS 7.0';
$lang->host->linuxList['centos71'] = 'CentOS 7.1';

$lang->host->windowsList[''] = '';
$lang->host->windowsList['win10-pro']      = 'Windows 10 pro';
$lang->host->windowsList['winserver08x64'] = 'Windows Server2008 x64';
$lang->host->windowsList['winserver12']    = 'Windows Server2012';
$lang->host->windowsList['winserver16']    = 'Windows Server2016';

$lang->host->solarisList[''] = '';
$lang->host->solarisList['solaris10']    = 'Solaris 10';
$lang->host->solarisList['solaris10x64'] = 'Solaris 10 x64';
$lang->host->solarisList['solaris11']    = 'Solaris 11';

$lang->host->netwareList[''] = '';
$lang->host->netwareList['netware5'] = 'NetWare 5';
$lang->host->netwareList['netware6'] = 'NetWare 6';

$lang->host->esxList[''] = '';
$lang->host->esxList['esx']  = 'VMware ESX/ESXi 4';
$lang->host->esxList['esx5'] = 'VMware ESXi 5';
$lang->host->esxList['esx6'] = 'VMware ESXi 6';

$lang->host->otherList['']   = '';
$lang->host->otherList['freebsd']   = 'FreeBSD';
$lang->host->otherList['freebsdx64']   = 'FreeBSD X64';

$lang->host->diskSize   = '硬盘容量';
$lang->host->diskType   = '硬盘类型';
$lang->host->diskTypeList['hdd'] = '机械硬盘';
$lang->host->diskTypeList['ssd'] = '固态硬盘';

$lang->host->confirmDelete  = '是否删除该主机记录？';

$lang->host->notice = new stdclass();
$lang->host->notice->memory    = '内存大小只能为数字！';
$lang->host->notice->diskSize  = '硬盘容量只能为数字！';
$lang->host->notice->cpuNumber = 'CPU数量只能为数字！';
$lang->host->notice->cpuCores  = 'CPU核心数只能为数字！';
$lang->host->notice->int       = '『%s』应当是正整数！';
$lang->host->notice->ip        = '『%s』格式不正确！';
