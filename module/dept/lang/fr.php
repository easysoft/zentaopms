<?php
/**
 * The dept module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.pm
 */
$lang->dept->common      = 'Compartiments';
$lang->dept->manageChild = "Entreprise > Compartiments > Domaines";
$lang->dept->edit        = "Editer Compartiment";
$lang->dept->delete      = "Supprimer Compartiment";
$lang->dept->parent      = "Comp. Parent";
$lang->dept->manager     = "Responsable";
$lang->dept->name        = "Nom du Compartiment";
$lang->dept->browse      = "Consulter Compartiment";
$lang->dept->manage      = "Gérer Compartiment";
$lang->dept->updateOrder = "Rang Compartiment";
$lang->dept->add         = "Ajout Compartiment";
$lang->dept->grade       = "Grade du Compartiment";
$lang->dept->order       = "Ordre du Compartiment";
$lang->dept->dragAndSort = "Déplacez pour classer";

$lang->dept->confirmDelete = " Confirmez que vous voulez bien supprimer ce Compartiment ?";
$lang->dept->successSave   = " Sauvé !";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = 'Ce Compartiment possède des domaines. Vous ne pouvez pas le supprimer !';
$lang->dept->error->hasUsers = 'Ce Compartiment possède des utilisateurs. Vous ne pouvez pas le supprimer !';
