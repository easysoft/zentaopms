<?php
declare(strict_types=1);
/**
 * The consistency view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$alterSQL = "SET @@sql_mode= '';\n{$alterSQL}";
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
            set::title($lang->upgrade->consistency),
            div
            (
                setClass('border p-4 mb-4'),
                set::style(array('background-color' => 'var(--color-gray-100)')),
                div
                (
                    setClass('article-h3 mb-2'),
                    $lang->upgrade->noticeSQL
                ),
                h::textarea
                (
                    set::style(array('height' => '200px', 'width' => '100%')),
                    set::readonly(true),
                    html($alterSQL)
                )
            ),
            div
            (
                setClass('text-center'),
                btn
                (
                    on::click('loadCurrentPage'),
                    set::type('primary'),
                    setClass('px-10'),
                    $lang->refresh
                )
            )
        )
    )
);

render('pagebase');
