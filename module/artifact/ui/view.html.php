<?php
declare(strict_types=1);
/**
 * The view file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      OpenAI
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;
if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
    unset($lang->artifact->featureBar);
}

$breadCrumbsBox = array();
if(!empty($breadCrumbs))
{
    foreach($breadCrumbs as $pathName => $pathItems)
    {
        $breadCrumbsBox[] = span('>', setStyle('margin', '0 5px'));
        $breadCrumbsBox[] = picker
        (
            setClass('picker-btn state'),
            setStyle('box-shadow', 'none'),
            set::items($pathItems),
            set::search(false),
            set::required(true),
            set::menu(jsRaw('{searchBox: true, search: undefined}')),
            set::display(jsRaw("(value, selections) => {
            return {html: `<div>\${selections.map(x => x.text).join(',')}</div><style>.picker-btn .caret{display:none}</style><button type='button' class='picker-btn-trigger btn size-xs square text-primary'><i class='icon icon-exchange'></i></button>`, className: 'flex justify-between gap-2 p-px'};}")),
            set::value(helper::safe64Encode('/' . $pathName))
        );
    }
}

div
(
    setClass('surface-light row items-center border py-1.5 pl-1 pr-2'),
    btn
    (
        setClass('ghost text-primary square size-md'),
        set::title('home'),
        set::icon('home'),
        set::url($browseLink)
    ),
    span('>', setStyle('margin', '0 5px')),
    picker
    (
        setClass('picker-btn state'),
        setStyle('box-shadow', 'none'),
        set::items($artifactList),
        set::search(false),
        set::required(true),
        set::menu(jsRaw('{searchBox: true, search: undefined}')),
        set::display(jsRaw("(value, selections) => {
        return {html: `<div>{$lang->artifact->common}: \${selections.map(x => x.text).join(',')}</div><style>.picker-btn .caret{display:none}</style><button type='button' class='picker-btn-trigger btn size-xs square text-primary'><i class='icon icon-exchange'></i></button>`, className: 'flex justify-between gap-2 p-px'};}")),
        set::value($artifact->id)
    ),
    empty($breadCrumbsBox) ? null : $breadCrumbsBox
);

div
(
    setClass('flex min-h-0 flex-1 row items-stretch'),
    sidebar
    (
        set::side('left'),
        setClass('repo-sidebar canvas h-full min-h-0'),
        setStyle('height', 'calc(100vh - 120px)'),
        set::width(300),
        set::preserve(false),
        div
        (
            setClass('p-2 h-full min-h-0'),
            tree
            (
                setClass('filesTree'),
                set::items($treeItems),
                set::collapsedIcon('folder text-warning'),
                set::expandedIcon('folder-open text-warning'),
                set::normalIcon('stack'),
                set::preserve(false),
                set::hover(true),
                set::defaultNestedShow($selectNode),
            ),
            div
            (
                setID('artifactViewToolbar'),
                setClass('mt-auto shrink-0 flex justify-end bottom-0 gap-2 px-3 py-2'),
                setStyle('position', 'absolute'),
                setStyle('right', '0'),
                //btn
                //(
                //    setID('artifactViewToggleAll'),
                //    setClass('btn ghost size-sm'),
                //    set::title($lang->artifact->expandAll),
                //    set::icon('icon-list-collapse'),
                //    on::click()->call('window.isExpand', jsRaw('$this'))
                //),
                dropdown
                (
                    set::placement('top-end'),
                    set::staticMenu(true),
                    set::hasIcons(true),
                    set::items
                    (
                        array
                        (
                            array
                            (
                                'text'        => $lang->artifact->addDirectory,
                                'icon'        => 'plus',
                                'data-toggle' => 'modal',
                                'url'         => helper::createLink('artifact', 'createDir', "artifactID={$artifact->id}&path=&isSubDir=0&spaceID={$spaceID}&repoID={$repoID}&type={$type}"),
                            )
                        )
                    ),
                    btn
                    (
                        setID('artifactViewSettingsToggle'),
                        setClass('ghost size-sm'),
                        set::title($lang->artifact->settings),
                        set::icon('cog-outline')
                    )
                )
            )
        )
    ),
    panel
    (
        setID('artifactViewPage'),
        setClass('h-full w-full min-h-0 flex ml-2'),
        setStyle('height', 'calc(100vh - 120px)'),
        div
        (
            setID('artifactViewList'),
        )
    )
);
