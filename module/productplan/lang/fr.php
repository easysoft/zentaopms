<?php
/**
 * The productplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: en.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->productplan->common     = $lang->productCommon . ' Plan';
$lang->productplan->browse     = "Liste des Plans";
$lang->productplan->index      = "Liste";
$lang->productplan->create     = "Créer Plan";
$lang->productplan->edit       = "Editer Plan";
$lang->productplan->delete     = "Supprimer Plan";
$lang->productplan->view       = "Détail Plan";
$lang->productplan->bugSummary = "Total <strong>%s</strong> Bugs sur cette page.";
$lang->productplan->basicInfo  = 'Infos de Base';
$lang->productplan->batchEdit  = 'Edition par Lot';

$lang->productplan->batchUnlink      = "Retirer par lot";
$lang->productplan->linkStory        = "Planifier Story";
$lang->productplan->unlinkStory      = "Retirer Story";
$lang->productplan->unlinkStoryAB    = "Retirer";
$lang->productplan->batchUnlinkStory = "Retirer par Lot";
$lang->productplan->linkedStories    = 'Stories Planifiées';
$lang->productplan->unlinkedStories  = 'Stories non Planifiées';
$lang->productplan->updateOrder      = 'Ordre';
$lang->productplan->createChildren   = "Créer Sous-Plans";

$lang->productplan->linkBug          = "Planifier Bug";
$lang->productplan->unlinkBug        = "Retirer Bug";
$lang->productplan->batchUnlinkBug   = "Retirer Bugs par Lot";
$lang->productplan->linkedBugs       = 'Bugs Planifiés';
$lang->productplan->unlinkedBugs     = 'Bugs non Planifiés';
$lang->productplan->unexpired        = 'Plans non échus';
$lang->productplan->all              = 'Tous les Plans';

$lang->productplan->confirmDelete      = "Voulez-vous supprimer ce plan ?";
$lang->productplan->confirmUnlinkStory = "Voulez-vous détacher cette Story du Plan ?";
$lang->productplan->confirmUnlinkBug   = "Voulez-vous retirer ce bug du plan ?";
$lang->productplan->noPlan             = "Aucun plan pour l'instant. ";
$lang->productplan->cannotDeleteParent = 'Impossible de supprimer le plan parent';

$lang->productplan->id         = 'ID';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = 'Plateforme/Branche';
$lang->productplan->title      = 'Titre';
$lang->productplan->desc       = 'Description';
$lang->productplan->begin      = 'Début';
$lang->productplan->end        = 'Fin';
$lang->productplan->last       = 'Dernier Plan';
$lang->productplan->future     = 'A Définir';
$lang->productplan->stories    = 'Story';
$lang->productplan->bugs       = 'Bug';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->parent     = "Plan Parent";
$lang->productplan->parentAB   = "Parent";
$lang->productplan->children   = "Sous-Plan";
$lang->productplan->childrenAB = "C";
$lang->productplan->order      = "Order";
$lang->productplan->deleted    = "Deleted";

$lang->productplan->endList[7]    = '1 Semaine';
$lang->productplan->endList[14]   = '2 Semaines';
$lang->productplan->endList[31]   = '1 Mois';
$lang->productplan->endList[62]   = '2 Mois';
$lang->productplan->endList[93]   = '3 Mois';
$lang->productplan->endList[186]  = '6 Mois';
$lang->productplan->endList[365]  = '1 Année';

$lang->productplan->errorNoTitle = 'ID %s titre ne doit pas être à blanc.';
$lang->productplan->errorNoBegin = "ID %s l'heure de début devrait être renseignée.";
$lang->productplan->errorNoEnd   = "ID %s l'heure de fin devrait être renseignée.";
$lang->productplan->beginGeEnd   = "ID %s l'heure de début ne doit pas être >= à l'heure de fin.";

$lang->productplan->featureBar['browse']['all']       = 'Tous';
$lang->productplan->featureBar['browse']['unexpired'] = 'Non échus';
$lang->productplan->featureBar['browse']['overdue']   = 'Echus';
