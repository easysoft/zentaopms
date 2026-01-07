<?php
declare(strict_types=1);
/**
 * The license view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$cmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $lang->upgrade->createWinFile : $lang->upgrade->createLinuxFile;

div
(
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh']),
    col
    (
        setClass('rounded-md bg-white gap-5 m-auto'),
        setStyle(['padding' => '1.5rem 2rem', 'width' => '50rem']),
        div
        (
            setClass('text-xl font-medium'),
            $lang->upgrade->setStatusFileTitle
        ),
        div
        (
            setClass('pre rounded-md bg-gray-100 px-8 py-6'),
            ul
            (
                li(html(sprintf($cmd, $statusFile))),
                li(html(sprintf($lang->upgrade->deleteStatusFile, $statusFile)))
            )
        ),
        checkbox
        (
            on::change('confirmStatusFile'),
            $lang->upgrade->confirmStatusFile
        ),
        div
        (
            setClass('center'),
            a
            (
                setID('confirm'),
                setClass('btn primary w-24 disabled'),
                $lang->upgrade->continue
            )
        )
    )
);

render('pagebase');
