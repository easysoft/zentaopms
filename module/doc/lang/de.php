<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: en.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->doc->common         = 'Dok';
$lang->doc->id             = 'ID';
$lang->doc->product        = $lang->productCommon;
$lang->doc->execution      = $lang->executionCommon;
$lang->doc->lib            = 'Bibliothek';
$lang->doc->module         = 'Modul';
$lang->doc->title          = 'Titel';
$lang->doc->digest         = 'Zusammenfassung';
$lang->doc->comment        = 'Bemerkung';
$lang->doc->type           = 'Typ';
$lang->doc->content        = 'Text';
$lang->doc->keywords       = 'Tags';
$lang->doc->url            = 'URL';
$lang->doc->files          = 'Datei';
$lang->doc->addedBy        = 'Angelegt von';
$lang->doc->addedDate      = 'Angelegt am';
$lang->doc->editedBy       = 'Bearbeitet von';
$lang->doc->editedDate     = 'Bearbeitet am';
$lang->doc->version        = 'Version';
$lang->doc->basicInfo      = 'Basis Info';
$lang->doc->deleted        = 'Gelöscht';
$lang->doc->fileObject     = 'Das Objekt';
$lang->doc->whiteList      = 'White List';
$lang->doc->contentType    = 'Format';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = 'Dateiname';
$lang->doc->filePath       = 'Dateipfad';
$lang->doc->extension      = 'Erweiterung';
$lang->doc->size           = 'Größe';
$lang->doc->download       = 'Download';
$lang->doc->acl            = 'Rechte';
$lang->doc->fileName       = 'Files';
$lang->doc->groups         = 'Gruppen';
$lang->doc->users          = 'Benutzer';
$lang->doc->item           = ' Einträge';
$lang->doc->num            = 'Doks';
$lang->doc->searchResult   = 'Suchergebnis';
$lang->doc->mailto         = 'Mailto';
$lang->doc->noModule       = 'No document in this lib, please create it';
$lang->doc->noChapter      = 'No chapters or articles in this book. Please add chapters and articles.';

$lang->doc->moduleDoc      = 'Nach Modulen';
$lang->doc->searchDoc      = 'Suche';
$lang->doc->fast           = 'Schnelleintrag';
$lang->doc->allDoc         = 'Alle Doks';
$lang->doc->openedByMe     = 'Meine';
$lang->doc->editedByMe     = 'Edited By Me';
$lang->doc->orderByOpen    = 'Zuletzt hinzugefügt';
$lang->doc->orderByEdit    = 'Zuletzt bearbeitet';
$lang->doc->orderByVisit   = 'Zuletzt angesehen';
$lang->doc->todayEdited    = 'Heute aktualisiert';
$lang->doc->pastEdited     = 'Aktualisiert';
$lang->doc->myDoc          = 'Meine Dokumente';
$lang->doc->myCollection   = 'Meine Favoriten';

/* 方法列表。*/
$lang->doc->index            = 'Home';
$lang->doc->create           = 'Dokument hinzufügen';
$lang->doc->edit             = 'Bearbeiten';
$lang->doc->delete           = 'Löschen';
$lang->doc->createBook       = 'Create Book';
$lang->doc->browse           = 'Liste';
$lang->doc->view             = 'Details';
$lang->doc->diff             = 'Diff';
$lang->doc->diffAction       = 'Diff Document';
$lang->doc->sort             = 'Sortierung';
$lang->doc->manageType       = 'Kategorie verwalten';
$lang->doc->editType         = 'Bearbeiten';
$lang->doc->deleteType       = 'Löschen';
$lang->doc->addType          = 'Hinzufügen';
$lang->doc->childType        = 'Child';
$lang->doc->catalogName      = 'Catalog Name';
$lang->doc->collect          = 'Favorit hinzufügen';
$lang->doc->cancelCollection = 'Favorit entfernen';
$lang->doc->deleteFile       = 'Delete File';

$lang->doc->libName        = 'Name';
$lang->doc->libType        = 'Kategorie';
$lang->doc->custom         = 'Eigene Dok Bibliothek';
$lang->doc->customAB       = 'Eigene Bibliothek';
$lang->doc->createlib      = 'Bibliothek erstellen';
$lang->doc->allLibs        = 'Bibliothek';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->executionCommon} Bibliothek Liste";
$lang->doc->showFiles      = 'Dok Bibliothek';
$lang->doc->editlib        = 'Bibliothek bearbeiten';
$lang->doc->deleteLib      = 'Bibliothek löschen';
$lang->doc->fixedMenu      = 'Im Menü fixieren';
$lang->doc->removeMenu     = 'Vom Menü entfernen';
$lang->doc->search         = 'Suche';

/* 查询条件列表 */
$lang->doc->allProduct    = 'Alle' . $lang->productCommon;
$lang->doc->allExecutions = 'Alle' . $lang->executionCommon;

$lang->doc->libTypeList['product']   = $lang->productCommon . ' Bibliothek';
$lang->doc->libTypeList['execution'] = $lang->executionCommon . ' Bibliothek';
$lang->doc->libTypeList['custom']    = 'Eigene Bibliothek';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon . 'Dok Bibliothek';
$lang->doc->systemLibs['execution'] = $lang->executionCommon . 'Dok Bibliothek';

$lang->doc->aclList['open']    = 'Öffentlich';
$lang->doc->aclList['custom']  = 'Eigene';
$lang->doc->aclList['private'] = 'Privat';

$lang->doc->typeList['html']     = 'Html';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = 'URL';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->types['text'] = 'Text';
$lang->doc->types['url']  = 'URL';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = 'Kategorie';
$lang->doc->browseTypeList['list'] = 'Liste';
$lang->doc->browseTypeList['grid'] = 'Karte';

