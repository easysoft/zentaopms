<?php
/**
 * The file module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: zh-cn.php 4630 2013-04-10 05:54:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = '附件';
$lang->file->id            = '编号';
$lang->file->objectType    = '对象类型';
$lang->file->objectID      = '对象ID';
$lang->file->deleted       = '已删除';
$lang->file->uploadImages  = '多图上传';
$lang->file->download      = '下载附件';
$lang->file->uploadDate    = '上传时间：';
$lang->file->edit          = '重命名';
$lang->file->inputFileName = '请输入附件名称';
$lang->file->delete        = '删除附件';
$lang->file->label         = '标题：';
$lang->file->maxUploadSize = "（不超过%s）";
$lang->file->applyTemplate = "应用模板";
$lang->file->tplTitle      = "模板名称";
$lang->file->tplTitleAB    = "模板名称";
$lang->file->setPublic     = "设置公共模板";
$lang->file->exportFields  = "导出字段";
$lang->file->exportRange   = "导出范围";
$lang->file->defaultTPL    = "默认模板";
$lang->file->setExportTPL  = "设置";
$lang->file->preview       = "预览";
$lang->file->previewFile   = "预览附件";
$lang->file->addFile       = '添加文件';
$lang->file->beginUpload   = '开始上传';
$lang->file->uploadSuccess = '上传成功';
$lang->file->batchExport   = '分批导出';
$lang->file->downloadFile  = '下载';
$lang->file->playFailed    = '视频预览失败，请联系管理员';
$lang->file->exportData    = "导出数据";

$lang->file->pathname  = '路径';
$lang->file->title     = '标题';
$lang->file->fileName  = '文件名称';
$lang->file->untitled  = '未命名';
$lang->file->extension = '文件类型';
$lang->file->size      = '大小';
$lang->file->encoding  = '编码';
$lang->file->addedBy   = '由谁添加';
$lang->file->addedDate = '添加时间';
$lang->file->downloads = '下载次数';
$lang->file->extra     = '备注';

$lang->file->dragFile            = '请拖拽文件到此处';
$lang->file->childTaskTips       = "任务名称前有'>'标记的为子任务";
$lang->file->uploadImagesExplain = '注：请上传"jpg, jpeg, gif, png"格式的图片，程序会以文件名作为标题，以图片作为内容。';
$lang->file->uploadingImages     = '共有 <strong>%s</strong> 个文件正在上传';
$lang->file->saveAndNext         = '保存并跳转下一页';
$lang->file->importPager         = '共有<strong>%s</strong>条记录，当前第<strong>%s</strong>页，共有<strong>%s</strong>页';
$lang->file->importSummary       = "本次导入共有<strong id='totalAmount'>%s</strong>条记录，每页导入%s条，需要导入<strong id='times'>%s</strong>次";
$lang->file->accessDenied        = '您无权访问该附件！';

$lang->file->errorNotExists   = "<span class='text-red'>文件夹 '%s' 不存在</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>文件夹 '%s' 不可写,请改变文件夹的权限。在linux中输入指令: <span class='code'>sudo chmod -R 777 %s</span></span>";
$lang->file->confirmDelete    = " 您确定删除该附件吗？";
$lang->file->errorFileSize    = " 文件大小已经超过%s，可能不能成功上传！";
$lang->file->errorFileUpload  = " 文件上传失败，文件大小可能超出限制";
$lang->file->errorFileFormate = " 文件上传失败，文件格式不在规定范围内";
$lang->file->errorFileMove    = " 文件上传失败，移动文件时出错";
$lang->file->dangerFile       = " 您选择的文件存在安全风险，系统将不予上传。";
$lang->file->errorSuffix      = '压缩包格式错误，只能上传zip压缩包！';
$lang->file->errorExtract     = '解压缩失败！可能文件已经损坏，或压缩包里含有非法上传文件。';
$lang->file->errorUploadEmpty = '没有等待上传的文件';
$lang->file->fileNotFound     = '未找到该文件，可能物理文件已被删除！';
$lang->file->fileContentEmpty = '上传文件内容为空，请检查后重新上传。';
$lang->file->bizGuide         = '如需使用Excel导入导出功能，可升级到 %s';

$lang->file->uploadError[1] = "上传的文件大小超过了限制，请修改 php.ini 中 upload_max_filesize 与 post_max_size 选项限制的值";
$lang->file->uploadError[2] = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
$lang->file->uploadError[3] = '文件只有部分被上传,请重新上传';
$lang->file->uploadError[4] = '没有文件被上传';
$lang->file->uploadError[5] = '上传文件大小为0,请重新上传';
