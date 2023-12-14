<?php
declare(strict_types=1);
/**
 * The changelog view file of misc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
namespace zin;

setID('changelogModal');
setStyle('min-height', '240px');

$versionItems = array();
foreach(array_keys($lang->misc->feature->all) as $versionName)
{
    $url  = createLink('misc', 'changeLog', "version=$versionName");
    $text = $versionName == 'latest' ? $lang->misc->feature->latest : $lang->misc->releaseDate[$versionName] . ' ' . $versionName;
    $versionItems[] = array('text' => $text, 'url' => $url, 'data-load' => 'modal', 'data-target' => 'changelogModal');
}

modalHeader
(
    set::title($lang->changeLog),
    set::titleClass('font-bold'),
    to::suffix
    (
        dropdown
        (
            a
            (
                set('href', 'javascript:;'),
                setClass('text-gray ml-4'),
                $version === 'latest' ? $lang->misc->feature->latest : ($lang->misc->releaseDate[$version] . ' ' . $version),
                span(setClass('caret align-middle ml-1'))
            ),
            set::placement('bottom-end'),
            set::menu(array('style' => array('max-height' => '180px', 'overflow-y' => 'auto'))),
            set::items($versionItems)
        )
    )
);

$idx = count($features) == 1 ? '' : 1;
$featureItems = array();
foreach($features as $feature)
{
    $featureItems[] = div
    (
        set('class', 'mb-2'),
        div
        (
            set('class', 'text-md font-bold py-2'),
            $idx . ($idx ? '. ' : '') . $feature['title']
        ),
        div
        (
            set('class', 'desc pl-4'),
            html($feature['desc'])
        )
    );

    $idx ++;
}

div
(
    setID('featureList'),
    setClass('px-3 py-2'),
    $featureItems
);

($detailed and !common::checkNotCN()) ? div
(
    setID('details'),
    setClass('px-3 py-2'),
    a
    (
        on::click('toggleDetails'),
        set('class', 'btn lighter text-primary w-full shadow-none justify-start'),
        set('href', '###'),
        icon('angle-right'),
        $lang->misc->feature->detailed
    ),
    div
    (
        set('class', 'lighter p-2 details-list hidden'),
        html($detailed)
    )
) : null;

render('modalDialog');
