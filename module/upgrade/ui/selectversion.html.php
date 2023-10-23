<?php
declare(strict_types=1);
/**
 * The selectversion view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        formPanel
        (
            zui::width('800px'),
            set::target('_self'),
            set::title($lang->upgrade->selectVersion),
            formRow
            (
                formGroup
                (
                    set::label($lang->upgrade->fromVersion),
                    picker
                    ( 
                        set::name('fromVersion'),
                        set::items($lang->upgrade->fromVersions),
                        set::value($version)
                    )
                ),
                formGroup
                (
                    setClass('ml-3'),
                    div
                    (
                        setClass('h-8 flex text-danger items-center'),
                        $lang->upgrade->noteVersion
                    )
                )
            ),
            formGroup
            (
                set::label($lang->upgrade->toVersion),
                div
                (
                    setClass('h-8 flex items-center'),
                    ucfirst($config->version)
                )
            ),
            set::actions(array('submit')),
            set::submitBtnText($lang->upgrade->common)
        )
    )
);

render('pagebase');

