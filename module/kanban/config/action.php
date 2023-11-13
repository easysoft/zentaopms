<?php
global $lang;

$config->kanban->actionList = array();
$config->kanban->actionList['editCard']['icon']        = 'edit';
$config->kanban->actionList['editCard']['text']        = $lang->kanbancard->edit;
$config->kanban->actionList['editCard']['hint']        = $lang->kanbancard->edit;
$config->kanban->actionList['editCard']['url']         = array('module' => 'kanban', 'method' => 'editCard', 'params' => 'cardID={id}');
$config->kanban->actionList['editCard']['data-toggle'] = 'modal';

$config->kanban->actionList['finishCard']['icon']      = 'checked';
$config->kanban->actionList['finishCard']['text']      = $lang->kanban->finishCard;
$config->kanban->actionList['finishCard']['hint']      = $lang->kanban->finishCard;
$config->kanban->actionList['finishCard']['url']       = array('module' => 'kanban', 'method' => 'finishCard', 'params' => 'cardID={id}&kanbanID={kanban}');
$config->kanban->actionList['finishCard']['className'] = 'ajax-submit';

$config->kanban->actionList['activateCard']['icon']        = 'magic';
$config->kanban->actionList['activateCard']['text']        = $lang->kanban->activateCard;
$config->kanban->actionList['activateCard']['hint']        = $lang->kanban->activateCard;
$config->kanban->actionList['activateCard']['url']         = array('module' => 'kanban', 'method' => 'activateCard', 'params' => 'cardID={id}&kanbanID={kanban}');
$config->kanban->actionList['activateCard']['data-toggle'] = 'modal';

$config->kanban->actionList['archiveCard']['icon']         = 'ban-circle';
$config->kanban->actionList['archiveCard']['text']         = $lang->kanban->archiveCard;
$config->kanban->actionList['archiveCard']['hint']         = $lang->kanban->archiveCard;
$config->kanban->actionList['archiveCard']['url']          = array('module' => 'kanban', 'method' => 'archiveCard', 'params' => 'cardID={id}');
$config->kanban->actionList['archiveCard']['data-confirm'] =  $lang->kanbancard->confirmArchive;
$config->kanban->actionList['archiveCard']['className']    = 'ajax-submit';

$config->kanban->actionList['restoreCard']['icon']         = 'back';
$config->kanban->actionList['restoreCard']['text']         = $lang->kanban->restoreCard;
$config->kanban->actionList['restoreCard']['hint']         = $lang->kanban->restoreCard;
$config->kanban->actionList['restoreCard']['url']          = array('module' => 'kanban', 'method' => 'restoreCard', 'params' => 'cardID={id}');
$config->kanban->actionList['restoreCard']['data-confirm'] =  $lang->kanbancard->confirmRestore;
$config->kanban->actionList['restoreCard']['className']    = 'ajax-submit';

$config->kanban->actionList['deleteCard']['icon']         = 'trash';
$config->kanban->actionList['deleteCard']['text']         = $lang->kanbancard->delete;
$config->kanban->actionList['deleteCard']['hint']         = $lang->kanbancard->delete;
$config->kanban->actionList['deleteCard']['url']          = array('module' => 'kanban', 'method' => 'deleteCard', 'params' => 'cardID={id}');
$config->kanban->actionList['deleteCard']['data-confirm'] =  $lang->kanbancard->confirmDelete;
$config->kanban->actionList['deleteCard']['className']    = 'ajax-submit';
