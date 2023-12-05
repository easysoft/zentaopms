<?php
/**
 * The file module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license  ZPL (http://zpl.pub/page/zplv12.html)
 * @author   Nguyễn Quốc Nho <quocnho@gmail.com>
 * @package  file
 * @version  $Id: vi.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link  http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = 'File';
$lang->file->uploadImages  = 'Batch Upload Images';
$lang->file->download      = 'Tải về Files';
$lang->file->uploadDate    = 'Uploaded';
$lang->file->edit          = 'Rename File';
$lang->file->inputFileName = 'Enter File tên';
$lang->file->delete        = 'Xóa File';
$lang->file->label         = 'Label:';
$lang->file->maxUploadSize = "<span class='text-red'>%s</span>";
$lang->file->applyTemplate = "Apply Mẫu";
$lang->file->tplTitle      = "Template Name";
$lang->file->tplTitleAB    = "Templates";
$lang->file->setPublic     = "Set Public Mẫu";
$lang->file->exportFields  = "Fields";
$lang->file->exportRange   = "Data";
$lang->file->defaultTPL    = "Default";
$lang->file->setExportTPL  = "Thiết lập";
$lang->file->preview       = "Preview";
$lang->file->addFile       = 'Thêm';
$lang->file->beginUpload   = 'Click to Upload';
$lang->file->uploadSuccess = 'Uploaded!';
$lang->file->batchExport   = 'Export in batches';

$lang->file->pathname  = 'Path tên';
$lang->file->title     = 'Tiêu đề';
$lang->file->fileName  = 'File tên';
$lang->file->untitled  = 'Untitled';
$lang->file->extension = 'Định dạng';
$lang->file->size      = 'Size';
$lang->file->encoding  = 'Encoding';
$lang->file->addedBy   = 'Người thêm';
$lang->file->addedDate = 'Đã thêm';
$lang->file->downloads = 'Downloads';
$lang->file->extra     = 'Nhận xét';

$lang->file->dragFile            = 'Drag images here.';
$lang->file->childTaskTips       = 'It\'s a child task if there is a \'>\' before the name.';
$lang->file->uploadImagesExplain = 'Ghi chú: upload .jpg, .jpeg, .gif, or .png images. The image name will be the name of the story and the image will be the description.';
$lang->file->saveAndNext         = 'Save and Next';
$lang->file->importPager         = 'Total: <strong>%s</strong>. Page <strong>%s</strong> of <strong>%s</strong>';
$lang->file->importSummary       = "Import <strong id='totalAmount'>%s</strong> items  You can <strong>%s</strong> items/page, so you have to import <strong id='times'>%s</strong> times.";
$lang->file->accessDenied        = 'Access denied to this file!';

$lang->file->errorNotExists   = "<span class='text-red'>'%s' không là found.</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>'%s' không thể ghi. Vui lòng change its permission. Enter <span class='code'>sudo chmod -R 777 '%s'</span></span> in Linux.";
$lang->file->confirmDelete    = " Bạn có muốn xóa nó?";
$lang->file->errorFileSize    = " File size exceeds the limit. It cannot be uploaded!";
$lang->file->errorFileUpload  = " Uploading failed. The file size might exceeds the limit.";
$lang->file->errorFileFormate = " Uploading failed. The file format không là in the prescribed scope.";
$lang->file->errorFileMove    = " Uploading failed. An error prompts when moving file.";
$lang->file->dangerFile       = " The file failed to be uploaded for security reasons.";
$lang->file->errorSuffix      = 'Format is incorrect. .zip files ONLY!';
$lang->file->errorExtract     = 'Extracting files failed. Files might be damaged or there might be invalid files in the zip package.';
$lang->file->fileNotFound     = 'Tập tin was not found. The physical file might have been deleted!';
$lang->file->fileContentEmpty = 'The file is empty. Check the file and upload it again.';

$lang->file->uploadError[1] = 'The uploaded filesize exceeds the limit. Please change the upload_max_filesize and post_max_size options in php.ini';
$lang->file->uploadError[2] = 'The size of the uploaded file exceeds the value specified by the MAX_FILE_SIZE option in the HTML form';
$lang->file->uploadError[3] = 'Only part of the file has been uploaded, please re-upload';
$lang->file->uploadError[4] = 'No files have been uploaded';
$lang->file->uploadError[5] = 'The size of the file is 0. Please upload the file again';
