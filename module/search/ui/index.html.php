<?php
declare(strict_types=1);
/**
 * The index view file of search module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     search
 * @link        https://www.zentao.net
 */
namespace zin;

to::header();

$labels = array();
foreach(array_slice($lang->search->modules, 0, 10) as $module => $moduleName)
{
    $labels[] = btn
    (
        setClass('light-pale mr-3' . ($type == $module ? ' primary' : '')),
        set(array('data-type' => $module)),
        on::click('searchWords'),
        $moduleName
    );
}

$moreItems = array();
foreach(array_slice($lang->search->modules, 10) as $module => $moduleName)
{
    $moreItems[$module] = array('text' => $moduleName, 'url' => $url . "&type={$module}");
}
$labels[] = dropdown
(
    btn
    (
        setClass('light-pale' . (isset($moreItems[$type]) ? ' primary' : '')),
        $lang->other,
    ),
    set::items($moreItems)
);

$items = array();
foreach($results as $object)
{
    $objectType = $object->objectType == 'case' ? 'testcase' : $object->objectType;
    if(($objectType == 'story' || $objectType == 'requirement' || $objectType == 'execution' || $objectType == 'issue') && !empty($object->extraType))
    {
        $objectType = $lang->search->objectTypeList[$object->extraType];
    }
    else
    {
        $objectType = $lang->searchObjects[$objectType];
    }

    $items[] = div
    (
        setClass('flex border-b h-20'),
        col
        (
            setClass('mr-4 pt-4 w-full'),
            div
            (
                setClass('flex flex-wrap'),
                a
                (
                    setClass('text-md'),
                    setStyle(array('line-height' => '22px')),
                    set::href($object->url),
                    html($object->title)
                ),
                span
                (
                    setClass('label rounded-full ml-2'),
                    $object->objectID
                ),
                span
                (
                    setClass('label rounded-full gray-pale ml-2'),
                    $objectType
                )
            ),
            div
            (
                setClass('text-gray text-sm mt-2'),
                setStyle(array('line-height' => '18px')),
                html($object->summary)
            )
        ),
        div
        (
            setClass('flex items-center w-16'),
            a
            (
                setClass('btn primary-pale'),
                set(array('href' => $object->url, 'data-toggle' => 'modal', 'data-size' => 'lg')),
                $lang->search->preview
            )
        )
    );
}

form
(
    setClass('w-1/2'),
    set::actions(false),
    inputGroup
    (
        input
        (
            on::change('toggleClearWords'),
            setClass('shadow-none'),
            set::name('words'),
            set::value($words)
        ),
        input
        (
            set::type('hidden'),
            set::name('type'),
            set::value($type)
        ),
        span
        (
            setClass('input-group-btn'),
            btn
            (
                on::click('clearWords'),
                setID('clearWords'),
                setClass('shadow-none' . (empty($words) ? ' hidden' : '')),
                icon('close')
            )
        ),
        span
        (
            setClass('input-group-btn border-l'),
            btn
            (
                setClass('text-primary shadow-none'),
                on::click('searchWords'),
                icon('search')
            )
        )
    )
);
div
(
    setClass('py-4'),
    $labels,
);
panel
(
    setID('searchResult'),
    setClass('shadow-none'),
    set::title($lang->search->result . ' :'),
    set::titleClass('text-md font-normal'),
    set::titleProps(array('style' => array('line-height' => '22px'))),
    set::headingClass('justify-start px-4 pt-4 pb-0'),
    set::bodyClass('px-6 pt-0 pb-2'),
    to::heading
    (
        div
        (
            setClass('text-md'),
            setStyle(array('line-height' => '22px')),
            html(sprintf($lang->search->resultCount, $pager->recTotal))
        )
    ),
    $items,
    $items ? div
    (
        setClass('flex justify-between'),
        span
        (
            setClass('mt-2'),
            sprintf($lang->search->executeInfo, $pager->recTotal, $consumed)
        ),
        pager
        (
            set::type('short'),
            set::className('justify-end mt-2')
        )
    ): null
);

render();
