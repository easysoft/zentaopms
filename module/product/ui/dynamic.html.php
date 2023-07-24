<?php
declare(strict_types=1);
/**
 * The dynamic view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao <chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('productID', $productID);

featureBar
(
    set::current($type),
    set::linkParams("productID={$productID}&type={key}"),
    li
    (
        setClass('w-40'),
        picker
        (
            set::name('user'),
            set::placeholder($lang->product->viewByUser),
            set::items($userIdPairs),
            set::value($param),
            on::change('onChangeUser')
        )
    )
);

/* No dynamic data. */
if(empty($dateGroups))
{
    $content = div
    (
        setClass('flex items-center justify-center h-64'),
        span
        (
            setClass('text-gray'),
            $lang->action->noDynamic
        ),
    );

    return;
}

/* Render dynamic data. */
$content     = array();
$firstAction = null;
$lastAction  = null;
foreach($dateGroups as $date => $actions)
{
    $isToday   = date(DT_DATE4) == $date;

    /* Grab the first and last action. */
    foreach($actions as $action)
    {
        if(empty($firstAction)) $firstAction = $action;
        $lastAction = $action;
    }

    $dynamicsGroup = li
    (
        div
        (
            setClass('my-4 cursor-pointer'),
            icon('angle-down text-primary border-2 rounded-full toolbar z-10 bg-canvas'),
            span
            (
                setClass('article-h3 ml-2'),
                $isToday ? $lang->action->dynamic->today : $date,
            ),
            on::click('toggleCollapse')
        ),
        div
        (
            setClass('flex-auto mx-6 mt-2 px-4 alert lighter'),
            setClass($type == 'today' ? 'border-secondary' : ''),
            dynamic
            (
                set::dynamics($actions),
                set::users($accountPairs),
            )
        )
    );

    $content[] = ul
    (
        setClass('timeline'),
        $dynamicsGroup
    );
}

panel($content);

/* Render previous and next float buttons. */
if(empty($firstAction)) return;

$firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
$lastDate  = substr($lastAction->originalDate, 0, 10);
$hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
$hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
$preLink   = $hasPre ? inlink('dynamic', "productID=$productID&type=$type&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
$nextLink  = $hasNext ? inlink('dynamic', "productID=$productID&type=$type&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';

if($hasPre || $hasNext)
{
    floatPreNextBtn
    (
        empty($hasNext)  ? null : set::preLink($nextLink),
        empty($hasPre) ? null : set::nextLink($preLink)

    );
}
