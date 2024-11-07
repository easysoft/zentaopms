<?php
declare(strict_types=1);
/**
 * The browse template view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Dai Tingting<daitingting@xirangit.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$spaceID = 1;
$libID   = $menu['id'];
$data    = array('spaceID' => $spaceID, 'docs' => array_values($templateList));
$data['spaces'][] = array('name' => $lang->doc->template, 'id' => $spaceID);
foreach($config->doc->templateMenu as $item) $data['libs'][] = $item + array('space' => $spaceID);

$privs = array();

$filterTypes = $lang->docTemplate->filterTypes;
if(!hasPriv('doc', 'editDocTemplate')) $filterTypes = array_values(array_filter($filterTypes, function($item){ return $item[0] != 'draft'; }));

$tableCols = array();
$tableCols['id']         = $lang->docTemplate->id;
$tableCols['title']      = $lang->docTemplate->title;
$tableCols['frequency']  = $lang->docTemplate->frequency;
$tableCols['type']       = $lang->docTemplate->type;
$tableCols['addedBy']    = $lang->docTemplate->addedBy;
$tableCols['addedDate']  = $lang->docTemplate->addedDate;
$tableCols['editedBy']   = $lang->docTemplate->editedBy;
$tableCols['editedDate'] = $lang->docTemplate->editedDate;
$tableCols['views']      = $lang->docTemplate->views;

$langData = array();
$langData['filterTypes']    = $filterTypes;
$langData['tableCols']      = $tableCols;
$langData['create']         = $lang->docTemplate->create;
$langData['edit']           = $lang->docTemplate->edit;
$langData['delete']         = $lang->docTemplate->delete;
$langData['editTemplate']   = $lang->docTemplate->edit;
$langData['deleteTemplate'] = $lang->docTemplate->delete;
$langData['confirmDelete']  = $lang->docTemplate->confirmDelete;

docApp
(
    set::data($data),
    set::spaceID($spaceID),
    set::libID($libID),
    set::docID($docID),
    set::noSpace(),
    set::noModule(),
    set::homeName(false),
    set::mode($docID ? 'view' : 'list'),
    set::privs($privs),
    set::userMap($users),
    set::spaceIcon('home'),
    set::langData($langData),
    set::pager(array('recTotal' => count($templateList), 'recPerPage' => $recPerPage, 'page' => $pageID))
);

render();
