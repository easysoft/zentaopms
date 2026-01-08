<?php
declare(strict_types=1);
/**
 * The delete files view file of upgrade module of ZenTaoPMS.
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

jsVar('copySuccess', $lang->upgrade->copySuccess);
jsVar('copyFail', $lang->upgrade->copyFail);

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
        div
        (
            setID('command'),
            setClass('pre-wrap break-all break-words rounded-md bg-gray-100 px-8 py-6'),
            setStyle('height', 'calc(100% - 8rem)'),
            $command
        ),
        div
        (
            setClass('text-warning'),
            $lang->upgrade->execCommand
        ),
        row
        (
            setClass('justify-center gap-4'),
            a
            (
                setClass('btn success w-24'),
                set::href('javascript:copyCommand("#command");'),
                $lang->upgrade->copyCommand
            ),
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
