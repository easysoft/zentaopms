<?php
declare(strict_types=1);
/**
 * The view file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        http://www.zentao.net
 */
namespace zin;

/**
 * Build content of table data.
 *
 * @param  array  $items
 * @access public
 * @return array
 */
$buildItems = function($items): array
{
    $itemList = array();
    foreach($items as $item)
    {
        $itemList[] = item
        (
            set::name($item['name']),
            !empty($item['href']) ? a
            (
                set::href($item['href']),
                !empty($item['attr']) && is_array($item['attr']) ? set($item['attr']) : null,
                $item['text']
            ) : $item['text'],
            set::collapse(!empty($item['text'])),
        );
    }

    return $itemList;
};

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($metric->id),
            set::level(1),
            set::text($metric->name)
        )
    ),
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            $lang->goback
        )
    )
);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->metric->definition),
            set::content(empty(trim($metric->definition)) ? $lang->metric->noFormula : $metric->definition),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->metric->sqlStatement),
            set::content(empty(trim($metric->sql)) ? $lang->metric->noSQL : $metric->sql),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->metric->metricData),
        )
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'metric', 'objectID' => $metric->id))),
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendBasicInfo'),
                set::className('overflow-hidden'),
                set::title($lang->metric->legendBasicInfo),
                set::active(true),
                tableData
                (
                    $buildItems($legendBasic)
                )
            ),
        ),
    )
);

if(!isInModal())
{
    floatPreNextBtn
    (
        !empty($preAndNext->pre)  ? set::preLink(createLink('metric', 'view', "id={$preAndNext->pre->id}"))   : null,
        !empty($preAndNext->next) ? set::nextLink(createLink('metric', 'view', "id={$preAndNext->next->id}")) : null
    );
}

$actionMenuList = !$metric->deleted ? $this->metric->buildOperateMenu($metric) : array();
div
(
    set::className('w-2/3 text-center fixed actions-menu'),
    set::className($metric->deleted ? 'no-divider' : ''),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        !empty($actionMenuList['main']) ? set::main($actionMenuList['main']) : null,
        !empty($actionMenuList['suffix']) ? set::suffix($actionMenuList['suffix']) : null,
        set::object($metric)
    )
);

render();
