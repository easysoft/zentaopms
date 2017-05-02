<?php
$lang->backup->common   = '备份';
$lang->backup->index    = '备份首页';
$lang->backup->history  = '备份历史';
$lang->backup->delete   = '删除备份';
$lang->backup->backup   = '备份';
$lang->backup->restore  = '还原';
$lang->backup->change   = '修改保留时间';
$lang->backup->changeAB = '修改';

$lang->backup->time  = '备份时间';
$lang->backup->files = '备份文件';
$lang->backup->size  = '大小';

$lang->backup->waitting       = '<span id="backupType"></span>正在进行中，请稍候...';
$lang->backup->confirmDelete  = '是否删除备份？';
$lang->backup->confirmRestore = '是否还原该备份？';
$lang->backup->holdDays       = '备份保留最近 %s 天';
$lang->backup->restoreTip     = '还原功能只还原附件和数据库，如果需要还原代码，可以手动还原。';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = '备份成功！';
$lang->backup->success->restore = '还原成功！';

$lang->backup->error = new stdclass();
$lang->backup->error->noWritable  = "<code>%s</code> 不可写！请检查该目录权限，否则无法备份。";
$lang->backup->error->noDelete    = "文件 %s 无法删除，修改权限或手工删除。";
$lang->backup->error->restoreSQL  = "数据库还原失败，错误：%s";
$lang->backup->error->restoreFile = "附件还原失败，错误：%s";
$lang->backup->error->backupFile  = "附件备份失败，错误：%s";
$lang->backup->error->backupCode  = "代码备份失败，错误：%s";
