<?php
declare(strict_types=1);
/**
 * The browse view file of stage module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     stage
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Define the set::module('release') feature bar on main menu. */
/* zin: Define the toolbar on main menu. */
$canCreateStage      = hasPriv('stage', 'create');
$canbatchCreateStage = hasPriv('stage', 'batchCreate');
if($canCreateStage) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->stage->create, 'url' => $this->createLink('stage', 'create', "type={$type}"), 'data-toggle' => 'modal');
if($canbatchCreateStage) $batchCreateItem = array('icon' => 'plus', 'class' => 'primary mr-4', 'text' => $lang->stage->batchCreate, 'url' => $this->createLink('stage', 'batchCreate', "type={$type}"));
div
(
    setClass('main-col main-content'),
    div
    (
        setClass('main-header flex-auto'),
        div
        (
            setClass('flex-auto'),
            html('<strong>' . $lang->stage->browse . '</strong>')
        ),
        toolbar
        (
            !empty($batchCreateItem) ? item(set($batchCreateItem)) : null,
            !empty($createItem) ? item(set($createItem)) : null
        )
    )
);

if(hasPriv('stage', 'settype'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('stage', 'settype')),
            $lang->stage->setType
        )
    );
}

$menuItems[] = li
(
    setClass('menu-item'),
    a
    (
        setClass('active'),
        set::href(createLink('stage', 'browse')),
        $lang->stage->browse
    )
);

sidebar
(
    div
    (
        setClass('cell p-2.5 bg-white'),
        menu($menuItems)
    )
);

$tableData = initTableData($stages, $config->stage->dtable->fieldList, $this->stage);
dtable
(
    set::cols($config->stage->dtable->fieldList),
    set::data($tableData),
    set::orderBy($orderBy),
    set::sortLink(createLink('stage', 'browse', "orderBy={name}_{sortType}&type={$type}"))
);