<?php
/**
 * The doc module english file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: en.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.pm
 */
/* Fields. */
$lang->doc->common         = 'Gestion Documentaire';
$lang->doc->id             = 'ID';
$lang->doc->product        = $lang->productCommon;
$lang->doc->execution      = $lang->executionCommon;
$lang->doc->lib            = 'Bibliothèque';
$lang->doc->module         = 'Catégorie';
$lang->doc->title          = 'Nom';
$lang->doc->digest         = 'Résumé';
$lang->doc->comment        = 'Commentaire';
$lang->doc->type           = 'Type';
$lang->doc->content        = 'Texte';
$lang->doc->keywords       = 'Tags';
$lang->doc->url            = 'URL';
$lang->doc->files          = 'Fichiers';
$lang->doc->addedBy        = 'Auteur';
$lang->doc->addedDate      = 'Ajouté le';
$lang->doc->editedBy       = 'Màj par';
$lang->doc->editedDate     = 'Màj le';
$lang->doc->version        = 'Version';
$lang->doc->basicInfo      = 'Infos de Base';
$lang->doc->deleted        = 'Supprimé';
$lang->doc->fileObject     = 'Objet Dépendant';
$lang->doc->whiteList      = 'Liste Blanche';
$lang->doc->contentType    = 'Format';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = 'Nom du Fichier';
$lang->doc->filePath       = 'Chemin Fichier';
$lang->doc->extension      = 'Extension';
$lang->doc->size           = 'Taille';
$lang->doc->download       = 'Téléchargement';
$lang->doc->acl            = 'Droit';
$lang->doc->fileName       = 'Fichiers';
$lang->doc->groups         = 'Groupes';
$lang->doc->users          = 'Utilisateurs';
$lang->doc->item           = ' Objets';
$lang->doc->num            = 'Documents';
$lang->doc->searchResult   = 'Résultat de Recherche';
$lang->doc->mailto         = 'Mailto';
$lang->doc->noModule       = 'No document in this lib, please create it';
$lang->doc->noChapter      = 'No chapters or articles in this book. Please add chapters and articles.';

$lang->doc->moduleDoc      = 'Par Module';
$lang->doc->searchDoc      = 'Rechercher';
$lang->doc->fast           = 'Saisie rapide';
$lang->doc->allDoc         = 'Tous les Documents';
$lang->doc->openedByMe     = 'Mes';
$lang->doc->editedByMe     = 'Edited By Me';
$lang->doc->orderByOpen    = 'Récemment Ajoutés';
$lang->doc->orderByEdit    = 'Récemment Mis à Jour';
$lang->doc->orderByVisit   = 'Dernière Visite';
$lang->doc->todayEdited    = "Mis à Jour Aujourd'hui";
$lang->doc->pastEdited     = 'Total Mis à Jour';
$lang->doc->myDoc          = 'Mes Documents';
$lang->doc->myCollection   = 'Mes Favoris';

/* 方法列表。*/
$lang->doc->index            = 'Accueil Documents';
$lang->doc->create           = 'Ajouter Document';
$lang->doc->edit             = 'Editer Document';
$lang->doc->delete           = 'Supprimer Document';
$lang->doc->createBook       = 'Create Book';
$lang->doc->browse           = 'Liste Documents';
$lang->doc->view             = 'Détail Document';
$lang->doc->diff             = 'Diff';
$lang->doc->diffAction       = 'Document Diff';
$lang->doc->sort             = 'Rang Document';
$lang->doc->manageType       = 'Gérer Catégories';
$lang->doc->editType         = 'Editer';
$lang->doc->deleteType       = 'Supprimer';
$lang->doc->addType          = 'Ajouter';
$lang->doc->childType        = 'Catégories';
$lang->doc->catalogName      = 'Catalog Name';
$lang->doc->collect          = 'Ajouter aux Favoris';
$lang->doc->cancelCollection = 'Retirer des Favoris';
$lang->doc->deleteFile       = 'Supprimer Fichier';

$lang->doc->libName        = 'Bibliothèque de Documents';
$lang->doc->libType        = 'Catégorie';
$lang->doc->custom         = 'Personnaliser Bibliothèque de Documents';
$lang->doc->customAB       = 'Person. Bib Doc';
$lang->doc->createlib      = 'Créer une Bibliothèque';
$lang->doc->allLibs        = 'Liste des Bibliothèque';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->executionCommon} Bibliothèque";
$lang->doc->showFiles      = 'Pièces Jointes';
$lang->doc->editlib        = 'Editer Bibliothèque';
$lang->doc->deleteLib      = 'Supprimer Bibliothèque';
$lang->doc->fixedMenu      = 'Coller au Menu';
$lang->doc->removeMenu     = 'Décoller du Menu';
$lang->doc->search         = 'Rechercher';

/* 查询条件列表 */
$lang->doc->allProduct    = 'Tous les' . $lang->productCommon . 's';
$lang->doc->allExecutions = 'Tous les' . $lang->executionCommon . 's';

$lang->doc->libTypeList['product']   = $lang->productCommon . ' Library';
$lang->doc->libTypeList['execution'] = 'Bibliothèque ' . $lang->executionCommon;
$lang->doc->libTypeList['custom']    = 'Bib. Personnalisée';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon;
$lang->doc->systemLibs['execution'] = $lang->executionCommon;

