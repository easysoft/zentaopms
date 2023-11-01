<?php
declare(strict_types=1);
/**
 * The space view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}"),
    li
    (
        set::className('nav-item ml-2'),
        checkbox
        (
            setID('showClosed'),
            on::change('toggleOnlyAutoCase'),
            set::checked($this->cookie->showClosed),
            $lang->kanban->showClosed
        )
    )
);

toolbar
(
    !empty($unclosedSpace) && $browseType != 'involved' ? item(set::icon('plus'), set::text($lang->kanban->create), set::className('secondary'), set::url(createLink('kanban', 'create', "spaceID=0&type={$browseType}")), set('data-toggle', 'modal')) : null,
    $browseType != 'involved' ? item(set::icon('plus'), set::text($lang->kanban->createSpace), set::className('primary'), set::url(createLink('kanban', 'createSpace', "type={$browseType}")), set('data-toggle', 'modal')) : null
);

$blocks = array();
foreach($spaceList as $space)
{
    $spaceDescTitle = empty($space->desc) ? $lang->kanban->emptyDesc : str_replace("\n", '', strip_tags($space->desc));
    $pattern        = '/<br[^>]*>|<img[^>]*>/';
    $spaceDesc      = empty($space->desc) ? $lang->kanban->emptyDesc : preg_replace($pattern, '', $space->desc);

    $childActions = array();
    $childActions[] = array('icon' => 'cog-outline', 'url' => createLink('kanban', 'editSpace', "spaceID={$space->id}"), 'text' => $lang->kanban->settingSpace, 'data-toggle' => 'modal');
    if($space->status != 'closed') $childActions[] = array('icon' => 'off',   'url' => createLink('kanban', 'closeSpace',    "spaceID={$space->id}"), 'text' => $lang->kanban->closeSpace,    'data-toggle' => 'modal');
    if($space->status == 'closed') $childActions[] = array('icon' => 'magic', 'url' => createLink('kanban', 'activateSpace', "spaceID={$space->id}"), 'text' => $lang->kanban->activateSpace, 'data-toggle' => 'modal');
    $childActions[] = array('icon' => 'trash', 'url' => createLink('kanban', 'editSpace', "spaceID={$space->id}"), 'text' => $lang->kanban->deleteSpace, 'data-toggle' => 'modal');

    $headingActions = array();
    if($space->status != 'closed' and $browseType != 'involved') $headingActions[] = array('type' => 'ghost', 'icon' => 'plus', 'url' => createLink('kanban', 'create', "spaceID={$space->id}&type={$space->type}"), 'text' => $lang->kanban->create, 'data-toggle' => 'modal', 'data-size' => 'lg');
    $headingActions[] = array('type' => 'dropdown', 'btnType' => 'dropdown', 'icon' => 'cog-outline', 'text' => $lang->kanban->setting, 'caret' => true, 'items' => $childActions);

    $blocks[] = blockPanel
    (
        set::className('mb-4'),
        set::title($space->name),
        set::titleIcon('cube'),
        set::headingActions($headingActions),
        div(html($spaceDesc)),
        !empty($space->kanbans) ? div('123') : div(set::className('dtable'), div(set::className('dtable-empty-tip'), span(set::className('text-gray'),  $lang->kanban->empty)))
    );
}

!empty($spaceList) ? $blocks : div(set::className('dtable'), div(set::className('dtable-empty-tip'), span(set::className('text-gray'),  $lang->kanbanspace->empty)));
div(set::className('table-footer'), pager(set::className('pull-right')));

render();
