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
$lang->file->common        = 'Datei';
$lang->file->id            = 'ID';
$lang->file->objectType    = 'Object Type';
$lang->file->objectID      = 'Object ID';
$lang->file->deleted       = 'Deleted';
$lang->file->uploadImages  = 'Stapel Upload';
$lang->file->download      = 'Dateien downloaden';
$lang->file->uploadDate    = 'Hochgeladen am';
$lang->file->edit          = 'Umbenennen';
$lang->file->inputFileName = 'Bitte Dateinamen angeben';
$lang->file->delete        = 'Datei löschen';
$lang->file->label         = 'Bezeichnung:';
$lang->file->maxUploadSize = "<span class='text-red'>%s</span>";
$lang->file->applyTemplate = "Vorlage nutzen";
$lang->file->tplTitle      = "Vorlagenname";
$lang->file->tplTitleAB    = "Template";
$lang->file->setPublic     = "Als öffentlich setzen";
$lang->file->exportFields  = "Zu exportierende Felder";
$lang->file->exportRange   = "Datenbereich";
$lang->file->defaultTPL    = "Standardvorlage";
$lang->file->setExportTPL  = "Einstellungen";
$lang->file->preview       = "Vorschau";
$lang->file->previewFile   = "Vorschau des Anhangs";
$lang->file->addFile       = 'Hinzufügen';
$lang->file->beginUpload   = 'Upload';
$lang->file->uploadSuccess = 'Hochgeladen!';
$lang->file->batchExport   = 'Export in batches';
$lang->file->downloadFile  = 'Download';
$lang->file->playFailed    = 'Video preview failed, please contact admin';
$lang->file->exportData    = "Daten exportieren";

$lang->file->pathname  = 'Pfadname';
$lang->file->title     = 'Titel';
$lang->file->fileName  = 'Dateiname';
$lang->file->untitled  = 'Ohne Titel';
$lang->file->extension = 'Endung';
$lang->file->size      = 'Größe';
$lang->file->encoding  = 'Encoding';
$lang->file->addedBy   = 'Angelegt von';
$lang->file->addedDate = 'Angelegt am';
$lang->file->downloads = 'Downloads';
$lang->file->extra     = 'Extra';

$lang->file->dragFile            = 'Bitte hier ablegen.';
$lang->file->childTaskTips       = 'It\'s a child task if there is a \'>\' before the name.';
$lang->file->uploadImagesExplain = 'Hinweis: Beim Upload von .jpg, .jpeg, .gif, und .png Dateien. Der Dateiname wird als Titel der Story genutzt und das Bild als der Inhalt.';
$lang->file->uploadingImages     = 'There are <strong>%s</strong> files being uploaded.';
$lang->file->saveAndNext         = 'Save and Next';
$lang->file->importPager         = 'Total: <strong>%s</strong>. Page <strong>%s</strong> of <strong>%s</strong>';
$lang->file->importSummary       = "Import <strong id='totalAmount'>%s</strong> items  You can <strong>%s</strong> items/page, so you have to import <strong id='times'>%s</strong> times.";
$lang->file->accessDenied        = 'Access denied to this file!';

$lang->file->errorNotExists   = "<span class='text-red'>'%s' wurde nicht gefunden.</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>'%s' ist nicht beschreibbar. Bitte passen Sie die Berechtigungen an. Befehl <span class='code'>sudo chmod -R 777 '%s'</span></span> in Linux.";
$lang->file->confirmDelete    = " Möchten Sie das wirklich löschen?";
$lang->file->errorFileSize    = " Die Dateigröße übersteigt das Limit. Upload nicht möglich!";
$lang->file->errorFileUpload  = " Upload fehlgeschlagen. Die Dateigröße übersteigt das Limit.";
$lang->file->errorFileFormate = " Upload fehlgeschlagen, Das Dateiformat ist nicht erlaubt.";
$lang->file->errorFileMove    = " Upload fehlgeschlagen, die Datei konnte nicht verschoben werden.";
$lang->file->dangerFile       = " Die Datei wurde aus Sicherheitsgründen abgelehnt.";
$lang->file->errorSuffix      = 'Format ist falsch. Nur .zip Dateien!';
$lang->file->errorExtract     = 'Entpacken fehlgeschlagen. Die Datei ist vermutlich defekt.';
$lang->file->errorUploadEmpty = 'No upload file.';
$lang->file->fileNotFound     = 'The file was not found. The physical file might have been deleted!';
$lang->file->fileContentEmpty = 'The file is empty. Check the file and upload it again.';
$lang->file->bizGuide         = 'To utilize Excel import/export, upgrade to ZenTao %s edition';

$lang->file->uploadError[1] = 'The uploaded filesize exceeds the limit. Please change the upload_max_filesize and post_max_size options in php.ini';
$lang->file->uploadError[2] = 'The size of the uploaded file exceeds the value specified by the MAX_FILE_SIZE option in the HTML form';
$lang->file->uploadError[3] = 'Only part of the file has been uploaded, please re-upload';
$lang->file->uploadError[4] = 'No files have been uploaded';
$lang->file->uploadError[5] = 'The size of the file is 0. Please upload the file again';
