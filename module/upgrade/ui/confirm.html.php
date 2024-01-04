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
jsVar('writable', $writable);

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        formPanel
        (
            setClass('upgrade-confirm'),
            on::click('button[type=submit]', "submitConfirm"),
            set::width('800px'),
            set::target('_self'),
            set::title($lang->upgrade->confirm),
            div
            (
                setClass('border p-4 mb-4'),
                set::style(array('background-color' => 'var(--color-gray-100)', 'max-height' => '430px', 'overflow' => 'hidden', 'overflow-y' => 'auto')),
                div(html(nl2br($confirm)))
            ),
            input
            (
                set::type('hidden'),
                set::name('fromVersion'),
                set::value($fromVersion)
            ),
            set::actions(array('submit', 'upgradingTips' => array('text' => $lang->upgrade->upgradingTips, 'class' => 'text-danger ghost hidden', 'id' => 'upgradingTips'))),
            set::submitBtnText($lang->upgrade->sureExecute)
        )
    )
);

modal
(
    setID('progress'),
    set::title('1%'),
    div
    (
        setClass('progress'),
        div
        (
            setClass('progress-bar'),
            set('role', 'progressbar'),
            set('style', '"width: 1%')
        )
    ),
    div
    (
        setID('logBox')
    )
);

render('pagebase');
