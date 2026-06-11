<?php
declare(strict_types=1);
/**
 * The browse view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('browseType', $browseType);
jsVar('param', $param);
jsVar('recTotal', $pager->recTotal);
jsVar('recPerPage', $pager->recPerPage);
jsVar('pageID', $pager->pageID);
jsVar('confirmDeleteTip', $lang->project->confirmDelete);

unset($programs[0]);
featureBar
(
    $projectType == 'bycard' && helper::hasFeature('program') ? to::before
    (
        div
        (
            picker
            (
                set::name('programID'),
                set::value($programID),
                set::items($programs),
                set::width('200px'),
                set::placeholder($lang->project->selectProgram),
                on::change('changeProgram')
            )
        )
    ) : null,
    set::current($browseType),
    set::linkParams("programID={$programID}&status={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    checkbox
    (
        set::rootClass($projectType == 'bycard' ? 'mx-2' : 'ml-2 mr-4'),
        set::name('involved'),
        set::text($lang->project->mine),
        set::checked($this->cookie->involved ? 'checked' : ''),
        on::change()->call('handleChangeInvolved', jsRaw('event'))
    ),
    li(searchToggle(set::module('project'), set::open($browseType == 'bysearch')))
);

toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(array
        (
            'icon'      => 'list',
            'class'     => $projectType == 'bycard' ? 'switchButton btn-icon' : 'btn-icon switchButton text-primary',
            'data-type' => 'bylist',
            'hint'      => $lang->project->bylist
        ), array
        (
            'icon'      => 'cards-view',
            'class'     => $projectType == 'bycard' ? 'btn-icon switchButton text-primary' : 'switchButton btn-icon',
            'data-type' => 'bycard',
            'hint'      => $lang->project->bycard
        ))
    ))),
    hasPriv('project', 'export') ? item(set(array
    (
        'icon'        => 'export',
        'text'        => $lang->project->export,
        'class'       => 'ghost export',
        'url'         => createLink('project', 'export', "status={$browseType}&orderBy={$orderBy}"),
        'data-toggle' => 'modal'
    ))) : null,
    hasPriv('project', 'create') ? item(set(array_merge(array
    (
        'icon'          => 'plus',
        'text'          => $lang->project->create,
        'data-toggle'   => 'modal',
        'data-position' => 'center'
    ), array
    (
        'class' => 'primary create-project-btn',
        'url'   => createLink('project', 'createGuide', "programID={$programID}")
    )))) : null,
    $projectType == 'bylist' ? on::click('.export')->call('handleClickExportBtn', jsRaw('event')) : null,
    on::click('.switchButton')->call('handleClickSwitchButton', jsRaw('event'))
);

if($projectType == 'bycard')
{
    include 'browsebycard.html.php';
}
else
{
    include 'browsebylist.html.php';
}