$lang->doc->fastMenuList['byediteddate']  = 'Zuletzt bearbeitet';
//$lang->doc->fastMenuList['visiteddate']   = 'Recently Visited';
$lang->doc->fastMenuList['openedbyme']    = 'Meine Dokumente';
$lang->doc->fastMenuList['collectedbyme'] = 'Meine Favoriten';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = 'Dateibiblothek anzeigen';
$lang->doc->customObjectLibs['customFiles'] = 'Eigene Bibliothek anzeigen';

$lang->doc->orderLib = 'Rank Settings';
$lang->doc->customShowLibs = 'Display Settings';
$lang->doc->customShowLibsList['zero']     = 'Display Empty Library';
$lang->doc->customShowLibsList['children'] = 'Display Child-category Documents';
$lang->doc->customShowLibsList['unclosed'] = "Display Active {$lang->executionCommon}s Only";

$lang->doc->mail = new stdclass();
$lang->doc->mail->create = new stdclass();
$lang->doc->mail->edit   = new stdclass();
$lang->doc->mail->create->title = "%s created document #%s:%s";
$lang->doc->mail->edit->title   = "%s edited document #%s:%s";

$lang->doc->confirmDelete        = "Möchten Sie dieses Dokument löschen?";
$lang->doc->confirmDeleteLib     = "Möchten Sie diese Bibliothek löschen?";
$lang->doc->confirmDeleteChapter = "Do you want to delete this chapter?";
$lang->doc->errorEditSystemDoc   = "System Dokumentenbibliothek darf nicht geändert werden.";
$lang->doc->errorEmptyProduct    = "Kein {$lang->productCommon}. Kann nicht erstellt werden.";
$lang->doc->errorEmptyProject    = "Kein {$lang->executionCommon}. Kann nicht erstellt werden.";
$lang->doc->errorMainSysLib      = "Diese Bibliothek kann nicht gelöscht werden.";
$lang->doc->accessDenied         = "Zugriff verweigert!";
$lang->doc->versionNotFount      = 'Existiert nicht in diesem Build.';
$lang->doc->noDoc                = 'Keine Dokumente. ';
$lang->doc->noArticle            = 'No articles.';
$lang->doc->noLib                = 'No libraries. ';
$lang->doc->noBook               = 'The WIKI library has not created a manual, please create a new one :)';
$lang->doc->cannotCreateOffice   = 'Sorry, %s can only be created in ZenTao Enterprise. Contact us at renee@easysoft.ltd to try ZenTao Enterprise.';
$lang->doc->notSetOffice         = "<p>To create a %s document, you need to configure <a href='%s' target='_parent'>office convert</a>.<p>";
$lang->doc->noSearchedDoc        = 'Nichts gesucht.';
$lang->doc->noEditedDoc          = 'Sie haben kein Dokument bearbeitet.';
$lang->doc->noOpenedDoc          = 'Sie haben kein Dokument erstellt.';
$lang->doc->noCollectedDoc       = 'Sie haben kein Dokument gesammelt.';
$lang->doc->errorEmptyLib        = 'No data in document library.';

$lang->doc->noticeAcl['lib']['product']['default']   = 'Users who can access the selected product can access it.';
$lang->doc->noticeAcl['lib']['product']['custom']    = 'Users who can access the selected product or users in the whiltelist can access it.';
$lang->doc->noticeAcl['lib']['project']['default']   = 'Users who can access the selected project can access it.';
$lang->doc->noticeAcl['lib']['project']['custom']    = 'Users who can access the selected project or users in the whiltelist can access it.';
$lang->doc->noticeAcl['lib']['execution']['default'] = "Users who can access the selected {$lang->executionCommon} can access it.";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "Users who can access the selected {$lang->executionCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['custom']['open']       = 'All users can access it.';
$lang->doc->noticeAcl['lib']['custom']['custom']     = 'Users in the whitelist can access it.';
$lang->doc->noticeAcl['lib']['custom']['private']    = 'Only the one who created it can access it.';

$lang->doc->noticeAcl['doc']['open']    = 'Users who can access the document library which the document belongs can access it.';
$lang->doc->noticeAcl['doc']['custom']  = 'Users in the whiltelist can access it.';
$lang->doc->noticeAcl['doc']['private'] = 'Only the one who created it can access it.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = 'Url';

$lang->doclib = new stdclass();
$lang->doclib->name      = 'Name';
$lang->doclib->control   = 'Zugriffskontrolle';
$lang->doclib->group     = 'Gruppe';
$lang->doclib->user      = 'Benutzer';
$lang->doclib->files     = 'Dateien';
$lang->doclib->all       = 'Alle Bibliotheken';
$lang->doclib->select    = 'Auswahl';
$lang->doclib->execution = $lang->executionCommon . ' Bibliothek';
$lang->doclib->product   = $lang->productCommon . ' Bibliothek';

$lang->doclib->aclListA['default'] = 'Default';
$lang->doclib->aclListA['custom']  = 'Custom';

$lang->doclib->aclListB['open']    = 'Public';
$lang->doclib->aclListB['custom']  = 'Custom';
$lang->doclib->aclListB['private'] = 'Private';

$lang->doclib->create['product']   = 'Create ' . $lang->productCommon . ' Library';
$lang->doclib->create['execution'] = 'Create ' . $lang->executionCommon . ' Library';
$lang->doclib->create['custom']    = 'Create Custom Library';

$lang->doclib->main['product']   = 'Primary Library';
$lang->doclib->main['execution'] = 'Primary Library';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = 'Custom';

$lang->doclib->nameList['custom'] = 'Custom Name';
