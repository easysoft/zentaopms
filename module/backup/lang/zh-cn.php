<?php
$lang->backup->common      = '备份';
$lang->backup->name        = '备份名称';
$lang->backup->index       = '备份列表';
$lang->backup->history     = '备份历史';
$lang->backup->delete      = '删除备份';
$lang->backup->backup      = '开始备份';
$lang->backup->restore     = '还原';
$lang->backup->change      = '保留天数';
$lang->backup->changeAB    = '修改';
$lang->backup->rmPHPHeader = '去除安全设置';
$lang->backup->setting     = '设置';

$lang->backup->restoreAction = '还原备份';
$lang->backup->settingAction = '备份设置';

$lang->backup->time     = '备份时间';
$lang->backup->files    = '备份文件';
$lang->backup->allCount = '总文件数';
$lang->backup->count    = '备份文件数';
$lang->backup->size     = '大小';
$lang->backup->status   = '状态';

$lang->backup->statusList['success'] = '成功';
$lang->backup->statusList['fail']    = '失败';

$lang->backup->settingDir = '备份目录';
$lang->backup->settingList['nofile'] = '不备份附件和代码';
$lang->backup->settingList['nosafe'] = '不需要防下载PHP文件头';

global $config;
if($config->inContainer) $lang->backup->settingList['nofile'] = '不备份附件';

$lang->backup->waiting          = '<span id="backupType"></span>正在进行中，请稍候...';
$lang->backup->progressSQL      = '<p>SQL备份中，已备份%s</p>';
$lang->backup->progressAttach   = '<p>SQL备份完成</p><p>附件备份中，共有%s个文件，已经备份%s个</p>';
$lang->backup->progressCode     = '<p>SQL备份完成</p><p>附件备份完成</p><p>代码备份中，共有%s个文件，已经备份%s个</p>';
$lang->backup->confirmDelete    = '是否删除备份？';
$lang->backup->confirmRestore   = '是否还原该备份？';
$lang->backup->holdDays         = '备份保留最近 %s 天';
$lang->backup->copiedFail       = '复制失败的文件：';
$lang->backup->restoreTip       = '还原功能只还原附件和数据库，如果需要还原代码，可以手动还原。';
$lang->backup->insufficientDisk = '磁盘空间小于NEED_SPACEG，可能会导致备份空间不足或影响使用，请处理后再试。';
$lang->backup->ongoBackup       = '继续备份';
$lang->backup->cancelBackup     = '取消备份';
$lang->backup->getSpaceLoading  = '正在计算备份空间';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = '备份成功！';
$lang->backup->success->restore = '还原成功！';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir     = '备份目录不存在，也无法创建该目录';
$lang->backup->error->noWritable      = "<code>%s</code> 不可写！请检查该目录权限，否则无法备份。";
$lang->backup->error->plainNoWritable = "%s 不可写！请检查该目录权限，否则无法备份。";
$lang->backup->error->noDelete        = "文件 %s 无法删除，修改权限或手工删除。";
$lang->backup->error->restoreSQL      = "数据库还原失败，错误：%s";
$lang->backup->error->restoreFile     = "附件还原失败，错误：%s";
$lang->backup->error->backupFile      = "附件备份失败，错误：%s";
$lang->backup->error->backupCode      = "代码备份失败，错误：%s";
$lang->backup->error->timeout         = "备份超时";

$lang->backup->notice = new stdclass();
$lang->backup->notice->higherVersion     = '还原的版本高于当前运行版本，请更新镜像版本到%s后再还原。';
$lang->backup->notice->lowerVersion      = '还原的版本低于当前运行版本，还原后会执行升级流程。';
$lang->backup->notice->unknownVersion    = '未从当前备份中读取到版本信息，是否还原该备份？';
$lang->backup->notice->settingsInQuickon = '您目前使用的是禅道DevOps平台版，无需设置其他选项。';
$lang->backup->notice->gotoUpgrade       = '还原成功，即将跳转到升级页面，如未跳转请手动刷新页面';