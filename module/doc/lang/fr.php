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
$lang->doclib->name         = 'Nom';
$lang->doclib->control      = 'Droit';
$lang->doclib->group        = 'Groupe';
$lang->doclib->user         = 'Utilisateur';
$lang->doclib->files        = 'Pièces Jointes';
$lang->doclib->all          = 'Toutes les Libraries';
$lang->doclib->select       = 'Sélect.';
$lang->doclib->execution    = 'Bibliothèque ' . $lang->executionCommon;
$lang->doclib->product      = $lang->productCommon . ' Library';
$lang->doclib->apiLibName   = 'Library Name';
$lang->doclib->defaultSpace = 'Default Space';
$lang->doclib->defaultMyLib = 'My Library';
$lang->doclib->spaceName    = 'Space Name';
$lang->doclib->createSpace  = 'Create Space';
$lang->doclib->editSpace    = 'Edit Space';
$lang->doclib->privateACL   = "Private (Only creators and whitelisted users with %s permissions can access it)";
$lang->doclib->defaultOrder = 'Doc order';
$lang->doclib->migratedWiki = 'Migré Wiki';

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "When execution is empty, the library created is the {$lang->projectCommon} library";

$lang->doclib->type['wiki'] = 'Doc';
$lang->doclib->type['api']  = 'API';

$lang->doclib->aclListA = array();
$lang->doclib->aclListA['default'] = 'Défaut';
$lang->doclib->aclListA['custom']  = 'Person.';

$lang->doclib->aclListB['open']    = 'Public';
$lang->doclib->aclListB['custom']  = 'Person.';
$lang->doclib->aclListB['private'] = 'Privée';

$lang->doclib->mySpaceAclList['private'] = "Private (Only creators can access it)";

$lang->doclib->aclList = array();
$lang->doclib->aclList['open']    = "Public (Users who can access doccan access it)";
$lang->doclib->aclList['default'] = "Default (Users who can access the selected %s or users in the whiltelist can access it)";
$lang->doclib->aclList['private'] = "Private (Only the one who created it or users in the whiltelist can access it)";

$lang->doclib->idOrder = array();
$lang->doclib->idOrder['id_asc']  = 'ID ascending order';
$lang->doclib->idOrder['id_desc'] = 'ID descending order' ;

$lang->doclib->create['product']   = 'Créer ' . $lang->productCommon . ' Library';
$lang->doclib->create['execution'] = 'Créer ' . 'Bibliothèque ' . $lang->executionCommon;
$lang->doclib->create['custom']    = 'Créer Bibliothèque Personnelle';

$lang->doclib->main['product']   = 'Bibliothèque Principale';
$lang->doclib->main['project']   = 'Primary Library';
$lang->doclib->main['execution'] = 'Bibliothèque Principale';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = 'Personnelle';

$lang->doclib->nameList['custom'] = 'Nom personnalisé';

$lang->doclib->apiNameUnique = array();
$lang->doclib->apiNameUnique['product'] = 'In the api library of the same ' . $lang->productCommon . ', ';
$lang->doclib->apiNameUnique['project'] = 'In the api library of the same ' . $lang->projectCommon . ', ';
$lang->doclib->apiNameUnique['nolink']  = 'In the no linked api library, ';

