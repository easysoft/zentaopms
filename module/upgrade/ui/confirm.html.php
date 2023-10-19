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
        formPanel
        (
            set::width('800px'),
            set::target('_self'),
            set::url(inlink('execute', "fromVersion={$fromVersion}")),
            on::submit('submit.disabled=1'),
            set::title($lang->upgrade->confirm),
            div
            (
                set::className('border p-4 mb-4'),
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
            set::actions(array('submit', 'upgradingTips' => array('text' => $lang->upgrade->upgradingTips, 'class' => 'text-danger ghost hidden', 'id' => 'upgradingTips'))),
            set::submitBtnText($lang->upgrade->sureExecute),
        )
    )
);

render('pagebase');
