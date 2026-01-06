<?php
declare(strict_types=1);
/**
 * The backup view file of upgrade module of ZenTaoPMS.
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
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('rounded-md bg-white gap-5 m-auto'),
        setStyle(['padding' => '1.5rem 2rem', 'width' => '50rem']),
        row
        (
            setClass('items-center gap-4'),
            icon
            (
                setClass('text-2xl text-warning'),
                'exclamation-sign'
            ),
            div
            (
                setClass('text-xl font-medium'),
                $lang->upgrade->notice
            )
        ),
        col
        (
            setClass('rounded-md gap-2.5 bg-gray-100 p-4'),
            html($lang->upgrade->noticeContent)
        ),
        div
        (
            setClass('center'),
            a
            (
                setID('upgrade'),
                setClass('btn primary disabled w-24'),
                set::href(inlink('consistency')),
                $lang->upgrade->common
            )
        )
    )
);

render('pagebase');
