<?php
declare(strict_types=1);
/**
 * The selectversion view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$clientLang   = $app->getClientLang();
$labelWidth   = $clientLang === 'en' ? '4rem' : '6rem';
$versionWidth = $clientLang === 'en' ? 'w-1/2' : 'w-3/5';

div
(
    setStyle(['padding' => '3rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('rounded-md bg-white gap-5 m-auto'),
        setStyle(['padding' => '1.5rem 2rem', 'width' => '50rem']),
        div
        (
            setClass('text-xl font-medium'),
            $lang->upgrade->selectVersion
        ),
        form
        (
            set::target('_self'),
            formRow
            (
                setClass('gap-4'),
                formGroup
                (
                    setClass($versionWidth),
                    set::label($lang->upgrade->fromVersion),
                    set::labelWidth($labelWidth),
                    set::labelProps(['style' => ['justify-content' => 'flex-start']]),
                    picker
                    (
                        set::maxItemsCount(0),
                        set::name('fromVersion'),
                        set::required(true),
                        set::items($lang->upgrade->fromVersions),
                        set::value($version)
                    )
                ),
                formGroup
                (
                    setStyle(['align-items' => 'center']),
                    div
                    (
                        setClass('text-warning'),
                        $lang->upgrade->noteVersion
                    )
                )
            ),
            formGroup
            (
                setClass($versionWidth),
                set::label($lang->upgrade->toVersion),
                set::labelWidth($labelWidth),
                set::labelProps(['style' => ['justify-content' => 'flex-start']]),
                set::name('toVersion'),
                set::value(ucfirst($config->version)),
                set::readonly(true)
            ),
            set::actions(array('submit')),
            set::submitBtnText($lang->upgrade->common)
        )
    )
);

render('pagebase');

