<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: en.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->doclib = new stdclass();
$lang->doclib->name       = 'Nom';
$lang->doclib->control    = 'Droit';
$lang->doclib->group      = 'Groupe';
$lang->doclib->user       = 'Utilisateur';
$lang->doclib->files      = 'Pièces Jointes';
$lang->doclib->all        = 'Toutes les Libraries';
$lang->doclib->select     = 'Sélect.';
$lang->doclib->execution  = 'Bibliothèque ' . $lang->executionCommon;
$lang->doclib->product    = $lang->productCommon . ' Library';
$lang->doclib->apiLibName = 'Library Name';
$lang->doclib->privateACL = "Private (Only creators and whitelisted users with %s permissions can access it)";

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "When execution is empty, the library created is the {$lang->projectCommon} library";

$lang->doclib->type['wiki'] = 'Wiki';
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

/* Fields. */
$lang->doc->common       = 'Gestion Documentaire';
$lang->doc->id           = 'ID';
$lang->doc->product      = $lang->productCommon;
$lang->doc->project      = $lang->projectCommon;
$lang->doc->execution    = $lang->execution->common;
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
$lang->doc->addedByAB    = 'CreatedBy';
$lang->doc->addedDate    = 'CreatedDate';
$lang->doc->editedBy     = 'Màj par';
$lang->doc->editedDate   = 'UpdatedDate';
$lang->doc->editingDate  = 'Editing user and time';
$lang->doc->lastEditedBy = 'Last Editor';
$lang->doc->version      = 'Version';
$lang->doc->basicInfo    = 'Infos de Base';
$lang->doc->deleted      = 'Supprimé';
$lang->doc->fileObject   = 'Objet Dépendant';
$lang->doc->whiteList    = 'Liste Blanche';
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
$lang->doc->position     = 'Position';
$lang->doc->person       = 'Person';
$lang->doc->team         = 'Team';
$lang->doc->manage       = 'Document Management';
$lang->doc->release      = 'Release';

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
$lang->doc->delete           = 'Supprimer Document';
$lang->doc->createBook       = 'Create Book';
$lang->doc->browse           = 'Liste Documents';
$lang->doc->view             = 'Détail Document';
$lang->doc->diff             = 'Diff';
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
$lang->doc->cancelCollection = 'Retirer des Favoris';
$lang->doc->deleteFile       = 'Supprimer Fichier';
$lang->doc->menuTitle        = 'Menu';
$lang->doc->api              = 'API';
$lang->doc->displaySetting   = 'Display Settings';
$lang->doc->collectAction    = 'Add Favorite';

$lang->doc->libName           = 'Bibliothèque de Documents';
$lang->doc->libType           = 'Catégorie';
$lang->doc->custom            = 'Personnaliser Bibliothèque de Documents';
$lang->doc->customAB          = 'Person. Bib Doc';
$lang->doc->createLib         = 'Create Library';
$lang->doc->allLibs           = 'Liste des Bibliothèque';
$lang->doc->objectLibs        = "{$lang->productCommon}/{$lang->executionCommon} Bibliothèque";
$lang->doc->showFiles         = 'Pièces Jointes';
$lang->doc->editLib           = 'Edit Document Library';
$lang->doc->deleteLib         = 'Supprimer Bibliothèque';
$lang->doc->fixedMenu         = 'Coller au Menu';
$lang->doc->removeMenu        = 'Décoller du Menu';
$lang->doc->search            = 'Rechercher';
$lang->doc->allCollections    = 'All Collections';
$lang->doc->keywordsTips      = 'Please use commas to separate multiple keywords.';
$lang->doc->sortLibs          = 'Sort Libs';
$lang->doc->titlePlaceholder  = 'Veuillez saisir le titre';
$lang->doc->confirm           = 'Confirm';
$lang->doc->docSummary        = 'Total: <strong>%s</strong>.';
$lang->doc->docCheckedSummary = 'Seleted: <strong>%total%</strong>.';
$lang->doc->showDoc           = 'Whether to display documents';
$lang->doc->uploadFile        = 'Upload File';
$lang->doc->uploadDoc         = 'Upload Document';
$lang->doc->uploadFormat      = 'Upload Format';
$lang->doc->editedList        = 'File editor';

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

$lang->doc->space = 'Space';
$lang->doc->spaceList['mine']    = 'My Space';
$lang->doc->spaceList['product'] = $lang->productCommon . ' Space';
$lang->doc->spaceList['project'] = $lang->projectCommon . ' Space';
$lang->doc->spaceList['api']     = 'API Space';
$lang->doc->spaceList['custom']  = 'Team Space';

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

$lang->doc->types['doc'] = 'Wiki';
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
$lang->doc->mail->create = new stdclass();
$lang->doc->mail->edit   = new stdclass();
$lang->doc->mail->create->title = "%s created document #%s:%s";
$lang->doc->mail->edit->title   = "%s edited document #%s:%s";

$lang->doc->confirmDelete        = "Voulez-vous supprimer ce document ?";
$lang->doc->confirmDeleteLib     = "Voulez-vous supprimer cette Bibliothèque ?";
$lang->doc->confirmDeleteBook    = "Do you want to delete this book?";
$lang->doc->confirmDeleteChapter = "Do you want to delete this chapter?";
$lang->doc->confirmDeleteModule  = "Do you want to delete this module?";
$lang->doc->confirmOtherEditing  = "This document is currently editing. Continuing to edit will overwrite the content edited by others. Do you want to continue?";
$lang->doc->errorEditSystemDoc   = "Vous n'avez pas besoin de changer de système de Bibliothèque.";
$lang->doc->errorEmptyProduct    = "Aucun {$lang->productCommon}. Il ne peut pas être créé.";
$lang->doc->errorEmptyProject    = "Aucun {$lang->executionCommon}. Il ne peut pas être créé.";
$lang->doc->errorMainSysLib      = "Cette Bibliothèque ne peut pas être supprimée.";
$lang->doc->accessDenied         = "Access is denied !";
$lang->doc->versionNotFount      = "N'existe pas dans ce build.";
$lang->doc->noDoc                = 'Aucun documents. ';
$lang->doc->noArticle            = 'No articles.';
$lang->doc->noLib                = 'No libraries.';
$lang->doc->noBook               = 'The Wiki library has not created a manual, please create a new one :)';
$lang->doc->cannotCreateOffice   = 'Désolé, %s peut seulement être créé dans Série ZenTao Biz ou plus. Coordonnées Philip@easysoft.ltd En savoir plus sur les séries zentao biz et zentao Max.';
$lang->doc->notSetOffice         = "<p>To create a %s document, you need to configure <a href='%s' target='_parent'>office convert</a>.<p>";
$lang->doc->noSearchedDoc        = 'Aucun documents trouvé.';
$lang->doc->noEditedDoc          = "Vous n'avez pas mis à jour de documents.";
$lang->doc->noOpenedDoc          = "Vous n'avez pas ajouté de documents.";
$lang->doc->noCollectedDoc       = "Vous avez aucun document dans vos favoris.";
$lang->doc->errorEmptyLib        = 'No data in document library.';
$lang->doc->confirmUpdateContent = 'You have a document that is not saved from last time. Do you want to continue editing it?';
$lang->doc->selectLibType        = 'Please select a type of doc library.';
$lang->doc->noLibreOffice        = 'You does not have access to office conversion settings!';
$lang->doc->errorParentChapter   = 'The parent chapter cannot be its own chapter or sub chapter!';

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
