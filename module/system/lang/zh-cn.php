<?php
$lang->system->common          = '仪表盘';
$lang->system->dashboard       = 'DevOps平台仪表盘';
$lang->system->systemInfo      = '系统信息';
$lang->system->dbManagement    = '数据库管理';
$lang->system->ldapManagement  = 'LDAP';
$lang->system->dbList          = '数据库列表';
$lang->system->configDomain    = '域名管理';
$lang->system->ossView         = '对象存储管理';
$lang->system->dbName          = '名称';
$lang->system->dbStatus        = '状态';
$lang->system->dbType          = '类型';
$lang->system->action          = '操作';
$lang->system->management      = '管理';
$lang->system->visit           = '访问';
$lang->system->close           = '关闭';
$lang->system->installLDAP     = '安装LDAP';
$lang->system->editLDAP        = '编辑';
$lang->system->LDAPInfo        = 'LDAP信息';
$lang->system->accountInfo     = '账号信息';
$lang->system->advance         = '高级';
$lang->system->verify          = '校验';
$lang->system->copy            = '复制';
$lang->system->copySuccess     = '已复制到剪切板';
$lang->system->cneStatus       = '平台状态';
$lang->system->cneStatistic    = '资源统计';
$lang->system->latestDynamic   = '最新动态';
$lang->system->nodeQuantity    = '节点数';
$lang->system->serviceQuantity = '服务数';
$lang->system->cpuUsage        = 'CPU（核）';
$lang->system->memUsage        = '内存（GB）';

/* LDAP */
$lang->system->LDAP = new stdclass;
$lang->system->LDAP->info             = 'LDAP信息';
$lang->system->LDAP->ldapEnabled      = '启用LDAP';
$lang->system->LDAP->ldapQucheng      = '渠成内置';
$lang->system->LDAP->ldapSource       = '来源';
$lang->system->LDAP->ldapInstall      = '安装并启用';
$lang->system->LDAP->ldapUpdate       = '更新';
$lang->system->LDAP->accountInfo      = '账号信息';
$lang->system->LDAP->account          = '账号';
$lang->system->LDAP->password         = '密码';
$lang->system->LDAP->ldapUsername     = '用户名';
$lang->system->LDAP->ldapName         = '名称';
$lang->system->LDAP->host             = '主机';
$lang->system->LDAP->port             = '端口';
$lang->system->LDAP->account          = '账号';
$lang->system->LDAP->password         = '密码';
$lang->system->LDAP->ldapRoot         = '根节点';
$lang->system->LDAP->filterUser       = '用户过滤';
$lang->system->LDAP->email            = '邮件字段';
$lang->system->LDAP->extraAccount     = '用户名字段';
$lang->system->LDAP->ldapAdvance      = '高级设置';
$lang->system->LDAP->updateLDAP       = '更新LDAP';
$lang->system->LDAP->updateInstance   = '更新已关联LDAP的服务';
$lang->system->LDAP->updatingProgress = '更新中...剩余 %s 个服务。';

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = '渠成内置';
$lang->system->ldapTypeList['extra']   = '外部映射';

/* OSS */
$lang->system->oss = new stdclass;
$lang->system->oss->common    = '对象存储';
$lang->system->oss->appURL    = '应用地址';
$lang->system->oss->user      = '用户名';
$lang->system->oss->password  = '密码';
$lang->system->oss->manage    = '管理';
$lang->system->oss->apiURL    = 'API地址';
$lang->system->oss->accessKey = 'Access Key';
$lang->system->oss->secretKey = 'Secret Key';

/* SMTP */
$lang->system->SMTP = new stdclass;
$lang->system->SMTP->common   = '邮箱配置';
$lang->system->SMTP->enabled  = '启用SMTP';
$lang->system->SMTP->install  = '安装';
$lang->system->SMTP->update   = '更新';
$lang->system->SMTP->edit     = '编辑';
$lang->system->SMTP->editSMTP = '编辑SMTP';
$lang->system->SMTP->account  = '发信邮箱';
$lang->system->SMTP->password = '密码';
$lang->system->SMTP->host     = 'SMTP服务器';
$lang->system->SMTP->port     = 'SMTP端口';
$lang->system->SMTP->save     = '保存';

/* Domain */
$lang->system->customDomain = '新域名';
$lang->system->certPem      = '公钥证书';
$lang->system->certKey      = '私钥';

$lang->system->domain = new stdclass;
$lang->system->domain->common        = '域名管理';
$lang->system->domain->editDomain    = '修改域名配置';
$lang->system->domain->config        = '配置域名和证书';
$lang->system->domain->currentDomain = '当前域名';
$lang->system->domain->oldDomain     = '旧域名';
$lang->system->domain->newDomain     = '新域名';
$lang->system->domain->expiredDate   = '证书过期时间';
$lang->system->domain->uploadCert    = '上传证书（仅支持泛域名证书）';

