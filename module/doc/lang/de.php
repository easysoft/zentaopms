<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: en.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->doclib = new stdclass();
$lang->doclib->name         = 'Name';
$lang->doclib->control      = 'Zugang';
$lang->doclib->group        = 'Gruppe';
$lang->doclib->user         = 'Benutzer';
$lang->doclib->files        = 'Dateien';
$lang->doclib->all          = 'Alle Bibliotheken';
$lang->doclib->select       = 'Auswahl';
$lang->doclib->execution    = $lang->executionCommon . ' Bibliothek';
$lang->doclib->product      = $lang->productCommon . ' Bibliothek';
$lang->doclib->apiLibName   = 'Library Name';
$lang->doclib->defaultSpace = 'Default Space';
$lang->doclib->defaultMyLib = 'My Library';
$lang->doclib->spaceName    = 'Space Name';
$lang->doclib->createSpace  = 'Create Space';
$lang->doclib->editSpace    = 'Edit Space';
$lang->doclib->privateACL   = "Private (Only creators and whitelisted users with %s permissions can access it)";
$lang->doclib->defaultOrder = 'Doc order';
$lang->doclib->migratedWiki = 'Migrierte Wiki';

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "When execution is empty, the library created is the {$lang->projectCommon} library";

$lang->doclib->type['wiki'] = 'Doc';
$lang->doclib->type['api']  = 'API';

$lang->doclib->aclListA = array();
$lang->doclib->aclListA['default'] = 'Default';
$lang->doclib->aclListA['custom']  = 'Custom';

$lang->doclib->aclListB['open']    = 'Public';
$lang->doclib->aclListB['custom']  = 'Custom';
$lang->doclib->aclListB['private'] = 'Private';

$lang->doclib->mySpaceAclList['private'] = "Private (Only creators can access it)";

$lang->doclib->aclList = array();
$lang->doclib->aclList['open']    = "Public (Users who can access doccan access it)";
$lang->doclib->aclList['default'] = "Default (Users who can access the selected %s or users in the whiltelist can access it)";
$lang->doclib->aclList['private'] = "Private (Only the one who created it or users in the whiltelist can access it)";

$lang->doclib->idOrder = array();
$lang->doclib->idOrder['id_asc']  = 'ID ascending order';
$lang->doclib->idOrder['id_desc'] = 'ID descending order' ;

$lang->doclib->create['product']   = 'Create ' . $lang->productCommon . ' Library';
$lang->doclib->create['execution'] = 'Create ' . $lang->executionCommon . ' Library';
$lang->doclib->create['custom']    = 'Create Custom Library';

$lang->doclib->main['product']   = 'Primary Library';
$lang->doclib->main['project']   = 'Primary Library';
$lang->doclib->main['execution'] = 'Primary Library';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = 'Custom';

$lang->doclib->nameList['custom'] = 'Custom Name';

$lang->doclib->apiNameUnique = array();
$lang->doclib->apiNameUnique['product'] = 'In the api library of the same ' . $lang->productCommon . ', ';
$lang->doclib->apiNameUnique['project'] = 'In the api library of the same ' . $lang->projectCommon . ', ';
$lang->doclib->apiNameUnique['nolink']  = 'In the no linked api library, ';

/* Fields. */
$lang->doc->common       = 'Dok';
$lang->doc->id           = 'ID';
$lang->doc->product      = $lang->productCommon;
$lang->doc->project      = $lang->projectCommon;
$lang->doc->execution    = $lang->execution->common;
$lang->doc->plan         = $lang->productplan->shortCommon;
$lang->doc->lib          = 'Bibliothek';
$lang->doc->module       = 'Modul';
$lang->doc->libAndModule = 'Bibliothek&Modul';
$lang->doc->object       = 'Object';
$lang->doc->title        = 'Titel';
$lang->doc->digest       = 'Zusammenfassung';
$lang->doc->comment      = 'Bemerkung';
$lang->doc->type         = 'Typ';
$lang->doc->content      = 'Text';
$lang->doc->keywords     = 'Keywords';
$lang->doc->status       = 'Status';
$lang->doc->url          = 'URL';
$lang->doc->files        = 'Datei';
$lang->doc->addedBy      = 'Angelegt von';
$lang->doc->addedByAB    = 'CreatedBy';
$lang->doc->addedDate    = 'CreatedDate';
$lang->doc->editedBy     = 'Bearbeitet von';
$lang->doc->editedDate   = 'UpdatedDate';
$lang->doc->editingDate  = 'Editing user and time';
$lang->doc->lastEditedBy = 'Last Editor';
$lang->doc->updateInfo   = 'Informationen aktualisieren';
$lang->doc->version      = 'Version';
$lang->doc->basicInfo    = 'Basis Info';
$lang->doc->deleted      = 'Gelöscht';
$lang->doc->fileObject   = 'Das Objekt';
$lang->doc->whiteList    = 'White List';
$lang->doc->readonly     = 'Readonly';
$lang->doc->editable     = 'Editable';
$lang->doc->contentType  = 'Format';
$lang->doc->separator    = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle    = 'Dateiname';
$lang->doc->filePath     = 'Dateipfad';
$lang->doc->extension    = 'Erweiterung';
$lang->doc->size         = 'Größe der Anlage';
$lang->doc->source       = 'Source';
$lang->doc->download     = 'Download';
$lang->doc->acl          = 'Rechte';
$lang->doc->fileName     = 'Files';
$lang->doc->groups       = 'Gruppen';
$lang->doc->users        = 'Benutzer';
$lang->doc->item         = ' Einträge';
$lang->doc->num          = 'Doks';
$lang->doc->searchResult = 'Suchergebnis';
$lang->doc->mailto       = 'Mailto';
$lang->doc->noModule     = 'No document in this lib, please create it';
$lang->doc->noChapter    = 'No chapters or articles in this book. Please add chapters and articles.';
$lang->doc->views        = 'Views';
$lang->doc->draft        = 'Draft';
$lang->doc->collector    = 'Collector';
$lang->doc->main         = 'Main Document Library';
$lang->doc->order        = 'Order';
$lang->doc->doc          = 'Document';
$lang->doc->updateOrder  = 'Update Order';
$lang->doc->update       = 'Update';
$lang->doc->nextStep     = 'Next';
$lang->doc->closed       = 'Closed';
$lang->doc->saveDraft    = 'Save Draft';
$lang->doc->position     = 'Position';
$lang->doc->person       = 'Person';
$lang->doc->team         = 'Team';
$lang->doc->manage       = 'Document Management';
$lang->doc->release      = 'Release';
$lang->doc->story        = 'Story';
$lang->doc->convertdoc   = 'Convert doc';
$lang->doc->needEditable = 'This document is not editable.';
$lang->doc->needReadable = 'This document is not readable.';
$lang->doc->groupLabel   = 'Gruppen';
$lang->doc->userLabel    = 'Benutzer';

