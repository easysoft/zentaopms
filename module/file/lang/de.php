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
$lang->file->common        = 'Datei';
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
$lang->file->addFile       = 'Hinzufügen';
$lang->file->beginUpload   = 'Upload';
$lang->file->uploadSuccess = 'Hochgeladen!';

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
$lang->file->saveAndNext         = 'Save and Next';
$lang->file->importPager         = 'Total: <strong>%s</strong>. Page <strong>%s</strong> of <strong>%s</strong>';
$lang->file->importSummary       = "Import <strong id='allCount'>%s</strong> items  You can <strong>%s</strong> items/page, so you have to import <strong id='times'>%s</strong> times.";

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
$lang->file->fileNotFound     = 'The file was not found. The physical file might have been deleted!';
$lang->file->fileContentEmpty = 'The file is empty. Check the file and upload it again.';
