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
    set::id('main'),
    div
    (
        set::id('mainContent'),
        set::class('bg-white p-4'),
        set::style(array('margin' => '50px auto 0', 'width' => '800px')),
        div
        (
            set::class('article-h1 mb-4'),
            $lang->upgrade->result
        ),
        in_array($result, array('fail', 'sqlFail')) ? div
        (
            set::class('alert danger-pale mb-2 article-h2'),
            $lang->upgrade->fail
        ) : null,
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
            set::class('mt-4'),
            $result == 'sqlFail' ? $lang->upgrade->afterExec : $lang->upgrade->afterDeleted,
            btn
            (
                on::click('loadCurrentPage'),
                $lang->refresh
            )
        ) : null
    )
);

render('pagebase');