$lang->doc->moduleDoc     = 'Nach Modulen';
$lang->doc->searchDoc     = 'Suche';
$lang->doc->fast          = 'Schnelleintrag';
$lang->doc->allDoc        = 'Alle Doks';
$lang->doc->allVersion    = 'All Versions';
$lang->doc->openedByMe    = 'Meine';
$lang->doc->editedByMe    = 'Edited By Me';
$lang->doc->orderByOpen   = 'Zuletzt hinzugefügt';
$lang->doc->orderByEdit   = 'Zuletzt bearbeitet';
$lang->doc->orderByVisit  = 'Zuletzt angesehen';
$lang->doc->todayEdited   = 'Heute aktualisiert';
$lang->doc->pastEdited    = 'Aktualisiert';
$lang->doc->myDoc         = 'Meine Dokumente';
$lang->doc->myView        = 'Recently Viewed';
$lang->doc->myCollection  = 'Meine Favoriten';
$lang->doc->myCreation    = 'Created By';
$lang->doc->myEdited      = 'Edited By';
$lang->doc->myLib         = 'My Library';
$lang->doc->tableContents = 'Catalog';
$lang->doc->addCatalog    = 'Add Catalog';
$lang->doc->editCatalog   = 'Edit Catalog';
$lang->doc->deleteCatalog = 'Delete Catalog';
$lang->doc->sortCatalog   = 'Catalog Sorting';
$lang->doc->sortDoclib    = 'Library Sorting';
$lang->doc->sortDoc       = 'Document Sorting';
$lang->doc->docStatistic  = 'Statistic';
$lang->doc->docCreated    = 'Created Documents';
$lang->doc->docEdited     = 'Edited Documents';
$lang->doc->docViews      = 'Page Views';
$lang->doc->docCollects   = 'Collection';
$lang->doc->todayUpdated  = "Today's update";
$lang->doc->daysUpdated   = 'Updated %s days ago';
$lang->doc->monthsUpdated = 'Updated %s months ago';
$lang->doc->yearsUpdated  = 'Updated %s years ago';
$lang->doc->viewCount     = '%s Visits';
$lang->doc->collectCount  = '%s Collections';

/* Methods list */
$lang->doc->index            = 'Dashboard';
$lang->doc->createAB         = 'Create';
$lang->doc->create           = 'Dokument hinzufügen';
$lang->doc->createOrUpload   = 'Create/Upload Document';
$lang->doc->edit             = 'Bearbeiten';
$lang->doc->effort           = 'Effort';
$lang->doc->delete           = 'Löschen';
$lang->doc->createBook       = 'Create Book';
$lang->doc->browse           = 'Liste';
$lang->doc->view             = 'Details';
$lang->doc->diff             = 'Diff';
$lang->doc->cancelDiff       = 'Cancel diff';
$lang->doc->diffAction       = 'Diff Document';
$lang->doc->sort             = 'Sortierung';
$lang->doc->manageType       = 'Kategorie verwalten';
$lang->doc->editType         = 'Bearbeiten';
$lang->doc->editChildType    = 'Manage';
$lang->doc->deleteType       = 'Löschen';
$lang->doc->addType          = 'Hinzufügen';
$lang->doc->childType        = 'Child';
$lang->doc->catalogName      = 'Catalog Name';
$lang->doc->collect          = 'Favorit hinzufügen';
$lang->doc->collectSuccess   = 'Add Favorite';
$lang->doc->cancelCollection = 'Favorit entfernen';
$lang->doc->deleteFile       = 'Delete File';
$lang->doc->menuTitle        = 'Menu';
$lang->doc->api              = 'API';
$lang->doc->displaySetting   = 'Display Settings';
$lang->doc->collectAction    = 'Add Favorite';