$lang->docTemplate = new stdclass();
$lang->docTemplate->id                           = 'ID';
$lang->docTemplate->title                        = 'Template Title';
$lang->docTemplate->frequency                    = 'Frequency';
$lang->docTemplate->type                         = 'Category';
$lang->docTemplate->addedBy                      = 'Added By';
$lang->docTemplate->addedDate                    = 'Added Date';
$lang->docTemplate->editedBy                     = 'Edited By';
$lang->docTemplate->editedDate                   = 'Edited Date';
$lang->docTemplate->views                        = 'Views';
$lang->docTemplate->confirmDelete                = 'Do you want to delete this document template?';
$lang->docTemplate->scope                        = 'Scope';
$lang->docTemplate->lib                          = $lang->docTemplate->scope;
$lang->docTemplate->module                       = 'Category';
$lang->docTemplate->desc                         = 'Describe';
$lang->docTemplate->deliverable                  = 'Is Deliverable';
$lang->docTemplate->parentModule                 = 'Parent';
$lang->docTemplate->typeName                     = 'Category Name';
$lang->docTemplate->parent                       = 'Level';
$lang->docTemplate->addTemplateType              = 'Add template category';
$lang->docTemplate->editTemplateType             = 'Edit template category';
$lang->docTemplate->docTitlePlaceholder          = 'Enter the document template title';
$lang->docTemplate->docTitleRequired             = 'Please enter the doc template title.';
$lang->docTemplate->errorDeleteType              = 'There is document template exists in the current category, it cannot be deleted.';
$lang->docTemplate->convertToNewDocConfirm       = 'Are you ready to convert this document template to the new format using a modern block-level editor? After saving as a draft or publishing, you can no longer switch back to the old editor.';
$lang->docTemplate->oldDocEditingTip             = 'This document template is being created in the old editor, the new editor is now enabled for editing. If you want to switch to the new editor, please save the document template first.';
$lang->docTemplate->leaveEditingConfirm          = 'The template is currently being edited. Do you want to leave the editing page?';
$lang->docTemplate->searchScopePlaceholder       = 'Search Scope';
$lang->docTemplate->searchTypePlaceholder        = 'Search Category';
$lang->docTemplate->moveDocTemplate              = 'Move Template';
$lang->docTemplate->moveSubTemplate              = 'Move Sub Template';
$lang->docTemplate->createTypeFirst              = 'Please create a template category first.';
$lang->docTemplate->editedList                   = 'Template editor';
$lang->docTemplate->content                      = 'Template Text';
$lang->docTemplate->templateDesc                 = 'Template Desc';
$lang->docTemplate->status                       = 'Template Status';
$lang->docTemplate->emptyTip                     = 'There is no system data that conform to this parameter and filtering conditions.';
$lang->docTemplate->emptyDataTip                 = 'There is no system data that conform to this filtering conditions.';
$lang->docTemplate->previewTip                   = 'After configuring the parameters, this block will display the corresponding list data according to the filter configuration.';
$lang->docTemplate->confirmDeleteChapterWithSub  = "After deleting a chapter, the lower level content of the chapter will be hidden. Are you sure you want to delete the chapter?";
$lang->docTemplate->confirmDeleteTemplateWithSub = "After deleting the template, the lower level content of the template will be hidden. Are you sure you want to delete this template?";
$lang->docTemplate->scopeHasTemplateTips         = 'There are templates within this scope. Please remove them before deleting the scope.';
$lang->docTemplate->scopeHasModuleTips           = 'There are template category data within this scope. Please remove them before deleting the scope.';
$lang->docTemplate->needEditable                 = 'This template is not editable.';

$lang->docTemplate->create = 'Create Template';
$lang->docTemplate->edit   = 'Edit Template';
$lang->docTemplate->delete = 'Delete Template';

$lang->docTemplate->more       = 'More';
$lang->docTemplate->scopeLabel = 'Scope';
$lang->docTemplate->noTemplate = 'No Doc Template';
$lang->docTemplate->noDesc     = 'No Desc';
$lang->docTemplate->of         = ' of ';
$lang->docTemplate->overdue    = 'Overdue';

$lang->docTemplate->addModule         = 'Add Template Category';
$lang->docTemplate->addSameModule     = 'Add Same Template Category';
$lang->docTemplate->addSubModule      = 'Add Sub Template Category';
$lang->docTemplate->editModule        = 'Edit Template Category';
$lang->docTemplate->deleteModule      = 'Delete Template Category';
$lang->docTemplate->noModules         = 'No Categories';
$lang->docTemplate->addSubDocTemplate = 'Add sub template';

$lang->docTemplate->filterTypes = array();
$lang->docTemplate->filterTypes[] = array('all', 'All');
$lang->docTemplate->filterTypes[] = array('draft', 'Draft');
$lang->docTemplate->filterTypes[] = array('released', 'Released');
$lang->docTemplate->filterTypes[] = array('createdByMe', 'Created By Me');

$lang->docTemplate->deliverableList['1'] = 'Yes';
$lang->docTemplate->deliverableList['0'] = 'No';

