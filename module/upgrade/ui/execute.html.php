<?php
declare(strict_types=1);
/**
 * The execute view file of upgrade module of ZenTaoPMS.
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
    setID('main'),
    div
    (
        setID('mainContent'),
        panel
        (
            set::style(array('margin' => '0 auto')),
            zui::width('800px'),
            div
            (
                setClass('text-lg font-bold mb-4'),
                icon
                (
                    'close',
                    setStyle('font-size', '16px'),
                    setClass('danger circle p-1.5 mr-2')
                ),
                in_array($result, array('fail', 'sqlFail')) ?  $lang->upgrade->fail : $lang->upgrade->result
            ),
            in_array($result, array('fail', 'sqlFail')) ? div
            (
                textarea
                (
                    set::name('errors'),
                    set::rows(10),
                    set('readonly', 'readonly'),
                    implode("\n", $errors)
                )
            ) : null,
            in_array($result, array('fail', 'sqlFail')) ? div
            (
                setClass('mt-4'),
                $result == 'sqlFail' ? $lang->upgrade->afterExec : $lang->upgrade->afterDeleted,
                btn
                (
                    on::click('window.reloadPage(this)'),
                    $lang->refresh
                )
            ) : formPanel
            (
                on::click('button[type=submit]', "submitConfirm"),
                set::width('800px'),
                input
                (
                    set::type('hidden'),
                    set::name('fromVersion'),
                    set::value($fromVersion)
                ),
            )
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
