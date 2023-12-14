<?php
declare(strict_types=1);
/**
 * The execution view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;
include 'sidebar.html.php';

div
(
    setClass('flex'),
    $sidebarMenu,
    formPanel
    (
        setID('closedExecutionForm'),
        set::actions(array('submit')),
        set::actionsClass('w-2/3'),
        setClass('flex-auto ml-4'),
        span
        (
            setClass('text-md font-bold'),
            $lang->custom->$module->fields[$module]
        ),
        formGroup
        (
            set::width('2/3'),
            setClass('closed-execution-box'),
            set::label($lang->custom->closedExecution),
            radioList
            (
                set::name('execution'),
                set::items($lang->custom->CRExecution),
                set::value(isset($config->CRExecution) ? $config->CRExecution : 0),
                set::inline(true)
            )
        ),
        formGroup
        (
            set::label(''),
            span
            (
                icon('info text-warning mr-2'),
                $lang->custom->notice->readOnlyOfExecution
            )
        )
    )
);

/* ====== Render page ====== */
render();