/* Fields. */
$lang->doc->common       = 'Gestion Documentaire';
$lang->doc->id           = 'ID';
$lang->doc->product      = $lang->productCommon;
$lang->doc->project      = $lang->projectCommon;
$lang->doc->execution    = $lang->execution->common;
$lang->doc->plan         = $lang->productplan->shortCommon;
$lang->doc->lib          = 'Bibliothèque';
$lang->doc->module       = 'Catégorie';
$lang->doc->libAndModule = 'Library&Catalog';
$lang->doc->object       = 'Object';
$lang->doc->title        = 'Nom';
$lang->doc->digest       = 'Résumé';
$lang->doc->comment      = 'Commentaire';
$lang->doc->type         = 'Type';
$lang->doc->content      = 'Texte';
$lang->doc->keywords     = 'Keywords';
$lang->doc->status       = 'Status';
$lang->doc->url          = 'URL';
$lang->doc->files        = 'Fichiers';
$lang->doc->addedBy      = 'Auteur';
$lang->doc->addedDate    = 'CreatedDate';
$lang->doc->editedBy     = 'Màj par';
$lang->doc->editedDate   = 'UpdatedDate';
$lang->doc->editingDate  = 'Editing user and time';
$lang->doc->lastEditedBy = 'Last Editor';
$lang->doc->updateInfo   = 'Mise à jour des informations';
$lang->doc->version      = 'Version';
$lang->doc->basicInfo    = 'Infos de Base';
$lang->doc->deleted      = 'Supprimé';
$lang->doc->fileObject   = 'Objet Dépendant';
$lang->doc->whiteList    = 'Liste Blanche';
$lang->doc->readonly     = 'Readonly';
$lang->doc->editable     = 'Editable';
$lang->doc->contentType  = 'Format';
$lang->doc->separator    = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle    = 'Nom du Fichier';
$lang->doc->filePath     = 'Chemin Fichier';
$lang->doc->extension    = 'Extension';
$lang->doc->size         = 'Taille de la pièce jointe';
$lang->doc->source       = 'Source';
$lang->doc->download     = 'Téléchargement';
$lang->doc->acl          = 'Droit';
$lang->doc->fileName     = 'Fichiers';
$lang->doc->groups       = 'Groupes';
$lang->doc->users        = 'Utilisateurs';
$lang->doc->item         = ' Objets';
$lang->doc->num          = 'Documents';
$lang->doc->searchResult = 'Résultat de Recherche';
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
$lang->doc->template     = 'Template';
$lang->doc->position     = 'Position';
$lang->doc->person       = 'Person';
$lang->doc->team         = 'Team';
$lang->doc->manage       = 'Document Management';
$lang->doc->release      = 'Release';
$lang->doc->story        = 'Story';
$lang->doc->convertdoc   = 'Convert doc';
$lang->doc->needEditable = 'This document is not editable.';
$lang->doc->needReadable = 'This document is not readable.';
$lang->doc->groupLabel   = 'Groupes';
$lang->doc->userLabel    = 'Utilisateurs';

$lang->doc->moduleDoc     = 'Par Module';
$lang->doc->searchDoc     = 'Rechercher';
$lang->doc->fast          = 'Saisie rapide';
$lang->doc->allDoc        = 'Tous les Documents';
$lang->doc->allVersion    = 'All Versions';
$lang->doc->openedByMe    = 'Mes';
$lang->doc->editedByMe    = 'Edited By Me';
$lang->doc->orderByOpen   = 'Récemment Ajoutés';
$lang->doc->orderByEdit   = 'Récemment Mis à Jour';
$lang->doc->orderByVisit  = 'Dernière Visite';
$lang->doc->todayEdited   = "Mis à Jour Aujourd'hui";
$lang->doc->pastEdited    = 'Total Mis à Jour';
$lang->doc->myDoc         = 'Mes Documents';
$lang->doc->myView        = 'Recently Viewed';
$lang->doc->myCollection  = 'Mes Favoris';
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
$lang->doc->create           = 'Ajouter Document';
$lang->doc->createOrUpload   = 'Create/Upload Document';
$lang->doc->edit             = 'Editer Document';
$lang->doc->effort           = 'Effort';
$lang->doc->delete           = 'Supprimer Document';
$lang->doc->createBook       = 'Create Book';
$lang->doc->browse           = 'Liste Documents';
$lang->doc->view             = 'Détail Document';
$lang->doc->diff             = 'Diff';
$lang->doc->confirm          = 'Confirm';
$lang->doc->cancelDiff       = 'Cancel diff';
$lang->doc->diffAction       = 'Document Diff';
$lang->doc->sort             = 'Rang Document';
$lang->doc->manageType       = 'Gérer Catégories';
$lang->doc->editType         = 'Editer';
$lang->doc->editChildType    = 'Manage';
$lang->doc->deleteType       = 'Supprimer';
$lang->doc->addType          = 'Ajouter';
$lang->doc->childType        = 'Catégories';
$lang->doc->catalogName      = 'Catalog Name';
$lang->doc->collect          = 'Ajouter aux Favoris';
$lang->doc->collectSuccess   = 'Add Favorite';
$lang->doc->cancelCollection = 'Retirer des Favoris';
$lang->doc->deleteFile       = 'Supprimer Fichier';
$lang->doc->menuTitle        = 'Menu';
$lang->doc->api              = 'API';
$lang->doc->displaySetting   = 'Display Settings';
$lang->doc->collectAction    = 'Add Favorite';

