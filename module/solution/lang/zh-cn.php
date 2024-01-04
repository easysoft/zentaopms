<?php
$lang->solution->market = new stdclass;
$lang->solution->market->browse = '解决方案市场';
$lang->solution->market->view   = '解决方案详情';

$lang->solution->name = '名称';

$lang->solution->browse        = '已安装';
$lang->solution->view          = '解决方案详情';
$lang->solution->detail        = '查看';
$lang->solution->progress      = '安装进度';
$lang->solution->install       = '安装';
$lang->solution->background    = '后台安装';
$lang->solution->cancelInstall = '取消安装';
$lang->solution->uninstall     = '卸载';
$lang->solution->retryInstall  = '重试';
$lang->solution->nextStep      = '下一步';
$lang->solution->config        = '配置';

$lang->solution->introduction = '基本介绍';
$lang->solution->scenes       = '适用场景';
$lang->solution->diagram      = '架构图';
$lang->solution->includedApp  = '包含应用';
$lang->solution->features     = '方案亮点';
$lang->solution->relatedLinks = '相关链接';
$lang->solution->customers    = '典型客户';
$lang->solution->apps         = '安装的应用';
$lang->solution->externalApps = '外部应用';
$lang->solution->resources    = '资源占用';

$lang->solution->editName = '修改名称';

$lang->solution->chooseApp           = '请选择要安装的应用';
$lang->solution->noInstalledSolution = '还没有安装解决方案';
$lang->solution->toInstall           = '去安装';

$lang->solution->notices = new stdclass;
$lang->solution->notices->fail                 = '失败';
$lang->solution->notices->success              = '成功';
$lang->solution->notices->creatingSolution     = '正在创建解决方案。';
$lang->solution->notices->uninstallingSolution = '正在卸载解决方案';
$lang->solution->notices->installingApp        = '正在安装：';
$lang->solution->notices->installationSuccess  = '解决方案安装成功！';
$lang->solution->notices->cancelInstall        = '确定要取消安装吗？';
$lang->solution->notices->confirmToUninstall   = '确定要卸载吗？';
$lang->solution->notices->confirmReinstall     = '确定要重试安装吗？';

$lang->solution->errors = new stdclass;
$lang->solution->errors->error                = '错误';
$lang->solution->errors->notFound             = '找不到相关数据';
$lang->solution->errors->failToInstallApp     = '安装%s应用失败';
$lang->solution->errors->timeout              = '安装超时';
$lang->solution->errors->failToUninstallApp   = '卸载%s应用失败';
$lang->solution->errors->hasInstallationError = '安装过程中发生错误';
$lang->solution->errors->notFoundAppByVersion = '找不到%s版本的%s应用';
$lang->solution->errors->notEnoughResource    = '资源不足, 请增加配置或释放其它资源后重试。';

$lang->solution->installationErrors = array();
$lang->solution->installationErrors['waiting']           = '安装未开始。';
$lang->solution->installationErrors['installing']        = '正在安装中。';
$lang->solution->installationErrors['uninstalling']      = '安装已取消。';
$lang->solution->installationErrors['cneError']          = '安装失败。';
$lang->solution->installationErrors['timeout']           = '安装超时。';
$lang->solution->installationErrors['notFoundApp']       = '找不到待安装的应用。';
$lang->solution->installationErrors['notEnoughResource'] = '资源不足, 请增加配置或释放其它资源后重试。';