$lang->doc->aclList['open']    = 'Public';
$lang->doc->aclList['custom']  = 'Person.';
$lang->doc->aclList['private'] = 'Privé';

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

$lang->doc->orderLib = 'Paramétrage Rang';
$lang->doc->customShowLibs = 'Paramétrage Affichage';
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
$lang->doc->confirmDeleteChapter = "Do you want to delete this chapter?";
$lang->doc->errorEditSystemDoc   = "Vous n'avez pas besoin de changer de système de Bibliothèque.";
$lang->doc->errorEmptyProduct    = "Aucun {$lang->productCommon}. Il ne peut pas être créé.";
$lang->doc->errorEmptyProject    = "Aucun {$lang->executionCommon}. Il ne peut pas être créé.";
$lang->doc->errorMainSysLib      = "Cette Bibliothèque ne peut pas être supprimée.";
$lang->doc->accessDenied         = "Access is denied !";
$lang->doc->versionNotFount      = "N'existe pas dans ce build.";
$lang->doc->noDoc                = 'Aucun documents. ';
$lang->doc->noArticle            = 'No articles.';
$lang->doc->noLib                = 'No libraries. ';
$lang->doc->noBook               = 'The WIKI library has not created a manual, please create a new one :)';
$lang->doc->cannotCreateOffice   = 'Désolé, %s peut seulement être créé dans ZenTao Enterprise. Contactez Zentao à renee@easysoft.ltd pour essayer ZenTao Enterprise.';
$lang->doc->notSetOffice         = "<p>To create a %s document, you need to configure <a href='%s' target='_parent'>office convert</a>.<p>";
$lang->doc->noSearchedDoc        = 'Aucun documents trouvé.';
$lang->doc->noEditedDoc          = "Vous n'avez pas mis à jour de documents.";
$lang->doc->noOpenedDoc          = "Vous n'avez pas ajouté de documents.";
$lang->doc->noCollectedDoc       = "Vous avez aucun document dans vos favoris.";
$lang->doc->errorEmptyLib        = 'No data in document library.';

$lang->doc->noticeAcl['lib']['product']['default']   = 'Les utilisateurs qui ont accès au Product peuvent y accéder.';
$lang->doc->noticeAcl['lib']['product']['custom']    = 'Les utilisateurs qui ont accès au Product ou les utilisateurs de la Liste Blanche peuvent y accéder.';
$lang->doc->noticeAcl['lib']['project']['default']   = 'Les utilisateurs qui ont accès au Projet peuvent y accéder.';
$lang->doc->noticeAcl['lib']['project']['custom']    = 'Les utilisateurs qui ont accès au Projet ou les utilisateurs de la Liste Blanche peuvent y accéder.';
$lang->doc->noticeAcl['lib']['execution']['default'] = "Les utilisateurs qui ont accès au {$lang->executionCommon} peuvent y accéder.";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "Les utilisateurs qui ont accès au {$lang->executionCommon} ou les utilisateurs de la Liste Blanche peuvent y accéder.";
$lang->doc->noticeAcl['lib']['custom']['open']       = 'Tous les utilisateurs peuvent y accéder.';
$lang->doc->noticeAcl['lib']['custom']['custom']     = 'Les utilisateurs de la Liste Blanche peuvent y accéder.';
$lang->doc->noticeAcl['lib']['custom']['private']    = 'Seulement le créateur de la Bibliothèque peut y accéder.';

$lang->doc->noticeAcl['doc']['open']    = 'Les utilisateurs qui ont accès à la Bibliothèque à laquelle le document appartient peuvent y accéder.';
$lang->doc->noticeAcl['doc']['custom']  = 'Les utilisateurs de la Liste Blanche peuvent y accéder.';
$lang->doc->noticeAcl['doc']['private'] = 'Seulement celui qui a ajouté le document peut y accéder.';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = 'URL';

$lang->doclib = new stdclass();
$lang->doclib->name      = 'Nom';
$lang->doclib->control   = "Contrôle d'Accès";
$lang->doclib->group     = 'Groupe';
$lang->doclib->user      = 'Utilisateur';
$lang->doclib->files     = 'Pièces Jointes';
$lang->doclib->all       = 'Toutes les Libraries';
$lang->doclib->select    = 'Sélect.';
$lang->doclib->execution = 'Bibliothèque ' . $lang->executionCommon;
$lang->doclib->product   = $lang->productCommon . ' Library';

$lang->doclib->aclListA['default'] = 'Défaut';
$lang->doclib->aclListA['custom']  = 'Person.';

$lang->doclib->aclListB['open']    = 'Public';
$lang->doclib->aclListB['custom']  = 'Person.';
$lang->doclib->aclListB['private'] = 'Privée';

$lang->doclib->create['product']   = 'Créer ' . $lang->productCommon . ' Library';
$lang->doclib->create['execution'] = 'Créer ' . 'Bibliothèque ' . $lang->executionCommon;
$lang->doclib->create['custom']    = 'Créer Bibliothèque Personnelle';

$lang->doclib->main['product']   = 'Bibliothèque Principale';
$lang->doclib->main['execution'] = 'Bibliothèque Principale';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = 'Personnelle';

$lang->doclib->nameList['custom'] = 'Nom personnalisé';