$lang->doc->libName            = 'Bibliothèque de Documents';
$lang->doc->libType            = 'Catégorie';
$lang->doc->custom             = 'Personnaliser Bibliothèque de Documents';
$lang->doc->customAB           = 'Person. Bib Doc';
$lang->doc->createLib          = 'Create Library';
$lang->doc->createLibAction    = 'Create Library';
$lang->doc->createSpace        = 'Create Space';
$lang->doc->allLibs            = 'Liste des Bibliothèque';
$lang->doc->objectLibs         = "{$lang->productCommon}/{$lang->executionCommon} Bibliothèque";
$lang->doc->showFiles          = 'Pièces Jointes';
$lang->doc->editLib            = 'Edit Document Library';
$lang->doc->editSpaceAction    = 'Edit Space';
$lang->doc->editLibAction      = 'Edit Library';
$lang->doc->deleteSpaceAction  = 'Delete Space';
$lang->doc->deleteLibAction    = 'Delete Library';
$lang->doc->moveLibAction      = 'Move Library';
$lang->doc->moveDocAction      = 'Move Document';
$lang->doc->batchMove          = 'Batch Move';
$lang->doc->batchMoveDocAction = 'Batch Move Document';
$lang->doc->fixedMenu          = 'Coller au Menu';
$lang->doc->removeMenu         = 'Décoller du Menu';
$lang->doc->search             = 'Rechercher';
$lang->doc->allCollections     = 'All Collections';
$lang->doc->keywordsTips       = 'Please use commas to separate multiple keywords.';
$lang->doc->sortLibs           = 'Sort Libs';
$lang->doc->titlePlaceholder   = 'Veuillez saisir le titre';
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
$lang->doc->frozenTips         = 'After the doc are baselined, %s is not allowed.';

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
$lang->doc->chapterName    = 'Name';

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
$lang->doc->zentaoList['projectCase'] = $lang->projectCommon . $lang->testcase->common;
$lang->doc->zentaoList['caselib']     = 'Case Library' . $lang->testcase->common;

$lang->doc->zentaoList['task']       = $lang->task->common;
$lang->doc->zentaoList['bug']        = $lang->bug->common;
$lang->doc->zentaoList['projectBug'] = $lang->projectCommon . 'Bug';
$lang->doc->zentaoList['productBug'] = 'Product Bug';
$lang->doc->zentaoList['planBug']    = 'Plan Bug';

$lang->doc->zentaoList['more']               = 'More';
$lang->doc->zentaoList['productPlan']        = $lang->productCommon . ' Plan';
$lang->doc->zentaoList['productPlanContent'] = $lang->productCommon . ' Plan Content';
$lang->doc->zentaoList['productRelease']     = $lang->productCommon . $lang->release->common;
$lang->doc->zentaoList['projectRelease']     = $lang->projectCommon . $lang->release->common;
$lang->doc->zentaoList['ER']                 = $lang->defaultERName;
$lang->doc->zentaoList['UR']                 = $lang->URCommon;
$lang->doc->zentaoList['feedback']           = 'Feedback';
$lang->doc->zentaoList['ticket']             = 'Ticket';
$lang->doc->zentaoList['gantt']              = 'Gantt Chart';

$lang->doc->zentaoList['HLDS'] = 'Outline Design';
$lang->doc->zentaoList['DDS']  = 'Detailed Design';
$lang->doc->zentaoList['DBDS'] = 'Database Design';
$lang->doc->zentaoList['ADS']  = 'Application Design';

