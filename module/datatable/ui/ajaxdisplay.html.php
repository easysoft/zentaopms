<?php
declare(strict_types=1);
/**
 * The ajaxcustom view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhujinyong<zhujinyong@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

global $lang, $app;
$app->loadLang('datatable');
$showModule    = isset($config->$currentModule->$currentMethod->showModule) ? $config->$currentModule->$currentMethod->showModule : '0';
$showAllModule = isset($config->execution->task->allModule) ? $config->execution->task->allModule : 0;

set::title($lang->datatable->displaySetting);
form
(
    set::url(helper::createLink('datatable', 'ajaxSave')),
    set::labelWidth('12em'),
    input
    (
        set('class', 'hidden'),
        set::name('target'),
        set::value($datatableID)
    ),
    input
    (
        set('class', 'hidden'),
        set::name('name'),
        set::value('showModule')
    ),
    formGroup
    (
        set::label($lang->datatable->showModule),
        radiolist
        (
            set::name('value'),
            set::inline(true),
            set::items($lang->datatable->showModuleList),
            set::value($showModule)
        )
    ),
    $moduleName == 'execution' && $methodName == 'task' && $this->config->vision != 'lite' ? formGroup
    (
        set::label($lang->datatable->showAllModule),
        radiolist
        (
            set::name('allModule'),
            set::inline(true),
            set::items($lang->datatable->showAllModuleList),
            set::value($showAllModule)
        )
    ) : null,
    !empty($showBranch) ? formGroup
    (
        set::label($lang->datatable->showBranch),
        radiolist
        (
            set::name('showBranch'),
            set::inline(true),
            set::items($lang->datatable->showBranchList),
            set::value(isset($config->$currentModule->$currentMethod->showBranch) ? $config->$currentModule->$currentMethod->showBranch : 1)
        )
    ) : null,
    input
    (
        set('class', 'hidden'),
        set::name('currentModule'),
        set::value($currentModule)
    ),
    input
    (
        set('class', 'hidden'),
        set::name('currentMethod'),
        set::value($currentMethod)
    ),
    set::actions(array('submit'))
);

render();
