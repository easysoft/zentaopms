<?php
declare(strict_types=1);
/**
 * The confirm view file of upgrade module of ZenTaoPMS.
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
            set::class('article-h1 mb-4'),
            $lang->upgrade->confirm
        ),
        form
        (
            set::url(inlink('execute')),
            set::target('_self'),
            on::submit('submit.disabled=1'),
            div
            (
                set::class('border p-4 mb-4'),
                set::style(array('background-color' => 'var(--color-gray-100)')),
                div
                (
                    html(nl2br($confirm)),
                )
            ),
            input
            (
                set::type('hidden'),
                set::name('fromVersion'),
                set::value($fromVersion),
            ),
            set::actions(array('submit')),
            set::submitBtnText($lang->upgrade->sureExecute),
            div
            (
                set::id('upgradeingTips'),
                set::class('text-danger hidden mt-2'),
                $lang->upgrade->upgradingTips
            )
        )
    )
);

render('pagebase');