$lang->doc->zentaoAction = array();
$lang->doc->zentaoAction['set']       = 'Setting';
$lang->doc->zentaoAction['delete']    = 'Delete';
$lang->doc->zentaoAction['setParams'] = 'Set Parameters';

$lang->doc->uploadFormatList = array();
$lang->doc->uploadFormatList['separateDocs'] = 'Save files as different document';
$lang->doc->uploadFormatList['combinedDocs'] = 'Save files as one document';

$lang->doc->fileType = new stdclass();
$lang->doc->fileType->stepResult = 'Case Result';

global $config;
/* Query condition list. */
$lang->doc->allProduct    = 'Tous les' . $lang->productCommon . 's';
$lang->doc->allExecutions = 'Tous les' . $lang->execution->common . 's';
$lang->doc->allProjects   = 'All' . $lang->projectCommon . 's';

$lang->doc->libTypeList['product']   = $lang->productCommon . ' Library';
$lang->doc->libTypeList['project']   = 'Project Library';
$lang->doc->libTypeList['execution'] = 'Bibliothèque ' . $lang->execution->common;
$lang->doc->libTypeList['api']       = 'API Library';
$lang->doc->libTypeList['custom']    = 'Bib. Personnalisée';

$lang->doc->libGlobalList['api'] = 'Api Libray';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon;
$lang->doc->systemLibs['execution'] = $lang->executionCommon;

$lang->doc->statusList['']       = "";
$lang->doc->statusList['normal'] = "Released";
$lang->doc->statusList['draft']  = "Draft";

$lang->doc->aclList['open']    = "Public (Access with library permissions)";
$lang->doc->aclList['private'] = "Private (Only creators and whitelist users can access)";

$lang->doc->aclListA['open']    = "Public (Everyone can access it, with permission to edit document templates for access and maintenance)";
$lang->doc->aclListA['private'] = "Private (Only creators can edit and use it)";

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

$lang->doc->browseType             = 'Catégorie';
$lang->doc->browseTypeList['list'] = 'Liste';
$lang->doc->browseTypeList['grid'] = 'Vignettes';

$lang->doc->fastMenuList['byediteddate']  = 'Récemment Mis à Jour';
//$lang->doc->fastMenuList['visiteddate']   = 'Recently Visited';
$lang->doc->fastMenuList['openedbyme']    = 'Mes Documents';
$lang->doc->fastMenuList['collectedbyme'] = 'Mes Favoris';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = 'Montrer Bibliothèque des Pièces jointes';
$lang->doc->customObjectLibs['customFiles'] = 'Montrer Library Personnelle';

$lang->doc->orderLib                       = 'Paramétrage Rang';
$lang->doc->customShowLibs                 = 'Paramétrage Affichage';
$lang->doc->customShowLibsList['zero']     = 'Montrer Bibliothèques Vides';
$lang->doc->customShowLibsList['children'] = 'Montrer sous-catégorie de Documents';
$lang->doc->customShowLibsList['unclosed'] = "Montrer {$lang->executionCommon}s actifs seulement";

$lang->doc->mail = new stdclass();
$lang->doc->mail->releasedDoc = new stdclass();
$lang->doc->mail->edit        = new stdclass();
$lang->doc->mail->releasedDoc->title = "%s released document #%s:%s";
$lang->doc->mail->edit->title        = "%s edited document #%s:%s";

