<?php
declare(strict_types=1);
/**
 * The sql fail view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）集团有限公司 (ZenTao Software (Qingdao) Co., Ltd. www.chandao.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     upgrade
 * @version     $Id$
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

div
(
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('container rounded-md bg-white gap-5'),
        setStyle(['padding' => '1.5rem 2rem', 'max-height' => '100%']),
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
                $lang->upgrade->fail
            )
        ),
        div
        (
            setClass('pre rounded-md bg-gray-100 overflow-x-hidden overflow-y-scroll px-8 py-6'),
            implode("\n", $errors)
        ),
        div
        (
            setClass('text-warning'),
            $lang->upgrade->afterExec
        ),
        div
        (
            setClass('center'),
            a
            (
                setClass('btn primary w-24'),
                set::href('javascript:loadCurrentPage()'),
                $lang->refresh
            )
        )
    )
);

render('pagebase');