$lang->doc->libName            = 'Name';
$lang->doc->libType            = 'Kategorie';
$lang->doc->custom             = 'Eigene Dok Bibliothek';
$lang->doc->customAB           = 'Eigene Bibliothek';
$lang->doc->createLib          = 'Create Library';
$lang->doc->createLibAction    = 'Create Library';
$lang->doc->createSpace        = 'Create Space';
$lang->doc->allLibs            = 'Bibliothek';
$lang->doc->objectLibs         = "{$lang->productCommon}/{$lang->executionCommon} Bibliothek Liste";
$lang->doc->showFiles          = 'Dok Bibliothek';
$lang->doc->editLib            = 'Edit Document Library';
$lang->doc->editSpaceAction    = 'Edit Space';
$lang->doc->editLibAction      = 'Edit Library';
$lang->doc->deleteSpaceAction  = 'Delete Space';
$lang->doc->deleteLibAction    = 'Delete Library';
$lang->doc->moveLibAction      = 'Move Library';
$lang->doc->moveDocAction      = 'Move Document';
$lang->doc->batchMove          = 'Batch Move';
$lang->doc->batchMoveDocAction = 'Batch Move Document';
$lang->doc->fixedMenu          = 'Im Menü fixieren';
$lang->doc->removeMenu         = 'Vom Menü entfernen';
$lang->doc->search             = 'Suche';
$lang->doc->allCollections     = 'All Collections';
$lang->doc->keywordsTips       = 'Please use commas to separate multiple keywords.';
$lang->doc->sortLibs           = 'Sort Libs';
$lang->doc->titlePlaceholder   = 'Please enter the title';
$lang->doc->confirm            = 'Confirm';
$lang->doc->docSummary         = 'Total: <strong>%s</strong>.';
$lang->doc->docCheckedSummary  = 'Seleted: <strong>%total%</strong>.';
$lang->doc->showDoc            = 'Whether to display documents';
$lang->doc->uploadFile         = 'Upload File';
$lang->doc->uploadDoc          = 'Upload Document';
$lang->doc->uploadFormat       = 'Upload Format';
$lang->doc->editedList         = 'File editor';
$lang->doc->moveTo             = 'Move to';
$lang->doc->notSupportExport   = 'This document does not support export';
$lang->doc->downloadTemplate   = 'Download Template';
$lang->doc->addFile            = 'Add File';

$lang->doc->preview         = 'Preview';
$lang->doc->insertTitle     = 'Insert %s list';
$lang->doc->previewTip      = 'You can modify the display data of the inserted content through the filtering configuration. The inserted data is a static data snapshot. ';
$lang->doc->insertTip       = 'Please preview at least one record.';
$lang->doc->insertText      = 'Insert';
$lang->doc->searchCondition = 'Search Condition';
$lang->doc->list            = 'List';
$lang->doc->detail          = 'Detail';
$lang->doc->zentaoData      = 'Zentao Data';
$lang->doc->emptyError      = 'Not empty';
$lang->doc->caselib         = 'Case Library';
$lang->doc->customSearch    = 'Custom Search';

$lang->doc->addChapter     = 'Add chapter';
$lang->doc->editChapter    = 'Edit chapter';
$lang->doc->sortChapter    = 'Chapter Sorting';
$lang->doc->deleteChapter  = 'Delete chapter';
$lang->doc->addSubChapter  = 'Add sub chapter';
$lang->doc->addSameChapter = 'Add same chapter';
$lang->doc->addSubDoc      = 'Add sub document';
$lang->doc->chapterName    = 'Chapter name';

$lang->doc->tips = new stdclass();
$lang->doc->tips->noProduct   = 'No product, please create first';
$lang->doc->tips->noProject   = 'No project, please create first';
$lang->doc->tips->noExecution = 'No execution, please create first';
$lang->doc->tips->noCaselib   = 'No caselib, please create first';

$lang->doc->zentaoList = array();
$lang->doc->zentaoList['story']          = $lang->SRCommon;
$lang->doc->zentaoList['productStory']   = $lang->productCommon . $lang->SRCommon;
$lang->doc->zentaoList['projectStory']   = $lang->projectCommon . $lang->SRCommon;
$lang->doc->zentaoList['executionStory'] = $lang->execution->common . $lang->SRCommon;
$lang->doc->zentaoList['planStory']      = $lang->productplan->shortCommon . $lang->SRCommon;

$lang->doc->zentaoList['case']        = $lang->testcase->common;
$lang->doc->zentaoList['productCase'] = $lang->productCommon . $lang->testcase->common;
$lang->doc->zentaoList['caselib']     = 'Case Library' . $lang->testcase->common;

$lang->doc->zentaoList['task']       = $lang->task->common;
$lang->doc->zentaoList['bug']        = $lang->bug->common;
$lang->doc->zentaoList['productBug'] = 'Product Bug';
$lang->doc->zentaoList['planBug']    = 'Plan Bug';

