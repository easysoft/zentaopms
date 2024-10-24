<?php
declare(strict_types=1);
/**
 * The project view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
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
        setID('closedProjectForm'),
        set::actions(array('submit')),
        set::actionsClass('w-2/3'),
        setClass('flex-auto'),
        span
        (
            setClass('text-md font-bold'),
            $lang->custom->closeSetting
        ),
        formGroup
        (
            set::width('2/3'),
            setClass('closed-project-box'),
            set::label($lang->custom->closedProject),
            radioList
            (
                set::name('project'),
                set::items($lang->custom->CRProject),
                set::value(isset($config->CRProject) ? $config->CRProject : 0),
                set::inline(true)
            )
        ),
        formGroup
        (
            set::label(''),
            span
            (
                setClass('row'),
                icon('info text-warning mr-1 mt-0.5'),
                span(html($lang->custom->notice->readOnlyOfProject))
            )
        )
    )
);

render();