$lang->doc->confirmDelete               = "Voulez-vous supprimer ce document ?";
$lang->doc->confirmDeleteWithSub        = "After deleting the document, the sub contents will be deleted simultaneously. Are you sure you want to delete this document?";
$lang->doc->confirmDeleteLib            = "Voulez-vous supprimer cette Bibliothèque ?";
$lang->doc->confirmDeleteSpace          = "Do you want to delete this space?";
$lang->doc->confirmDeleteBook           = "Do you want to delete this book?";
$lang->doc->confirmDeleteChapter        = "Do you want to delete this chapter?";
$lang->doc->confirmDeleteChapterWithSub = "After deleting the chapter, the sub chapters and documents will be deleted simultaneously. Are you sure you want to delete this chapter?";
$lang->doc->confirmDeleteModule         = "Do you want to delete this module?";
$lang->doc->confirmDeleteModuleWithSub  = "After deleting the module, the sub modules and documents will be deleted simultaneously. Are you sure you want to delete this module?";
$lang->doc->confirmOtherEditing         = "This document is currently editing. Continuing to edit will overwrite the content edited by others. Do you want to continue?";
$lang->doc->errorEditSystemDoc          = "Vous n'avez pas besoin de changer de système de Bibliothèque.";
$lang->doc->errorEmptyProduct           = "Aucun {$lang->productCommon}. Il ne peut pas être créé.";
$lang->doc->errorEmptyProject           = "Aucun {$lang->executionCommon}. Il ne peut pas être créé.";
$lang->doc->errorEmptySpaceLib          = "There is no document library in this space. It cannot be created. Please create a document library first";
$lang->doc->errorMainSysLib             = "Cette Bibliothèque ne peut pas être supprimée.";
$lang->doc->accessDenied                = "Access is denied !";
$lang->doc->cannotView                  = "You do not have permission to view this document. Please contact the creator %s.";
$lang->doc->versionNotFount             = "N'existe pas dans ce build.";
$lang->doc->noDoc                       = 'Aucun documents. ';
$lang->doc->noArticle                   = 'No articles.';
$lang->doc->noLib                       = 'No libraries.';
$lang->doc->noBook                      = 'The Wiki library has not created a manual, please create a new one :)';
$lang->doc->cannotCreateOffice          = 'Désolé, %s peut seulement être créé dans Série ZenTao Biz ou plus. Coordonnées Philip@easysoft.ltd En savoir plus sur les séries zentao biz et zentao Max.';
$lang->doc->notSetOffice                = "<p>To create a %s document, you need to configure <a href='%s'>Collabora Online</a>.<p>";
$lang->doc->requestTypeError            = "The current requestType configuration is not PATH_INFO, cannot use the online editing. Please contact the administrator to modify the requestType configuration.";
$lang->doc->notSetCollabora             = "Collabora Online is not set up, unable to create %s document. Please configure <a href='%s'>Collabora Online</a>.";
$lang->doc->noSearchedDoc               = 'Aucun documents trouvé.';
$lang->doc->noEditedDoc                 = "Vous n'avez pas mis à jour de documents.";
$lang->doc->noOpenedDoc                 = "Vous n'avez pas ajouté de documents.";
$lang->doc->noCollectedDoc              = "Vous avez aucun document dans vos favoris.";
$lang->doc->errorEmptyLib               = 'No data in document library.';
$lang->doc->confirmUpdateContent        = 'You have a document that is not saved from last time. Do you want to continue editing it?';
$lang->doc->selectLibType               = 'Please select a type of doc library.';
$lang->doc->selectDoc                   = 'Please select a doc';
$lang->doc->noLibreOffice               = 'You does not have access to office conversion settings!';
$lang->doc->errorParentChapter          = 'The parent chapter cannot be its own chapter or sub chapter!';
$lang->doc->errorOthersCreated          = 'There are documents created by others in this library. You cannot move it.';
$lang->doc->confirmLeaveOnEdit          = 'Vérifiez que vous avez du contenu de document non sauvegardé, voulez-vous continuer?';
$lang->doc->errorOccurred               = 'An error occurred. Please try again later.';
$lang->doc->selectLibFirst              = 'Please select a lib first.';
$lang->doc->createLibFirst              = 'Please create a lib first.';
$lang->doc->nopriv                      = 'You do not have access permissions for %s and cannot view this document. If you need to adjust permissions, please contact relevant personnel.';
$lang->doc->docConvertComment           = "This document has been converted to the new editor format. Switch to version %s to view the document before conversion.";
$lang->doc->previewNotAvailable         = 'Preview function is not available. Please visit the Kanban board to view the document %s.';
$lang->doc->hocuspocusConnect           = 'The collaborative editing service is connected.';
$lang->doc->hocuspocusDisconnect        = 'The collaborative editing service is disconnected. Edits will be synchronized after reconnection.';
$lang->doc->docTemplateConvertComment   = 'The template has been converted to the new editor format. Switch to version %s to view the template before conversion.';
$lang->doc->noSupportList               = "This {$lang->projectCommon} does not support %s.";

