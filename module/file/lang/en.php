<?php
/**
 * The file module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = 'Attachments';
$lang->file->id            = 'ID';
$lang->file->objectType    = 'Item Type';
$lang->file->objectID      = 'Item ID';
$lang->file->deleted       = 'Deleted';
$lang->file->uploadImages  = 'Upload Images';
$lang->file->download      = 'Download Attachments';
$lang->file->uploadDate    = 'Uploaded on';
$lang->file->edit          = 'Rename';
$lang->file->inputFileName = 'Attachment Name';
$lang->file->delete        = 'Delete Attachment';
$lang->file->label         = 'Label';
$lang->file->maxUploadSize = "(%s max)";
$lang->file->applyTemplate = "Apply Template";
$lang->file->tplTitle      = "Template Name";
$lang->file->tplTitleAB    = "Templates";
$lang->file->setPublic     = "Set Public Template";
$lang->file->exportFields  = "Export";
$lang->file->exportRange   = "Export Range";
$lang->file->defaultTPL    = "Default Template";
$lang->file->setExportTPL  = "Settings";
$lang->file->preview       = "Preview";
$lang->file->previewFile   = "Preview Attachments";
$lang->file->addFile       = 'Add';
$lang->file->beginUpload   = 'upload';
$lang->file->uploadSuccess = 'Uploaded';
$lang->file->batchExport   = 'Export in batches';
$lang->file->downloadFile  = 'Download';
$lang->file->playFailed    = 'Video preview failed. Please contact your admin.';
$lang->file->exportData    = "Export Data";

$lang->file->pathname  = 'Path';
$lang->file->title     = 'Title';
$lang->file->fileName  = 'File Name';
$lang->file->untitled  = 'Untitled';
$lang->file->extension = 'File type';
$lang->file->size      = 'Size';
$lang->file->encoding  = 'Encoding';
$lang->file->addedBy   = 'Added by';
$lang->file->addedDate = 'Added on';
$lang->file->downloads = 'Downloads';
$lang->file->extra     = 'Notes';

$lang->file->attachmentName = "Attachment Name";
$lang->file->sourceObject   = "Source Object";
$lang->file->sourceID       = "Source ID";

$lang->file->dragFile            = 'Drag files here';
$lang->file->childTaskTips       = 'It\'s a sub-task if there is a \'>\' before the name.';
$lang->file->uploadImagesExplain = 'Note: Please upload images in .jpg, .jpeg, .gif, or .png format. The image name will be the name of the story and the image will be the description.';
$lang->file->uploadingImages     = '<strong>%s</strong> files being uploaded.';
$lang->file->saveAndNext         = 'Save and Next';
$lang->file->importPager         = 'Total: <strong>%s</strong>. Page <strong>%s</strong> of <strong>%s</strong>';
$lang->file->importSummary       = "Total: <strong id='totalAmount'>%s</strong> records. %s per page, <strong id='times'>%s</strong> batches in total.";
$lang->file->accessDenied        = 'Access denied to this file!';
$lang->file->uploadImagesTip     = 'Click or drag to upload. Supported formats: jpg, jpeg, gif, and png.';
$lang->file->waitDownloadTip     = 'Exporting files. Please wait...';

$lang->file->errorNotExists   = "<span class='text-red'>'%s' is not found.</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>'%s' is not writable. Please change its permissions. Enter <span class='code'>sudo chmod -R 777 '%s'</span></span> in Linux.";
$lang->file->confirmDelete    = "Delete this attachment?";
$lang->file->errorFileSize    = "File exceeds %s and may not upload successfully.";
$lang->file->errorFileUpload  = "Upload failed: File size may exceed the limit.";
$lang->file->errorFileFormat  = "Upload failed: Unsupported file format.";
$lang->file->errorFileMove    = "Upload failed: Error moving file.";
$lang->file->errorFileCount   = "Limit: %s files. Excess files will be ignored.";
$lang->file->dangerFile       = "Upload blocked: Security risk detected in selected file.";
$lang->file->errorSuffix      = 'Invalid format, .zip files ONLY!';
$lang->file->errorExtract     = 'Extraction failed. The file may be corrupted or contains restricted content.';
$lang->file->errorUploadEmpty = 'No files pending upload';
$lang->file->fileNotFound     = 'File not found. It may have been deleted from the storage.';
$lang->file->fileContentEmpty = 'Empty file detected. Please check and try again.';
$lang->file->bizGuide         = 'Upgrade to ZenTao %s edition to use Excel import/export.';

$lang->file->uploadError[1] = 'File size exceeds limit. Please increase upload_max_filesize and post_max_size in php.ini.';
$lang->file->uploadError[2] = 'The uploaded file exceeds the MAX_FILE_SIZE value specified in the HTML form.';
$lang->file->uploadError[3] = 'The file was only partially uploaded. Please try again.';
$lang->file->uploadError[4] = 'No file was uploaded';
$lang->file->uploadError[5] = 'Empty file detected. Please try again.';
