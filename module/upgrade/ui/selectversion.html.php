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
        set::class('bg-white p-4'),
        set::style(array('margin' => '50px auto 0', 'width' => '800px')),
        div
        (
            set::class('article-h1 mb-4 ml-5'),
            $lang->upgrade->selectVersion
        ),
        form
        (
            set::url(inlink('confirm')),
            set::target('_self'),
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
                    set::class('ml-3'),
                    div
                    (
                        set::class('h-8 flex text-danger items-center'),
                        $lang->upgrade->noteVersion
                    )
                )
            ),
            formGroup
            (
                set::label($lang->upgrade->toVersion),
                div
                (
                    set::class('h-8 flex items-center'),
                    ucfirst($config->version)
                )
            ),
            set::actions(array('submit')),
            set::submitBtnText($lang->upgrade->common)
        )
    )
);

render('pagebase');

