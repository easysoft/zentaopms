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

jsVar('inputWords', $lang->search->inputWords);

to::header();

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
    setStyle(array('margin' => '0 auto')),
    set::actions(false),
    inputGroup
    (
        input
        (
            on::change('toggleClearWords'),
            setClass('shadow-none'),
            set::name('words'),
            set::value($words),
            set::placeholder($lang->search->inputWords)
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
        picker
        (
            set::className('shadow-none border-l'),
            set::name('type[]'),
            set::value($type),
            set::items($typeList),
            set::multiple(true)
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

$pagerItems = array();
$pagerItems[] = array('type' => 'info', 'text' => '{page}/{pageTotal}');
$pagerItems[] = array('type' => 'link', 'hint' => $lang->pager->previousPage, 'page' => 'prev', 'icon' => 'icon-angle-left');
$pagerItems[] = array('type' => 'link', 'hint' => $lang->pager->nextPage,     'page' => 'next', 'icon' => 'icon-angle-right');

panel
(
    setID('searchResult'),
    setClass('shadow-none mt-4'),
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
            set::items($pagerItems),
            set::_className('justify-end mt-2')
        )
    ) : null
);

render();