$lang->doc->zentaoList['more']               = 'More';
$lang->doc->zentaoList['productPlan']        = $lang->productCommon . ' Plan';
$lang->doc->zentaoList['productPlanContent'] = $lang->productCommon . ' Plan Content';
$lang->doc->zentaoList['productRelease']     = $lang->productCommon . $lang->release->common;
$lang->doc->zentaoList['ER']                 = $lang->defaultERName;
$lang->doc->zentaoList['UR']                 = $lang->URCommon;
$lang->doc->zentaoList['feedback']           = 'Feedback';
$lang->doc->zentaoList['ticket']             = 'Ticket';

$lang->doc->zentaoAction = array();
$lang->doc->zentaoAction['set']    = 'Setting';
$lang->doc->zentaoAction['delete'] = 'Delete';

$lang->doc->uploadFormatList = array();
$lang->doc->uploadFormatList['separateDocs'] = 'Save files as different document';
$lang->doc->uploadFormatList['combinedDocs'] = 'Save files as one document';

$lang->doc->fileType = new stdclass();
$lang->doc->fileType->stepResult = 'Case Result';

global $config;
/* Query condition list. */
$lang->doc->allProduct    = 'Alle' . $lang->productCommon;
$lang->doc->allExecutions = 'Alle' . $lang->execution->common;
$lang->doc->allProjects   = 'All' . $lang->projectCommon . 's';

$lang->doc->libTypeList['product']   = $lang->productCommon . ' Bibliothek';
$lang->doc->libTypeList['project']   = 'Project Library';
$lang->doc->libTypeList['execution'] = $lang->execution->common . ' Bibliothek';
$lang->doc->libTypeList['api']       = 'API Library';
$lang->doc->libTypeList['custom']    = 'Eigene Bibliothek';

$lang->doc->libGlobalList['api'] = 'Api Libray';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon . 'Dok Bibliothek';
$lang->doc->systemLibs['execution'] = $lang->executionCommon . 'Dok Bibliothek';

$lang->doc->statusList['']       = "";
$lang->doc->statusList['normal'] = "Released";
$lang->doc->statusList['draft']  = "Draft";

$lang->doc->aclList['open']    = "Public (Access with library permissions)";
$lang->doc->aclList['private'] = "Private (Only creators and whitelist users can access)";

$lang->doc->selectSpace = 'Select Space';
$lang->doc->space       = 'Space';
$lang->doc->spaceList['mine']    = 'My Space';
$lang->doc->spaceList['custom']  = 'Team Space';
$lang->doc->spaceList['product'] = $lang->productCommon . ' Space';
$lang->doc->spaceList['project'] = $lang->projectCommon . ' Space';
$lang->doc->spaceList['api']     = 'API Space';

$lang->doc->apiType = 'API Type';
$lang->doc->apiTypeList['product'] = $lang->productCommon . ' API';
$lang->doc->apiTypeList['project'] = $lang->projectCommon . ' API';
$lang->doc->apiTypeList['nolink']  = 'No Link API';

$lang->doc->typeList['html']     = 'Html';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = 'URL';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->createList['template']   = 'Wiki';
$lang->doc->createList['word']       = 'Word';
$lang->doc->createList['ppt']        = 'PPT';
$lang->doc->createList['excel']      = 'Excel';
$lang->doc->createList['attachment'] = $lang->doc->uploadDoc;

$lang->doc->types['doc'] = 'Doc';
$lang->doc->types['api'] = 'API';

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

$lang->doc->orderLib                       = 'Rank Settings';
$lang->doc->customShowLibs                 = 'Display Settings';
$lang->doc->customShowLibsList['zero']     = 'Display Empty Library';
$lang->doc->customShowLibsList['children'] = 'Display Child-category Documents';
$lang->doc->customShowLibsList['unclosed'] = "Display Active {$lang->executionCommon}s Only";

$lang->doc->mail = new stdclass();
$lang->doc->mail->releasedDoc = new stdclass();
$lang->doc->mail->edit        = new stdclass();
$lang->doc->mail->releasedDoc->title = "%s released document #%s:%s";
$lang->doc->mail->edit->title        = "%s edited document #%s:%s";