$lang->system->domain->notReuseOldDomain     = '使用自定义域名后无法改回默认域名';
$lang->system->domain->setDNS                = '建议修改域名前请先进行DNS解析，';
$lang->system->domain->dnsHelperLink         = '点击查看帮助文档';
$lang->system->domain->updateInstancesDomain = '更新已安装服务的域名';
$lang->system->domain->totalOldDomain        = '共 %s 个。';
$lang->system->domain->updatingProgress      = '更新中...，剩余 %s 个,';
$lang->system->domain->updating              = '更新中...';

$lang->system->SLB = new stdclass;
$lang->system->SLB->common        = '负载均衡';
$lang->system->SLB->config        = '配置负载均衡';
$lang->system->SLB->edit          = '修改负载均衡';
$lang->system->SLB->ipPool        = 'IP段';
$lang->system->SLB->ipPoolExample = '示例：192.168.10.0/24或者192.168.10.0-192.168.10.100';
$lang->system->SLB->installing    = '正在配置负载均衡';
$lang->system->SLB->leftSeconds   = '预计剩余';
$lang->system->SLB->second        = '秒';

$lang->system->notices = new stdclass;
$lang->system->notices->success               = '成功';
$lang->system->notices->fail                  = '失败';
$lang->system->notices->attention             = '注意';
$lang->system->notices->noLDAP                = '找不到LDAP配置数据';
$lang->system->notices->ldapUsed              = '%s个服务已关联了LDAP';
$lang->system->notices->ldapInstallSuccess    = 'LDAP安装成功';
$lang->system->notices->ldapUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->confirmUpdateLDAP     = '修改LDAP后，会自动更新并重启已关联的服务，确定要修改吗？';
$lang->system->notices->verifyLDAPSuccess     = '校验LDAP成功！';
$lang->system->notices->fillAllRequiredFields = '请填写全部必填项！';
$lang->system->notices->smtpInstallSuccess    = 'LDAP安装成功';
$lang->system->notices->smtpUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->smtpWhiteList         = '为防止邮件被屏蔽，请在邮件服务器里面将发信邮箱设为白名单';
$lang->system->notices->smtpAuthCode          = '有些邮箱要填写单独申请的授权码，具体请到邮箱相关设置查询';
$lang->system->notices->smtpUsed              = '%s 个服务关联了SMTP';
$lang->system->notices->verifySMTPSuccess     = '校验成功！';
$lang->system->notices->pleaseCheckSMTPInfo   = '校验失败！请检查用户名和密码是否正确';
$lang->system->notices->confirmUpdateDomain   = '修改域名后，会自动更新已安装服务的域名，确定要修改吗？';
$lang->system->notices->updateDomainSuccess   = '域名修改成功。';
$lang->system->notices->configSLBSuccess      = '配置负载均衡成功。';
$lang->system->notices->validCert             = '校验成功';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB                  = '找不到该数据库';
$lang->system->errors->notFoundLDAP                = '找不到LDAP数据';
$lang->system->errors->dbNameIsEmpty               = '数据库名为空';
$lang->system->errors->notSupportedLDAP            = '暂不支持该类型的LDAP';
$lang->system->errors->failToInstallLDAP           = '安装内置LDAP失败';
$lang->system->errors->failToInstallExtraLDAP      = '对接外部LDAP失败';
$lang->system->errors->failToUpdateExtraLDAP       = '更新外部LDAP失败';
$lang->system->errors->failToUninstallQuChengLDAP  = '卸载渠成内部LDAP失败';
$lang->system->errors->failToUninstallExtraLDAP    = '卸载外部LDAP失败';
$lang->system->errors->failToDeleteLDAPSnippet     = '删除LDAP片段失败';
$lang->system->errors->verifyLDAPFailed            = '校验LDAP失败';
$lang->system->errors->LDAPLinked                  = '有服务已经关联了LDAP';
$lang->system->errors->SMTPLinked                  = '有服务已经关联了SMTP服务';
$lang->system->errors->failGetOssAccount           = '获取对象存储账号失败';
$lang->system->errors->failToInstallSMTP           = '安装SMTP失败';
$lang->system->errors->failToUninstallSMTP         = '卸载SMTP失败';
$lang->system->errors->failToUpdateSMTP            = '更新SMTP失败';
$lang->system->errors->verifySMTPFailed            = '校验SMTP失败';
$lang->system->errors->notFoundSMTPApp             = '找不到SMTP代理应用';
$lang->system->errors->notFoundSMTPService         = '找不到SMTP代理服务';
$lang->system->errors->domainIsRequired            = '必须填写域名';
$lang->system->errors->invalidDomain               = '无效的域名或格式错误。域名只允许小写字母、数字、点(.)和中横线(-)';
$lang->system->errors->failToUpdateDomain          = '更新域名失败';
$lang->system->errors->forbiddenOriginalDomain     = '不能修改为平台默认域名';
$lang->system->errors->newDomainIsSameWithOld      = '新域名不能与原域名相同';
$lang->system->errors->failedToConfigSLB           = '配置负载均衡失败';
$lang->system->errors->wrongIPRange                = 'IP段格式错误，请参照示例格式，' . $lang->system->SLB->ipPoolExample;
$lang->system->errors->ippoolRequired              = 'IP段不能为空';
$lang->system->errors->failedToInstallSLBComponent = '安装负载均衡组件失败';
$lang->system->errors->tryReinstallSLB             = '安装负载均衡组件超时，请重试。';

