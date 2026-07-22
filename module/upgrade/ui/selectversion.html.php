<?php
declare(strict_types=1);
/**
 * The selectversion view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$clientLang   = $app->getClientLang();
$labelWidth   = $clientLang === 'en' ? '8rem' : '6rem';
$versionWidth = $clientLang === 'en' ? 'w-1/2' : 'w-3/5';

$featureColBox = array();
foreach($lang->upgrade->upgradeFeatures as $group)
{
    $featureBox = array();
    foreach($group as $feature)
    {
        $featureBox[] = div
        (
            setClass('flex border rounded-lg bg-gray-100'),
            setStyle('height', '60px'),
            span(setClass('p-2'), icon("{$feature['icon']} icon-2x mt-1.5"), setStyle('position', 'relative')),
            div
            (
                div(setClass('font-medium font-bold text-md mt-2'), $feature['title']),
                div(setClass('text-gray text-sm mt-2'), $feature['desc'])
            )
        );
    }

    $featureColBox[] = col
    (
        setClass('w-1/2 gap-3'),
        $featureBox
    );
}

div
(
    setStyle(['padding' => '3rem', 'height' => '100vh', 'overflow' => 'hidden']),
    col
    (
        setClass('rounded-md bg-white gap-5 m-auto'),
        setStyle(['padding' => '1.5rem 2rem', 'width' => '60rem']),
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
        ),
        div
        (
            setClass('border-t mt-5 pt-5'),
            div
            (
                span(setClass('label-dot secondary mr-2 w-1')),
                setClass('text-lg font-medium mb-4 font-bold'),
                $lang->upgrade->upgradeFeatureDesc
            ),
            row
            (
                setClass('gap-x-8 gap-y-3'),
                $featureColBox
            )
        )
    )
);

render('pagebase');