$lang->doc->confirmDelete               = "Möchten Sie dieses Dokument löschen?";
$lang->doc->confirmDeleteWithSub        = "After deleting the document, the sub contents will be deleted simultaneously. Are you sure you want to delete this document?";
$lang->doc->confirmDeleteLib            = "Möchten Sie diese Bibliothek löschen?";
$lang->doc->confirmDeleteSpace          = "Möchten Sie diesen Bereich löschen?";
$lang->doc->confirmDeleteBook           = "Do you want to delete this book?";
$lang->doc->confirmDeleteChapter        = "Do you want to delete this chapter?";
$lang->doc->confirmDeleteChapterWithSub = "After deleting the chapter, the sub chapters and documents will be deleted simultaneously. Are you sure you want to delete this chapter?";
$lang->doc->confirmDeleteModule         = "Do you want to delete this module?";
$lang->doc->confirmDeleteModuleWithSub  = "After deleting the module, the sub modules and documents will be deleted simultaneously. Are you sure you want to delete this module?";
$lang->doc->confirmOtherEditing         = "This document is currently editing. Continuing to edit will overwrite the content edited by others. Do you want to continue?";
$lang->doc->errorEditSystemDoc          = "System Dokumentenbibliothek darf nicht geändert werden.";
$lang->doc->errorEmptyProduct           = "Kein {$lang->productCommon}. Kann nicht erstellt werden.";
$lang->doc->errorEmptyProject           = "Kein {$lang->executionCommon}. Kann nicht erstellt werden.";
$lang->doc->errorEmptySpaceLib          = "There is no document library in this space. It cannot be created. Please create a document library first";
$lang->doc->errorMainSysLib             = "Diese Bibliothek kann nicht gelöscht werden.";
$lang->doc->accessDenied                = "Zugriff verweigert!";
$lang->doc->versionNotFount             = 'Existiert nicht in diesem Build.';
$lang->doc->noDoc                       = 'Keine Dokumente. ';
$lang->doc->noArticle                   = 'No articles.';
$lang->doc->noLib                       = 'No libraries.';
$lang->doc->noBook                      = 'The Wiki library has not created a manual, please create a new one :)';
$lang->doc->cannotCreateOffice          = 'Sorry, %s file can only be created in ZenTao Biz Series or above. Contact Philip@easysoft.ltd to know more about ZenTao Biz Series and ZenTao Max Series.';
$lang->doc->notSetOffice                = "<p>To create a %s document, you need to configure <a href='%s'>Collabora Online</a>.<p>";
$lang->doc->requestTypeError            = "The current requestType configuration is not PATH_INFO, cannot use the online editing. Please contact the administrator to modify the requestType configuration.";
$lang->doc->notSetCollabora             = "Collabora Online is not set up, unable to create %s document. Please configure <a href='%s'>Collabora Online</a>.";
$lang->doc->noSearchedDoc               = 'Nichts gesucht.';
$lang->doc->noEditedDoc                 = 'Sie haben kein Dokument bearbeitet.';
$lang->doc->noOpenedDoc                 = 'Sie haben kein Dokument erstellt.';
$lang->doc->noCollectedDoc              = 'Sie haben kein Dokument gesammelt.';
$lang->doc->errorEmptyLib               = 'No data in document library.';
$lang->doc->confirmUpdateContent        = 'You have a document that is not saved from last time. Do you want to continue editing it?';
$lang->doc->selectLibType               = 'Please select a type of doc library.';
$lang->doc->selectDoc                   = 'Please select a doc';
$lang->doc->noLibreOffice               = 'You does not have access to office conversion settings!';
$lang->doc->errorParentChapter          = 'The parent chapter cannot be its own chapter or sub chapter!';
$lang->doc->errorOthersCreated          = 'There are documents created by others in this library. You cannot move it.';
$lang->doc->confirmLeaveOnEdit          = 'Wird nach ungespeicherten dokumenten gefragt, ob dies fortgesetzt wurde?';
$lang->doc->errorOccurred               = 'An error occurred. Please try again later.';
$lang->doc->selectLibFirst              = 'Please select a lib first.';
$lang->doc->createLibFirst              = 'Please create a lib first.';
$lang->doc->nopriv                      = 'You do not have access permissions for %s and cannot view this document. If you need to adjust permissions, please contact relevant personnel.';
$lang->doc->docConvertComment           = "This document has been converted to the new editor format. Switch to version %s to view the document before conversion.";
$lang->doc->previewNotAvailable         = 'Preview function is not available. Please visit the Kanban board to view the document %s.';
$lang->doc->hocuspocusConnect           = 'The collaborative editing service is connected.';
$lang->doc->hocuspocusDisconnect        = 'The collaborative editing service is disconnected. Edits will be synchronized after reconnection.';

