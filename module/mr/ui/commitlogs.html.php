<?php
declare(strict_types=1);
/**
 * The commitLogs file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$app->loadLang('productplan');
$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($MR->id),
            set::level(1),
            set::text($MR->title)
        ),
        $MR->deleted ? h::span
        (
            setClass('label danger'),
            $lang->product->deleted
        ) : null
    )
);

$dropMenus = array();
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['inline'], 'icon' => 'snap-house', 'id' => 'inline', 'class' => 'inline-appose');
$dropMenus[] = array('text' => $this->lang->repo->viewDiffList['appose'], 'icon' => 'col-archive', 'id' => 'appose', 'class' => 'inline-appose');

panel
(
    setClass('relative'),
    div
    (
        set::id('mrMenu'),
        nav
        (
            li
            (
                setClass('nav-item'),
                a
                (
                    $lang->mr->view,
                    set::href(inlink('view', "MRID={$MR->id}")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item'),
                a
                (
                    $lang->mr->commitLogs,
                    setClass('active'),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item'),
                a
                (
                    $lang->mr->viewDiff,
                    set::href(inlink('diff', "MRID={$MR->id}")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item story'),
                a
                (
                    icon($lang->icons['story']),
                    $lang->productplan->linkedStories,
                    set::href(inlink('link', "MRID={$MR->id}&type=story")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item bug'),
                a
                (
                    icon($lang->icons['bug']),
                    $lang->productplan->linkedBugs,
                    set::href(inlink('link', "MRID={$MR->id}&type=bug")),
                    set('data-app', $app->tab)
                )
            ),
            li
            (
                setClass('nav-item task'),
                a
                (
                    icon('todo'),
                    $lang->mr->linkedTasks,
                    set::href(inlink('link', "MRID={$MR->id}&type=task")),
                    set('data-app', $app->tab)
                )
            )
        )
    ),
    empty($commitLogs) ? p(setClass('detail-content'), in_array(strtolower($repo->SCM), array('gogs')) ? $lang->mr->unsupportedFeature : $lang->mr->noChanges) : div(
        dtable
        (
            set::cols($config->mr->commitLogs->dtable->fieldList),
            set::data($commitLogs),
            set::footPager(usePager())
        )
    )
);

render();
