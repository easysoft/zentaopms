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
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('container rounded-md bg-white h-full gap-5'),
        setStyle(['padding' => '1.5rem 2rem']),
        div
        (
            setClass('text-xl font-medium'),
            $lang->upgrade->confirm
        ),
        div
        (
            setClass('pre rounded-md bg-gray-100 overflow-x-hidden overflow-y-scroll px-8 py-6 h-full'),
            setStyle('height', 'calc(100% - 5rem)'),
            $confirm
        ),
        div
        (
            setClass('center'),
            a
            (
                setClass('btn primary w-24'),
                set::href(inlink('execute', "fromVersion=$fromVersion")),
                $lang->upgrade->sureExecute
            )
        )
    )
);

render('pagebase');
