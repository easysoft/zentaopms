<?php
declare(strict_types=1);
/**
 * The browse view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('group') feature bar on main menu. */
featureBar
(
    set::current('all'),
);

/* zin: Define the toolbar on main menu. */
$canManagePriv     = hasPriv('group', 'managePriv');
$canCreateGroup    = hasPriv('group', 'create');

if($canManagePriv) $managePrivItem = array('class' => 'btn ghost', 'text' => $lang->group->managePrivByModule, 'url' => inLink('managePriv', 'type=byModule'), 'data-toggle' => 'modal');
if($canCreateGroup) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->group->create, 'url' => inLink('create', '', '', true),  'data-toggle' => 'modal');
toolbar
(
    $canManagePriv ? item(set($managePrivItem)) : null,
    $canCreateGroup ? item(set($createItem)) : null
);

jsVar('confirmDelete', $lang->group->confirmDelete);
$tableData = initTableData($groups, $config->group->dtable->fieldList, $this->group);
dtable
(
    set::cols(array_values($config->group->dtable->fieldList)),
    set::data($tableData)
);

/* ====== Render page ====== */
render();
