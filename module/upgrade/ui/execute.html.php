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
            form
            (
                on::click('button[type=submit]', "submitConfirm"),
                on::click('button[type=button]', "loadCurrentPage"),
                set::target('_self'),
                set::actions(false),
                formHidden('fromVersion', $fromVersion),
                div
                (
                    setClass('mt-4'),
                    $result == 'sqlFail' ? $lang->upgrade->afterExec : $lang->upgrade->afterDeleted,
                    btn
                    (
                        set::btnType($this->app->rawMethod == 'execute' ? 'submit' : 'button'),
                        $lang->refresh
                    )
                )
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
