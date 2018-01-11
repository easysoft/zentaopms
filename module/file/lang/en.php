<?php
/**
 * The file module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = 'File';
$lang->file->uploadImages  = 'Batch Upload Images';
$lang->file->download      = 'Download Files';
$lang->file->uploadDate    = 'Uploaded on';
$lang->file->edit          = 'Rename';
$lang->file->inputFileName = 'Enter a File Name';
$lang->file->delete        = 'Delete File';
$lang->file->label         = 'Label:';
$lang->file->maxUploadSize = "<span class='red'>%s</span>";
$lang->file->applyTemplate = "Apply a Template";
$lang->file->tplTitle      = "Template Name";
$lang->file->setPublic     = "Set Public Template";
$lang->file->exportFields  = "Fileds to be Exported";
$lang->file->defaultTPL    = "Default Template";
$lang->file->setExportTPL  = "Settings";
$lang->file->preview       = "Preview";
$lang->file->addFile       = 'Add';
$lang->file->beginUpload   = 'Upload';
$lang->file->uploadSuccess = 'Uploaded!';

$lang->file->pathname  = 'Path Name';
$lang->file->title     = 'Title';
$lang->file->extension = 'Extension';
$lang->file->size      = 'Size';
$lang->file->addedBy   = 'Added By';
$lang->file->addedDate = 'Added On';
$lang->file->downloads = 'Downloads';
$lang->file->extra     = 'Extra';

$lang->file->dragFile         = 'Please drag here.';
$lang->file->errorNotExists   = "<span class='red'>'%s' is not found.</span>";
$lang->file->errorCanNotWrite = "<span class='red'>'%s' is not writable. Please change its permission. Enter sudo chmod -R 777 '%s'</span> in Linux.";
$lang->file->confirmDelete    = " Do you want to delete it?";
$lang->file->errorFileSize    = " File size exceeds the limit. It cannot be uploaded!";
$lang->file->errorFileUpload  = " Uploading failed. File size might exceeds the limit.";
$lang->file->errorFileFormate = " Uploading failed, file format is limited.";
$lang->file->errorFileMove    = " Uploading failed, there was an error when moving file.";
$lang->file->dangerFile       = " File has been rejected to upload for security issues.";
$lang->file->errorSuffix      = 'Format is incorrect. .zip files ONLY!';
$lang->file->errorExtract     = 'Extracting file failed. File might be damaged or invalid files in the zip package.';
$lang->file->uploadImagesExplain = 'Note: upload .jpg, .jpeg, .gif, and .png images. The image name will be taken as the title of the story and the image as its content.';
