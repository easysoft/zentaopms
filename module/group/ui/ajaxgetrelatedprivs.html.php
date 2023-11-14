<?php
declare(strict_types=1);
/**
 * The ajaxGetRelatedPrivs view file of group module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
namespace zin;

$dependTree = null;
foreach($relatedPrivData['depend'] as $dependPrivs)
{
    $dependTree[] = checkboxGroup
        (
            set::title(array('text' => $dependPrivs['text'], 'id' => "dependPrivs[{$dependPrivs['id']}]", 'name' => 'dependPrivs[]', 'data-id' => $dependPrivs['id'], 'data-has-children' => !empty($dependPrivs['children']), 'disabled' => true, 'checked' => true)),
            !empty($dependPrivs['children']) ? set::items($dependPrivs['children']) : null
        );
}

$recommendTree = null;
foreach($relatedPrivData['recommend'] as $recommendPrivs)
{
    $recommendTree[] = checkboxGroup
        (
            set::title(array('text' => $recommendPrivs['text'], 'id' => "recommendPrivs[{$recommendPrivs['id']}]", 'name' => 'recommendPrivs[]', 'data-id' => $recommendPrivs['id'], 'data-has-children' => !empty($recommendPrivs['children']), 'checked' => $recommendPrivs['checked'], 'labelClass' => $recommendPrivs['labelClass'])),
            !empty($recommendPrivs['children']) ? set::items($recommendPrivs['children']) : null
        );
}

div
(
    setClass('priv-panel'),
    div
    (
        setClass('panel-title'),
        $lang->group->dependentPrivs,
        icon
        (
            'help',
            set('data-toggle', 'tooltip'),
            set('data-title', $lang->group->dependPrivTips),
            set('data-placement', 'right'),
            set('data-type', 'white'),
            set('data-class-name', 'text-gray border border-light w-40'),
            setClass('text-gray')
        )
    ),
    div
    (
        setClass('panel-content'),
        div
        (
            setClass('menuTree depend menu-active-primary menu-hover-primary'),
            setClass(count($relatedPrivData['depend']) == 0 ? 'hidden' : ''),
            $dependTree
        ),
        div
        (
            setClass('table-empty-tip text-center'),
            setClass(count($relatedPrivData['depend']) > 0 ? 'hidden' : ''),
            span
            (
                setClass('text-gray'),
                $lang->noData
            )
        )
    )
);
div
(
    setClass('priv-panel mt-m'),
    div
    (
        setClass('panel-title'),
        $lang->group->recommendPrivs,
        icon
        (
            'help',
            set('data-toggle', 'tooltip'),
            set('data-title', $lang->group->recommendPrivTips),
            set('data-placement', 'right'),
            set('data-type', 'white'),
            set('data-class-name', 'text-gray border border-light w-40'),
            setClass('text-gray')
        )
    ),
    div
    (
        setClass('panel-content'),
        div
        (
            setClass('menuTree recommend menu-active-primary menu-hover-primary'),
            setClass(count($relatedPrivData['recommend']) == 0 ? 'hidden' : ''),
            $recommendTree
        ),
        div
        (
            setClass('table-empty-tip text-center'),
            setClass(count($relatedPrivData['recommend']) > 0 ? 'hidden' : ''),
            span
            (
                setClass('text-gray'),
                $lang->noData
            )
        )
    )
);