$lang->system->backup = new stdclass();
$lang->system->backup->common       = '系统备份';
$lang->system->backup->shortCommon  = '备份';
$lang->system->backup->systemInfo   = '系统信息';
$lang->system->backup->index        = '备份首页';
$lang->system->backup->history      = '备份记录';
$lang->system->backup->delete       = '删除备份';
$lang->system->backup->backup       = '备份';
$lang->system->backup->change       = '保留时间';
$lang->system->backup->changeAB     = '修改';
$lang->system->backup->rmPHPHeader  = '去除安全设置';
$lang->system->backup->setting      = '设置';
$lang->system->backup->backupPerson = '备份人';
$lang->system->backup->type         = '备份类型';

$lang->system->backup->settingAction = '备份设置';

$lang->system->backup->name           = '名称';
$lang->system->backup->currentVersion = '当前版本';
$lang->system->backup->latestVersion  = '最新版本';

$lang->system->backup->files    = '备份文件';
$lang->system->backup->allCount = '总文件数';
$lang->system->backup->count    = '备份文件数';
$lang->system->backup->size     = '大小';
$lang->system->backup->status   = '状态';
$lang->system->backup->running  = '运行中';
$lang->system->backup->done     = '完成';

$lang->system->backup->backupName   = '备份名称：';
$lang->system->backup->backupSql    = '备份数据库：';
$lang->system->backup->backupFile   = '备份附件：';
$lang->system->backup->restoreImage = '回滚平台镜像：';
$lang->system->backup->restoreSQL   = '回滚数据库：';
$lang->system->backup->restoreFile  = '回滚附件：';
$lang->system->backup->checkService = '检查服务：';

$lang->system->backup->upgrade  = '升级';
$lang->system->backup->rollback = '回滚';
$lang->system->backup->restart  = '重启';
$lang->system->backup->delete   = '删除';

$lang->system->backup->statusList['pending']    = '等待中';
$lang->system->backup->statusList['inprogress'] = '进行中';
$lang->system->backup->statusList['completed']  = '完成';
$lang->system->backup->statusList['failed']     = '失败';

$lang->system->backup->restoreProgress['doing'] = '进行中';
$lang->system->backup->restoreProgress['done']  = '完成';

$lang->system->backup->typeList['manual']  = '手动备份';
$lang->system->backup->typeList['upgrade'] = '升级前自动备份';
$lang->system->backup->typeList['restore'] = '回滚前自动备份';

$lang->system->backup->waitting        = '备份正在进行中，请稍候...';
$lang->system->backup->waittingStore   = '正在还原应用数据，请稍候...';
$lang->system->backup->progress        = '备份中，进度（%d/%d）';
$lang->system->backup->progressStore   = '还原中，进度（%d/%d）';
$lang->system->backup->progressSQL     = '备份中，已备份%s';
$lang->system->backup->progressAttach  = '备份中，共有%s个文件，已经备份%s个';
$lang->system->backup->progressCode    = '代码备份中，共有%s个文件，已经备份%s个';
$lang->system->backup->confirmDelete   = '是否删除该备份？';
$lang->system->backup->confirmRestore  = '平台还原过程中需要重启，这将导致您当前的所有操作中断并且无法恢复，您确定要继续吗？';
$lang->system->backup->holdDays        = '备份保留最近 %s 天';
$lang->system->backup->copiedFail      = '复制失败的文件：';
$lang->system->backup->restoreTip      = '还原功能只还原数据库。';
$lang->system->backup->versionInfo     = '点击查看新版本介绍';
$lang->system->backup->confirmUpgrade  = '请确认是否升级渠成平台？';
$lang->system->backup->upgrading       = '升级中';
$lang->system->backup->backupTitle     = '正在备份 渠成平台...';
$lang->system->backup->restoreTitle    = '正在回滚 渠成平台...';
$lang->system->backup->backingUp       = '进行中';
$lang->system->backup->restoring       = '进行中';

$lang->system->backup->success = new stdclass();
$lang->system->backup->success->upgrade = '升级成功！';
$lang->system->backup->success->degrade = '降级成功！';

$lang->system->backup->error = new stdclass();
$lang->system->backup->error->backupFail        = "备份失败!";
$lang->system->backup->error->restoreFail       = "还原失败!";
$lang->system->backup->error->upgradeFail       = "升级失败!";
$lang->system->backup->error->upgradeOvertime   = "升级超时!";
$lang->system->backup->error->degradeFail       = "降级失败!";
$lang->system->backup->error->beenLatestVersion = "已经是最新版，无需升级!";
$lang->system->backup->error->requireVersion    = "必须上传版本号!";
