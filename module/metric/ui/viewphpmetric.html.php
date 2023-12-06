<?php
declare(strict_types=1);
/**
 * The view file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        https://www.zentao.net
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
            set::collapse(!empty($item['text']))
        );
    }

    return $itemList;
};

$oldMetricTabs = $metric->fromID === 0 ? '' : tabs
(
    set::collapse(true),
    tabPane
    (
        set::key('oldMetricInfo'),
        set::title($lang->metric->oldMetricInfo),
        set::className('overflow-hidden'),
        set::active(true),
        tableData
        (
            set::tdClass('word-no-wrap'),
            $buildItems($oldMetricInfo)
        )
    )
);

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

$fnGenerateDataDisplay = function() use($resultData, $resultHeader, $lang, $metric)
{
    if(empty($resultData)) $resultData = array($resultData);
    if(count($resultData) == 1 && count((array)$resultData[0]) == 1) return div
        (
            set::className('card-data'),
            center
            (
                p
                (
                    set::className('card-digit'),
                    $resultData[0]->value
                ),
                p
                (
                    set::className('card-title'),
                    $lang->metric->objectList[$metric->object]
                ),
            )

        );

    return dtable
        (
            set::height(400),
            set::cols($resultHeader),
            set::data($resultData)
        );
};

if(!$this->metric->checkCalcExists($metric))
{
    $metricDataSection = section
    (
        set::title($lang->metric->metricData),
        set::content($lang->metric->noCalc)
    );
}
else
{
    $metricDataSection = section
    (
        set::title($lang->metric->metricData),
        $fnGenerateDataDisplay($resultData, $resultHeader, $lang, $metric)
    );
}

$actionMenuList = !$metric->deleted ? $this->metric->buildOperateMenu($metric) : array();
detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->metric->desc),
            set::content(empty(trim($metric->desc)) ? $lang->metric->noDesc : $metric->desc),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->metric->formula),
            set::content(empty(trim($metric->definition)) ? $lang->metric->noFormula : $metric->definition),
            set::useHtml(true)
        ),
        $metricDataSection
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'metric', 'objectID' => $metric->id)))
    ),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        !empty($actionMenuList['main']) ? set::main($actionMenuList['main']) : null,
        !empty($actionMenuList['suffix']) ? set::suffix($actionMenuList['suffix']) : null,
        set::object($metric)
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
            tabPane
            (
                set::key('legendCreateInfo'),
                set::title($lang->metric->legendCreateInfo),
                tableData
                (
                    $buildItems($createEditInfo)
                )
            )
        ),
        $oldMetricTabs
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

render();
