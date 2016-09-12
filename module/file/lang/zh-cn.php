<?php
/**
 * The file module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: zh-cn.php 4630 2013-04-10 05:54:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = '附件';
$lang->file->uploadImages  = '多图上传';
$lang->file->download      = '下载附件';
$lang->file->edit          = '重命名';
$lang->file->inputFileName = '请输入附件名称';
$lang->file->delete        = '删除附件';
$lang->file->label         = '标题：';
$lang->file->maxUploadSize = "<span class='red'>%s</span>";
$lang->file->applyTemplate = "应用模板";
$lang->file->tplTitle      = "模板名称";
$lang->file->setPublic     = "设置公共模板";
$lang->file->exportFields  = "要导出字段";
$lang->file->defaultTPL    = "默认模板";
$lang->file->setExportTPL  = "设置";

$lang->file->errorNotExists   = "<span class='red'>文件夹 '%s' 不存在</span>";
$lang->file->errorCanNotWrite = "<span class='red'>文件夹 '%s' 不可写,请改变文件夹的权限。在linux中输入指令:sudo chmod -R 777 '%s'</span>";
$lang->file->confirmDelete    = " 您确定删除该附件吗？";
$lang->file->errorFileSize    = " 文件大小已经超过限制，可能不能成功上传！";
$lang->file->errorFileUpload  = " 文件上传失败，文件大小可能超出限制";
$lang->file->dangerFile       = " 您选择的文件存在安全风险，系统将不予上传。";
$lang->file->errorSuffix      = '压缩包格式错误，只能上传zip压缩包！';
$lang->file->errorExtract     = '解压缩失败！可能文件已经损坏，或压缩包里含有非法上传文件。';
$lang->file->uploadImagesExplain = <<<EOD
<p>1、上传文件为包含图片的zip压缩包，程序会以文件名作为标题，以图片作为内容。</p>
<p>2、如果文件名开头含有 数字+下划线，以方便排序，程序会将他们忽略。</p>
<p>3、图片格式：jpg|jpeg|gif|png。</p>
EOD;
