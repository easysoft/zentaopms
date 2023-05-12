<?php
declare(strict_types=1);
/**
 * The browse view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     project
 * @link        http://www.zentao.net
 */

namespace zin;

/* zin: Define the feature bar on main menu. */
featureBar
(
    set::current($browseType),
    item(set(array
    (
        'type' => "checkbox",
        'text' => $lang->project->edit
    ))),
    item(set(array
    (
        'type' => "checkbox",
        'text' => $lang->project->mine
    ))),
    li(searchToggle())
);

/* zin: Define the toolbar on main menu. */
toolbar
(
    item(set(array
    (
        'type'  => "btnGroup",
        'items' => array(array
        (
            'icon'  => "list",
            'text'  => "",
            'class' => "btn-icon primary"
        ), array
        (
            'icon'  => "cards-view",
            'text'  => "",
            'class' => "btn-icon"
        ))
    ))),
    item(set(array
    (
        'icon'  => 'export',
        'text'  => $lang->project->export,
        'class' => "ghost export"
    ))),
    item(set(array
    (
        'icon'  => 'plus',
        'text'  => $lang->project->create,
        'class' => "primary create-project-btn",
        'href'  => $this->createLink('project', 'createGuide')
    )))
);

/* zin: Define the sidebar in main content. */
sidebar
(
    moduleMenu()
);

/* zin: Define the dtable in main content. */
jsVar('langSummary', $lang->project->summary);
dtable
(
    set::className('shadow rounded'),
    set::cols(array_values($config->project->dtable->fieldList)),
    set::data($projectStats),
    set::plugins(array("checkable")),
    set::checkable(true),
    set::footPager(usePager()),
    set::footer(jsRaw('function(){return window.footerGenerator.call(this);}'))
);

render();
