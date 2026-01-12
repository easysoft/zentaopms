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

jsVar('version', (string)$version);
jsVar('execFixSQL', !empty($alterSQL) && !$hasError);

div
(
    setStyle(['padding' => '3rem 4rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('container rounded-md bg-white gap-5'),
        setStyle(['padding' => '1.5rem 2rem', 'max-height' => '100%']),
        div
        (
            setClass('text-xl font-medium'),
            $lang->upgrade->consistency,
        ),
        div
        (
            $hasError ? $lang->upgrade->noticeErrSQL : $lang->upgrade->showSQLLog,
            $hasError ? null : span(setID('progressBox'))
        ),
        div
        (
            setID('logBox'),
            setClass('pre rounded-md bg-gray-100 overflow-x-hidden overflow-y-auto px-8 py-6'),
            setStyle(['max-height' => 'calc(100% - 5rem)']),
            $hasError ? html($alterSQL . ';') : null
        ),
        div
        (
            setClass('text-center'),
            btn
            (
                setID('continueBtn'),
                setClass('px-10'),
                set::type('primary disabled'),
                $hasError ? $lang->refresh : $lang->upgrade->continue
            )
        )
    )
);

render('pagebase');
