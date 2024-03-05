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
            on::change('changeShowClosed'),
            set::checked($this->cookie->showClosed),
            $lang->kanban->showClosed
        )
    )
);

toolbar
(
    !empty($unclosedSpace) && $browseType != 'involved' ? item(set::icon('plus'), set::text($lang->kanban->create), set::className('secondary'), set::url(createLink('kanban', 'create', "spaceID=0&type={$browseType}")), set('data-toggle', 'modal'), set('data-size', 'lg')) : null,
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
    $childActions[] = array('icon' => 'trash', 'url' => createLink('kanban', 'deleteSpace', "spaceID={$space->id}"), 'text' => $lang->kanban->deleteSpace, 'innterClass' => 'ajax-btn', 'data-confirm' => $lang->kanban->confirmDeleteSpace);

    $headingActions = array();
    if($space->status != 'closed' and $browseType != 'involved') $headingActions[] = array('type' => 'ghost', 'icon' => 'plus', 'url' => createLink('kanban', 'create', "spaceID={$space->id}&type={$space->type}"), 'text' => $lang->kanban->create, 'data-toggle' => 'modal', 'data-size' => 'lg');
    $headingActions[] = array('type' => 'dropdown', 'btnType' => 'dropdown', 'icon' => 'cog-outline', 'text' => $lang->kanban->setting, 'caret' => true, 'items' => $childActions);

    $kanbans = array();
    if(!empty($space->kanbans))
    {
        foreach($space->kanbans as $kanbanID => $kanban)
        {
            $kanbanDescTitle = str_replace("\n", '', strip_tags($kanban->desc));
            $kanbanDesc      = str_replace("\n", '', preg_replace($pattern, '', $kanban->desc));

            $teamPairs   = array_filter(explode(',', ",$kanban->createdBy,$kanban->owner,$kanban->team"));
            $teamPairs   = array_unique($teamPairs);

            $count       = 0;
            $userAvatars = array();
            $teamCount   = count($teamPairs);
            foreach($teamPairs as $member)
            {
                if($count > 3) break;
                if(empty($users[$member])) continue;

                $userAvatars[] = userAvatar(set::account($users[$member]), set::avatar($usersAvatar[$member]), set::realname($users[$member]), set::size('sm'));
                $count ++;
            }

            $cardActions = array();
            $canEdit     = common::hasPriv('kanban','edit');
            $canDelete   = common::hasPriv('kanban','delete');
            $canClose    = (common::hasPriv('kanban', 'close') and $kanban->status == 'active');
            $canActivate = (common::hasPriv('kanban', 'activate') and $kanban->status == 'closed');
            if($canEdit)     $cardActions[] = array('icon' => 'edit',  'text' => $lang->kanban->edit,     'url' => createLink('kanban', 'edit',     "kanbanID={$kanban->id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
            if($canClose)    $cardActions[] = array('icon' => 'off',   'text' => $lang->kanban->close,    'url' => createLink('kanban', 'close',    "kanbanID={$kanban->id}"), 'data-toggle' => 'modal');
            if($canActivate) $cardActions[] = array('icon' => 'magic', 'text' => $lang->kanban->activate, 'url' => createLink('kanban', 'activate', "kanbanID={$kanban->id}"), 'data-toggle' => 'modal');
            if($canDelete)   $cardActions[] = array('icon' => 'trash', 'text' => $lang->kanban->delete,   'url' => createLink('kanban', 'delete',   "kanbanID={$kanban->id}"), 'innterClass' => 'ajax-btn', 'data-confirm' => $lang->kanban->confirmDeleteKanban);

            $teamCountLang = ($teamCount > 1) ? $lang->kanban->teamSumCount : str_replace("Pers", "Person", $lang->kanban->teamSumCount);
            $cardsCount    = ($kanban->cardsCount > 1) ? str_replace("Card", "Cards", $lang->kanban->cardsCount) : $lang->kanban->cardsCount;

            $kanbans[] = cell
            (
                set::width('1/4'),
                set::className('px-2 pb-4 overflow-hidden'),
                div
                (
                    set::className('kanban-card border px-4 pb-2 pt-1 open-url'),
                    set('data-url', createLink('kanban', 'view', "kanbanID=$kanbanID")),
                    div
                    (
                        set::className('flex items-center'),
                        cell(set::className('ellipsis font-bold mr-1'), set::title($kanban->name), $kanban->name),
                        $kanban->status == 'closed' ? cell(set::className('label gray mx-1'), setStyle(array('min-width' => '44px')), $lang->kanban->closed) : null,
                        $space->type == 'cooperation' && $kanban->owner == $this->app->user->account ? cell(set::className('label text-important ring-important mx-1'), setStyle(array('min-width' => '44px')), $lang->kanban->mine) : null,
                        $cardActions ? cell
                        (
                            set::className('flex-1 text-right'),
                            dropdown
                            (
                                btn(setClass('btn dropdown-toggle ghost'), set::icon('ellipsis-v'), set::caret(false)),
                                set::items($cardActions)
                            )
                        ) : null
                    ),
                    div(set::className('h-16 mb-2 overflow-hidden'), set::title($kanbanDescTitle), html($kanbanDesc)),
                    div
                    (
                        set::className('flex items-center'),
                        cell(set::className('flex items-center mr-2'), $userAvatars),
                        cell(sprintf($teamCountLang, $teamCount)),
                        cell(set::className('flex-1 text-right'), empty($kanban->cardsCount) ? $lang->kanban->noCard : sprintf($cardsCount, $kanban->cardsCount))
                    )
                )
            );
        }
    }

    $blocks[] = blockPanel
    (
        set::className('mb-4'),
        set::title($space->name),
        set::titleIcon('cube'),
        $space->status == 'closed' ? to::titleSuffix(span(set::className('label gray'), $lang->kanban->closed)) : null,
        set::headingActions($headingActions),
        div(set::className('p-2'), html($spaceDesc)),
        !empty($space->kanbans) ? div(set::className('flex flex-wrap'), $kanbans) : div(set::className('dtable'), div(set::className('dtable-empty-tip'), span(set::className('text-gray'),  $lang->kanban->empty)))
    );
}

!empty($spaceList) ? $blocks : div(set::className('dtable'), div(set::className('dtable-empty-tip'), span(set::className('text-gray'),  $lang->kanbanspace->empty)));
if(!empty($spaceList)) div(set::className('table-footer'), pager(set(usePager()), set::className('pull-right')));

render();
