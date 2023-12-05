<?php
/**
 * The dept module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->dept->id           = 'ID';
$lang->dept->path         = 'Path';
$lang->dept->position     = 'Position';
$lang->dept->manageChild  = "Entreprise > Compartiments > Domaines";
$lang->dept->edit         = "Editer Compartiment";
$lang->dept->delete       = "Supprimer Compartiment";
$lang->dept->parent       = "Comp. Parent";
$lang->dept->manager      = "Responsable";
$lang->dept->name         = "Nom du Compartiment";
$lang->dept->browse       = "Consulter Compartiment";
$lang->dept->manage       = "Gérer Compartiment";
$lang->dept->updateOrder  = "Department Ranking";
$lang->dept->add          = "Ajout Compartiment";
$lang->dept->grade        = "Grade du Compartiment";
$lang->dept->order        = "Ordre du Compartiment";
$lang->dept->dragAndSort  = "Déplacez pour classer";
$lang->dept->noDepartment = "No Department";

$lang->dept->manageChildAction = "Manage Subordinate Department";

$lang->dept->confirmDelete = " Confirmez que vous voulez bien supprimer ce Compartiment ?";
$lang->dept->successSave   = " Sauvé !";
$lang->dept->repeatDepart  = " Il y a un nom de département en double, êtes-vous sûr de l'ajouter ?";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = 'Ce Compartiment possède des domaines. Vous ne pouvez pas le supprimer !';
$lang->dept->error->hasUsers = 'Ce Compartiment possède des utilisateurs. Vous ne pouvez pas le supprimer !';
