<?php
/**
 * The dept module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dept
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->dept->common       = 'Abteilung';
$lang->dept->id           = 'ID';
$lang->dept->path         = 'Path';
$lang->dept->position     = 'Position';
$lang->dept->manageChild  = "Abteilung";
$lang->dept->edit         = "Bearbeiten";
$lang->dept->delete       = "Löschen";
$lang->dept->parent       = "Übergeordnet";
$lang->dept->manager      = "Manager";
$lang->dept->name         = "Name";
$lang->dept->browse       = "Verwalten";
$lang->dept->manage       = "Pflegen";
$lang->dept->updateOrder  = "Sortieren";
$lang->dept->add          = "Hinzufügen";
$lang->dept->grade        = "Grade";
$lang->dept->order        = "Rank";
$lang->dept->dragAndSort  = "Verschieben und sortieren";
$lang->dept->noDepartment = "No Department";

$lang->dept->confirmDelete = " Möchten Sie diese Abteilung löschen?";
$lang->dept->successSave   = " Gespeichert!";
$lang->dept->repeatDepart  = " Es gibt einen doppelten Abteilungsnamen, möchten Sie ihn wirklich hinzufügen?";

$lang->dept->error = new stdclass();
$lang->dept->error->hasSons  = 'Diese Abteilung hat Unterabteilungen und kann daher nicht gelöscht werden!';
$lang->dept->error->hasUsers = 'Diese Abteilung hat Mitarbeiter und kann daher nicht gelöscht werden!';