$lang->doc->noticeAcl['lib']['product']['default']   = "Users who can access the selected {$lang->productCommon} can access it.";
$lang->doc->noticeAcl['lib']['product']['custom']    = "Users who can access the selected {$lang->productCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['project']['default']   = "Users who can access the selected {$lang->projectCommon} can access it.";
$lang->doc->noticeAcl['lib']['project']['open']      = "Users who can access the selected {$lang->projectCommon} can access it.";
$lang->doc->noticeAcl['lib']['project']['private']   = "Users who can access the selected {$lang->projectCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['project']['custom']    = "Users who can access the selected {$lang->projectCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['execution']['default'] = "Users who can access the selected {$lang->execution->common} can access it.";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "Users who can access the selected {$lang->execution->common} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['api']['open']          = 'All users can access it.';
$lang->doc->noticeAcl['lib']['api']['custom']        = 'Users in the whitelist can access it.';
$lang->doc->noticeAcl['lib']['api']['private']       = 'Only the one who created it can access it.';
$lang->doc->noticeAcl['lib']['custom']['open']       = 'All users can access it.';
$lang->doc->noticeAcl['lib']['custom']['custom']     = 'Users in the whitelist can access it.';
$lang->doc->noticeAcl['lib']['custom']['private']    = 'Only the one who created it can access it.';

$lang->doc->noticeAcl['doc']['open']    = 'Users who can access the document library which the document belongs can access it.';
$lang->doc->noticeAcl['doc']['custom']  = 'Users in the whiltelist can access it.';
$lang->doc->noticeAcl['doc']['private'] = 'Only the one who created it can access it.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url       = 'URL';
$lang->doc->placeholder->execution = 'When the execution is empty, the document is created in the project library';

$lang->doc->summary = "Total files on this page: <strong>%s</strong> , total size: <strong>%s</strong>, <strong>%s</strong>.";
$lang->doc->ge      = ':';
$lang->doc->point   = '.';

$lang->doc->libDropdown['editLib']       = 'Edit Library';
$lang->doc->libDropdown['deleteLib']     = 'Delete Library';
$lang->doc->libDropdown['editSpace']     = 'Edit Space';
$lang->doc->libDropdown['deleteSpace']   = 'Delete Space';
$lang->doc->libDropdown['addModule']     = 'Add Directory';
$lang->doc->libDropdown['addSameModule'] = 'Add Same Directory';
$lang->doc->libDropdown['addSubModule']  = 'Add Sub Directory';
$lang->doc->libDropdown['editModule']    = 'Edit Directory';
$lang->doc->libDropdown['delModule']     = 'Delete Directory';

$lang->doc->featureBar['tableContents']['all']   = 'All';
$lang->doc->featureBar['tableContents']['draft'] = 'Draft';

$lang->doc->featureBar['myspace']['all']   = 'All';
$lang->doc->featureBar['myspace']['draft'] = 'Draft';

$lang->doc->showDocList[1] = 'Yes';
$lang->doc->showDocList[0] = 'No';

$lang->doc->whitelistDeny['product']   = "<i class='icon pr-1 text-important icon-exclamation'></i>User <span class='px-1 text-important'>%s</span> currently has no product access permission, therefore cannot access the document. To access, please maintain product access control permissions.";
$lang->doc->whitelistDeny['project']   = "<i class='icon pr-1 text-important icon-exclamation'></i>User <span class='px-1 text-important'>%s</span> currently has no project access permission, therefore cannot access the document. To access, please maintain project access control permissions.";
$lang->doc->whitelistDeny['execution'] = "<i class='icon pr-1 text-important icon-exclamation'></i>User <span class='px-1 text-important'>%s</span> currently has no execution access permission, therefore cannot access the document. To access, please maintain execution access control permissions.";
$lang->doc->whitelistDeny['doc']       = "<i class='icon pr-1 text-important icon-exclamation'></i>User <span class='px-1 text-important'>%s</span> currently has no library access permission, therefore cannot access the document. To access, please maintain library access control permissions.";

$lang->doc->filterTypes[] = array('all', 'All');
$lang->doc->filterTypes[] = array('draft', 'Draft');
$lang->doc->filterTypes[] = array('collect', 'Collected by me');
$lang->doc->filterTypes[] = array('createdByMe', 'Created by me');
$lang->doc->filterTypes[] = array('editedByMe', 'Edited by me');

$lang->doc->fileFilterTypes[] = array('all', 'All');
$lang->doc->fileFilterTypes[] = array('addedByMe', 'Add by me');

$lang->doc->productFilterTypes[] = array('all',  'All');
$lang->doc->productFilterTypes[] = array('mine', 'Mine');

$lang->doc->projectFilterTypes[] = array('all', 'All');
$lang->doc->projectFilterTypes[] = array('mine', 'Involved');

$lang->doc->spaceFilterTypes[] = array('all', 'All');

$lang->doc->docLang                              = new stdClass();
$lang->doc->docLang->cancel                      = $lang->cancel;
$lang->doc->docLang->export                      = $lang->export;
$lang->doc->docLang->exportWord                  = "Export Word";
$lang->doc->docLang->exportPdf                   = "Export PDF";
$lang->doc->docLang->exportImage                 = "Export Image";
$lang->doc->docLang->exportHtml                  = "Export HTML";
$lang->doc->docLang->exportMarkdown              = "Export Markdown";
$lang->doc->docLang->exportJSON                  = "Export Backup(.json)";
$lang->doc->docLang->importMarkdown              = "Import Markdown";
$lang->doc->docLang->importConfluence            = "Import Confluence Storage";
$lang->doc->docLang->importJSON                  = "Import Backup(.json)";
$lang->doc->docLang->importConfirm               = "Import will overwrite the current document content. Are you sure you want to import?";
$lang->doc->docLang->settings                    = $lang->settings;
$lang->doc->docLang->save                        = $lang->save;
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createLib                   = $lang->doc->createLib;
$lang->doc->docLang->actions                     = $lang->doc->libDropdown;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->create                      = $lang->doc->createAB;
$lang->doc->docLang->createDoc                   = $lang->doc->create;
$lang->doc->docLang->editDoc                     = $lang->doc->edit;
$lang->doc->docLang->effort                      = $lang->doc->effort;
$lang->doc->docLang->deleteDoc                   = $lang->doc->delete;
$lang->doc->docLang->uploadDoc                   = $lang->doc->uploadDoc;
$lang->doc->docLang->createList                  = $lang->doc->createList;
$lang->doc->docLang->confirmDelete               = $lang->doc->confirmDelete;
$lang->doc->docLang->confirmDeleteWithSub        = $lang->doc->confirmDeleteWithSub;
$lang->doc->docLang->confirmDeleteLib            = $lang->doc->confirmDeleteLib;
$lang->doc->docLang->confirmDeleteSpace          = $lang->doc->confirmDeleteSpace;
$lang->doc->docLang->confirmDeleteModule         = $lang->doc->confirmDeleteModule;
$lang->doc->docLang->confirmDeleteModuleWithSub  = $lang->doc->confirmDeleteModuleWithSub;
$lang->doc->docLang->confirmDeleteChapter        = $lang->doc->confirmDeleteChapter;
$lang->doc->docLang->confirmDeleteChapterWithSub = $lang->doc->confirmDeleteChapterWithSub;
$lang->doc->docLang->collect                     = $lang->doc->collect;
$lang->doc->docLang->edit                        = $lang->doc->edit;
$lang->doc->docLang->delete                      = $lang->doc->delete;
$lang->doc->docLang->cancelCollection            = $lang->doc->cancelCollection;
$lang->doc->docLang->moveDoc                     = $lang->doc->moveDocAction;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->moveLib                     = $lang->doc->moveLibAction;
$lang->doc->docLang->moduleName                  = $lang->doc->catalogName;
$lang->doc->docLang->saveDraft                   = $lang->doc->saveDraft;
$lang->doc->docLang->release                     = $lang->doc->release;
$lang->doc->docLang->batchMove                   = $lang->doc->batchMove;
$lang->doc->docLang->filterTypes                 = $lang->doc->filterTypes;
$lang->doc->docLang->fileFilterTypes             = $lang->doc->fileFilterTypes;
$lang->doc->docLang->productFilterTypes          = $lang->doc->productFilterTypes;
$lang->doc->docLang->projectFilterTypes          = $lang->doc->projectFilterTypes;
$lang->doc->docLang->spaceFilterTypes            = $lang->doc->spaceFilterTypes;
$lang->doc->docLang->sortCatalog                 = $lang->doc->sortCatalog;
$lang->doc->docLang->sortDoclib                  = $lang->doc->sortDoclib;
$lang->doc->docLang->sortDoc                     = $lang->doc->sortDoc;
$lang->doc->docLang->errorOccurred               = $lang->doc->errorOccurred;
$lang->doc->docLang->selectLibFirst              = $lang->doc->selectLibFirst;
$lang->doc->docLang->createLibFirst              = $lang->doc->createLibFirst;
$lang->doc->docLang->space                       = 'Space';
$lang->doc->docLang->spaceTypeNames              = array();
$lang->doc->docLang->spaceTypeNames['mine']      = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['product']   = $lang->productCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['project']   = $lang->projectCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['execution'] = $lang->executionCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['api']       = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['custom']    = $lang->doc->docLang->space;
$lang->doc->docLang->enterSpace                  = 'Enter Space';
$lang->doc->docLang->noDocs                      = 'No documents';
$lang->doc->docLang->noFiles                     = 'No files';
$lang->doc->docLang->noLibs                      = 'No libraries';
$lang->doc->docLang->noModules                   = 'No directories';
$lang->doc->docLang->docsTotalInfo               = 'Total: {0}';
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createModule                = $lang->doc->addCatalog;
$lang->doc->docLang->leaveEditingConfirm         = 'The document is currently being edited. Do you want to leave the editing page?';
$lang->doc->docLang->saveDocFailed               = 'Failed to save the document';
$lang->doc->docLang->loadingDocsData             = 'Loading document data...';
$lang->doc->docLang->loadDataFailed              = 'Load data failed.';
$lang->doc->docLang->noSpaceTip                  = 'No space, please create one.';
$lang->doc->docLang->searchModulePlaceholder     = 'Search directory';
$lang->doc->docLang->searchDocPlaceholder        = 'Search document';
$lang->doc->docLang->searchChapterPlaceholder    = 'Search Chapter';
$lang->doc->docLang->searchSpacePlaceholder      = 'Search space';
$lang->doc->docLang->searchLibPlaceholder        = 'Search library';
$lang->doc->docLang->searchPlaceholder           = 'Search';
$lang->doc->docLang->newDocLabel                 = 'New';
$lang->doc->docLang->editingDocLabel             = 'Editing';
$lang->doc->docLang->filesLib                    = $lang->doclib->files;
$lang->doc->docLang->currentDocVersionHint       = 'Current version';
$lang->doc->docLang->viewsCount                  = $lang->doc->views;
$lang->doc->docLang->keywords                    = $lang->doc->keywords;
$lang->doc->docLang->keywordsPlaceholder         = $lang->doc->keywordsTips;
$lang->doc->docLang->loadingDocTip               = 'Loading document...';
$lang->doc->docLang->loadingEditorTip            = 'Loading editor...';
$lang->doc->docLang->pasteImageTip               = $lang->noticePasteImg;
$lang->doc->docLang->downloadFile                = 'Download';
$lang->doc->docLang->loadingFilesTip             = 'Loading files...';
$lang->doc->docLang->recTotalFormat              = $lang->pager->totalCountAB;
$lang->doc->docLang->recPerPageFormat            = $lang->pager->pageSizeAB;
$lang->doc->docLang->firstPage                   = $lang->pager->firstPage;
$lang->doc->docLang->prevPage                    = $lang->pager->previousPage;
$lang->doc->docLang->nextPage                    = $lang->pager->nextPage;
$lang->doc->docLang->lastPage                    = $lang->pager->lastPage;
$lang->doc->docLang->docOutline                  = 'Outline';
$lang->doc->docLang->noOutline                   = 'No outline';
$lang->doc->docLang->loading                     = $lang->loading;
$lang->doc->docLang->libNamePrefix               = 'Lib:';
$lang->doc->docLang->colon                       = $lang->colon;
$lang->doc->docLang->createdByUserAt             = 'Created by {name} at {time}';
$lang->doc->docLang->editedByUserAt              = 'Edited by {name} at {time}';
$lang->doc->docLang->docInfo                     = 'Document Information';
$lang->doc->docLang->docStatus                   = $lang->doc->status;
$lang->doc->docLang->creator                     = $lang->doc->addedByAB;
$lang->doc->docLang->createDate                  = $lang->doc->addedDate;
$lang->doc->docLang->modifier                    = $lang->doc->editedBy;
$lang->doc->docLang->editDate                    = $lang->doc->editedDate;
$lang->doc->docLang->collectCount                = $lang->doc->docCollects;
$lang->doc->docLang->collected                   = 'Collected';
$lang->doc->docLang->history                     = $lang->history;
$lang->doc->docLang->updateHistory               = $lang->doc->updateInfo;
$lang->doc->docLang->updateInfoFormat            = '{name} at {time}';
$lang->doc->docLang->noUpdateInfo                = 'No update info';
$lang->doc->docLang->enterFullscreen             = 'Enter Fullscreen';
$lang->doc->docLang->exitFullscreen              = 'Exit Fullscreen';
$lang->doc->docLang->collapse                    = 'Collapse';
$lang->doc->docLang->draft                       = $lang->doc->statusList['draft'];
$lang->doc->docLang->released                    = $lang->doc->statusList['normal'];
$lang->doc->docLang->attachment                  = $lang->doc->files;
$lang->doc->docLang->docTitleRequired            = 'Please enter the document title。';
$lang->doc->docLang->docTitlePlaceholder         = 'Enter the document title';
$lang->doc->docLang->noDataYet                   = 'No data yet';
$lang->doc->docLang->position                    = $lang->doc->position;
$lang->doc->docLang->relateObject                = 'Related Objects';
$lang->doc->docLang->showHasDocsOnlyProduct      = 'Show only products with doc';
$lang->doc->docLang->showHasDocsOnlyProject      = 'Show only projects with doc';
$lang->doc->docLang->showClosedProduct           = 'Show closed products';
$lang->doc->docLang->showClosedProject           = 'Show closed projects';
$lang->doc->docLang->noProducts                  = 'No products';
$lang->doc->docLang->noProjects                  = 'No projects';
$lang->doc->docLang->productMine                 = 'My responsibility';
$lang->doc->docLang->projectMine                 = 'My participation';
$lang->doc->docLang->productOther                = 'Other';
$lang->doc->docLang->projectOther                = 'Other';
$lang->doc->docLang->accessDenied                = $lang->doc->accessDenied;
$lang->doc->docLang->convertToNewDoc             = 'Convert document';
$lang->doc->docLang->convertToNewDocConfirm      = 'Are you ready to convert this document to the new format using a modern block-level editor? The changes will be effective after saving the document, and it will no longer be possible to use the old version of the editor.';
$lang->doc->docLang->created                     = 'created';
$lang->doc->docLang->edited                      = 'edited';
$lang->doc->docLang->notSaved                    = 'Not saved';
$lang->doc->docLang->oldDocEditingTip            = 'This document is being created in the old editor, the new editor is now enabled for editing. If you want to switch to the new editor, please save the document first.';
$lang->doc->docLang->switchToOldEditor           = 'Switch to old editor';
$lang->doc->docLang->zentaoList                  = $lang->doc->zentaoList;
$lang->doc->docLang->list                        = $lang->doc->list;
$lang->doc->docLang->loadingFile                 = 'Downloading document images...';
$lang->doc->docLang->needEditable                = $lang->doc->needEditable;
$lang->doc->docLang->addChapter                  = $lang->doc->addChapter;
$lang->doc->docLang->editChapter                 = $lang->doc->editChapter;
$lang->doc->docLang->sortChapter                 = $lang->doc->sortChapter;
$lang->doc->docLang->deleteChapter               = $lang->doc->deleteChapter;
$lang->doc->docLang->addSubChapter               = $lang->doc->addSubChapter;
$lang->doc->docLang->addSameChapter              = $lang->doc->addSameChapter;
$lang->doc->docLang->addSubDoc                   = $lang->doc->addSubDoc;
$lang->doc->docLang->chapterName                 = $lang->doc->chapterName;
$lang->doc->docLang->autoSaveHint                = 'Auto saved';
$lang->doc->docLang->editing                     = 'Editing';
$lang->doc->docLang->restoreVersionHint          = 'Restore to version';
$lang->doc->docLang->restoreVersion              = 'Restore';
$lang->doc->docLang->restoreVersionConfirm       = 'This will create a new version using the content of version {version}. Are you sure you want to continue?';
