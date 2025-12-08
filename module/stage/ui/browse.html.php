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

featureBar();

$canCreateStage      = hasPriv('stage', 'create');
$canbatchCreateStage = hasPriv('stage', 'batchCreate');
if($canCreateStage) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->stage->create, 'url' => $this->createLink('stage', 'create', "groupID={$groupID}"), 'data-toggle' => 'modal');
if($canbatchCreateStage) $batchCreateItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->stage->batchCreate, 'url' => $this->createLink('stage', 'batchCreate', "groupID={$groupID}"));
toolbar
(
    !empty($batchCreateItem) ? item(set($batchCreateItem)) : null,
    !empty($createItem) ? item(set($createItem)) : null
);

if($this->config->edition == 'open')
{
    if(hasPriv('stage', 'settype'))
    {
        $menuItems[] = li
        (
            setClass('menu-item'),
            a
            (
                set::href(createLink('stage', 'settype')),
                $lang->stage->setTypeAB
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
            $lang->stage->browseAB
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
}

$tableData = initTableData($stages, $config->stage->dtable->fieldList, $this->stage);
dtable
(
    set::cols($config->stage->dtable->fieldList),
    set::data($tableData),
    set::orderBy($orderBy),
    set::sortLink(createLink('stage', 'browse', "groupID={$groupID}&orderBy={name}_{sortType}")),
    set::plugins(array('sortable')),
    set::sortHandler('.move-stage'),
    set::onSortEnd(jsRaw('window.onSortEnd')),
    set::onRenderCell(jsRaw('window.renderCell'))
);
