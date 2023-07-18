<?php
declare(strict_types=1);
/**
* The projectdoc block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 获取区块左侧的项目列表。
 * Get project tabs on the left side.
 *
 * @param  array  $projects
 * @param  string $blockNavCode
 * @param  bool   $longBlock
 * @return array
 */
$getProjectTabs = function(array $projects, string $blockNavCode, bool $longBlock): array
{
    $navTabs  = array();
    $selected = key($projects);
    $navTabs[] = li
    (
        set('class', 'nav-item overflow-hidden nav-prev rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-left'))
    );
    foreach($projects as $project)
    {
        $navTabs[] = li
        (
            set('class', 'nav-item nav-switch w-full'),
            a
            (
                set('class', 'ellipsis text-dark title ' . ($longBlock && $project->id == $selected ? ' active' : '')),
                $longBlock ? set('data-toggle', 'tab') : null,
                set('data-name', "tab3{$blockNavCode}Content{$project->id}"),
                set('href', $longBlock ? "#tab3{$blockNavCode}Content{$project->id}" : helper::createLink('project', 'browse', "projectID=$project->id")),
                $project->name

            ),
            !$longBlock ? a
            (
                set('class', 'hidden' . ($project->id == $selected ? ' active' : '')),
                set('data-toggle', 'tab'),
                set('data-name', "tab3{$blockNavCode}Content{$project->id}"),
                set('href', "#tab3{$blockNavCode}Content{$project->id}"),
            ) : null,
            a
            (
                set('class', 'link flex-1 text-right hidden'),
                set('href', helper::createLink('project', 'browse', "projectID=$project->id")),
                icon
                (
                    set('class', 'rotate-90 text-primary'),
                    setStyle(array('--tw-rotate' => '270deg')),
                    'import'
                )
            )
        );
    }
    $navTabs[] = li
    (
        set('class', 'nav-item overflow-hidden nav-next rounded-full bg-white shadow-md h-6 w-6'),
        a(icon(set('size', '24'), 'angle-right'))
    );
    return $navTabs;
};

/**
 * 获取区块右侧显示的项目文档列表。
 * Get project doc list.
 *
 * @param  array  $projects
 * @param  array  $users
 * @param  array  $docGroup
 * @param  string $blockNavID
 * @param  bool   $longBlock
 * @return array
 */
$getProjectInfo = function(array $projects, array $users, array $docGroup, string $blockNavID, bool $longBlock): array
{
    global $lang, $config;
    $tabItems = array();
    $selected = key($projects);
    foreach($projects as $project)
    {
        $tabItems[] = div
        (
            set('class', 'tab-pane h-full' . ($project->id == $selected ? ' active' : '')),
            set('id', "tab3{$blockNavID}Content{$project->id}"),
            dtable
            (
                set::height(318),
                set::bordered(false),
                set::horzScrollbarPos('inside'),
                set::cols(array_values($config->block->doc->dtable->fieldList)),
                set::data(array_values($docGroup[$project->id])),
                set::userMap($users),
            )
        );
    }
    return $tabItems;
};

$blockNavCode = 'nav-' . uniqid();
panel
(
    set('id', "projectdoc-block-{$block->id}"),
    on::click('.nav-prev,.nav-next', 'switchNav'),
    set('class', 'projectdoc-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set('headingClass', 'border-b'),
    to::heading
    (
        div
        (
            set('class', 'panel-title'),
            span(span($block->title)),
            dropdown
            (
                a
                (
                    setClass('text-gray ml-4'),
                    $lang->project->involved,
                    span(setClass('caret align-middle ml-1'))
                ),
                set::items(array(array('text' => $lang->project->involved), array('text' => $lang->project->all)))
            ),
        )
    ),
    to::headingActions
    (
        a
        (
            set('class', 'text-gray'),
            set('href', createLink('doc', 'projectspace')),
            $lang->more,
            icon('caret-right')
        )
    ),
    div
    (
        set('class', "flex h-full overflow-hidden " . ($longBlock ? '' : 'col')),
        cell
        (
            $longBlock ? set('width', '22%') : null,
            set('class', $longBlock ? 'bg-secondary-pale overflow-y-auto overflow-x-hidden' : ''),
            ul
            (
                set('class', 'nav nav-tabs ' .  ($longBlock ? 'nav-stacked' : 'pt-4 px-4')),
                $getProjectTabs($projects, $blockNavCode, $longBlock)
            ),
        ),
        cell
        (
            set('class', 'tab-content'),
            set('width', '78%'),
            $getProjectInfo($projects, $users, $docGroup, $blockNavCode, $longBlock)
        )
    )
);
render();
