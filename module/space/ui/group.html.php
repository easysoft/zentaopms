<?php
declare(strict_types=1);
/**
 * The manageGroup view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('confirmDelete', $lang->space->notice->confirmDelete);

dropmenu
(
    set::module('space'),
    set::tab('space'),
    set::objectID($spaceID),
    set::url(createLink('space', 'ajaxGetDropMenu', "spaceID=$spaceID&module={$app->rawModule}&method={$app->rawMethod}"))
);

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::linkParams("spaceID={$spaceID}"),
    set::current('all')
);

/* zin: Define the toolbar on main menu. */
$createGroupURL = $this->createLink('space', 'createGroup', "spaceID={$spaceID}");
$importGroupURL = $this->createLink('space', 'importGroup', "spaceID={$spaceID}");
toolbar
(
    hasPriv('space', 'importGroup') ? item(set
    (
        array
        (
            'icon'        => 'plus',
            'text'        => $lang->space->importGroup,
            'class'       => "primary create-project-btn",
            'url'         => $importGroupURL,
            'data-toggle' => 'modal'
        )
    )) : null,
    hasPriv('space', 'createGroup') ? item(set
    (
        array
        (
            'icon'        => 'plus',
            'text'        => $lang->group->create,
            'class'       => "primary create-project-btn",
            'url'         => $createGroupURL,
            'data-toggle' => 'modal'
        )
    )) : null
);

$groups = initTableData($groups, $config->spaceGroup->dtable->fieldList, $this->space);

/* zin: Define the dtable in main content. */
dtable
(
    set::fixedLeftWidth('30%'),
    set::cols(array_values($config->spaceGroup->dtable->fieldList)),
    set::data($groups),
    set::emptyTip($lang->group->noGroup),
    set::createTip($lang->group->create),
    set::createLink(hasPriv('space', 'createGroup') ? createLink('space', 'createGroup', "spaceID={$spaceID}") : ''),
    set::createAttr("data-toggle='modal'")
);