$lang->doc->noticeAcl['lib']['product']['default']   = "Users who can access the selected {$lang->productCommon} can access it.";
$lang->doc->noticeAcl['lib']['product']['custom']    = "Users who can access the selected {$lang->productCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['project']['default']   = "Users who can access the selected {$lang->projectCommon} can access it.";
$lang->doc->noticeAcl['lib']['project']['open']      = "Users who can access the selected {$lang->projectCommon} can access it.";
$lang->doc->noticeAcl['lib']['project']['private']   = "Users who can access the selected {$lang->projectCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['project']['custom']    = "Users who can access the selected {$lang->projectCommon} or users in the whiltelist can access it.";
$lang->doc->noticeAcl['lib']['execution']['default'] = "Les utilisateurs qui ont accès au {$lang->execution->common} peuvent y accéder.";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "Les utilisateurs qui ont accès au {$lang->execution->common} ou les utilisateurs de la Liste Blanche peuvent y accéder.";
$lang->doc->noticeAcl['lib']['api']['open']          = 'All users can access it.';
$lang->doc->noticeAcl['lib']['api']['custom']        = 'Users in the whitelist can access it.';
$lang->doc->noticeAcl['lib']['api']['private']       = 'Only the one who created it can access it.';
$lang->doc->noticeAcl['lib']['custom']['open']       = 'Tous les utilisateurs peuvent y accéder.';
$lang->doc->noticeAcl['lib']['custom']['custom']     = 'Les utilisateurs de la Liste Blanche peuvent y accéder.';
$lang->doc->noticeAcl['lib']['custom']['private']    = 'Seulement le créateur de la Bibliothèque peut y accéder.';

$lang->doc->noticeAcl['doc']['open']    = 'Les utilisateurs qui ont accès à la Bibliothèque à laquelle le document appartient peuvent y accéder.';
$lang->doc->noticeAcl['doc']['custom']  = 'Les utilisateurs de la Liste Blanche peuvent y accéder.';
$lang->doc->noticeAcl['doc']['private'] = 'Seulement celui qui a ajouté le document peut y accéder.';

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

$lang->doc->manageScope        = 'Manage Scope';
$lang->doc->browseTemplate     = 'Template Square';
$lang->doc->createTemplate     = 'Create Document Template';
$lang->doc->editTemplate       = 'Edit Document Template';
$lang->doc->moveTemplate       = 'Move Document Template';
$lang->doc->deleteTemplate     = 'Delete Document Template';
$lang->doc->viewTemplate       = 'View Document Template';
$lang->doc->addTemplateType    = 'Create Category';
$lang->doc->editTemplateType   = 'Edit Category';
$lang->doc->deleteTemplateType = 'Delete Category';
$lang->doc->sortTemplate       = 'Sort';

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
$lang->doc->docLang->uploadDoc                   = $lang->doc->uploadFile;
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
$lang->doc->docLang->template                    = $lang->doc->template;
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
$lang->doc->docLang->creator                     = $lang->doc->addedBy;
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
$lang->doc->docLang->frozenTips                  = $lang->doc->frozenTips;

$lang->docTemplate->types = array();
$lang->docTemplate->types['plan']   = 'Plan';
$lang->docTemplate->types['story']  = 'Story';
$lang->docTemplate->types['design'] = 'Design';
$lang->docTemplate->types['dev']    = 'Development';
$lang->docTemplate->types['test']   = 'Test';
$lang->docTemplate->types['desc']   = 'Description';
$lang->docTemplate->types['other']  = 'Other';

$lang->docTemplate->builtInScopes = array();
$lang->docTemplate->builtInScopes['rnd']  = array();
$lang->docTemplate->builtInScopes['or']   = array();
$lang->docTemplate->builtInScopes['lite'] = array();
$lang->docTemplate->builtInScopes['rnd']['product']   = 'Product';
$lang->docTemplate->builtInScopes['rnd']['project']   = 'Project';
$lang->docTemplate->builtInScopes['rnd']['execution'] = 'Execution';
$lang->docTemplate->builtInScopes['rnd']['personal']  = 'Personal';
$lang->docTemplate->builtInScopes['or']['market']     = 'Market';
$lang->docTemplate->builtInScopes['or']['product']    = 'Product';
$lang->docTemplate->builtInScopes['or']['personal']   = 'Personal';
$lang->docTemplate->builtInScopes['lite']['project']  = 'Project';
$lang->docTemplate->builtInScopes['lite']['personal'] = 'Personal';
