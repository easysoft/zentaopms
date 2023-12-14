<?php
declare(strict_types=1);
/**
* The docrecentupdate block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$canView = common::hasPriv('doc', 'view');

$docItems = array();

foreach($docList as $doc)
{
    $editTip  = $lang->doc->todayUpdated;
    $interval = $doc->editInterval;
    if($interval->year)
    {
      $editTip = sprintf($lang->doc->yearsUpdated, $interval->year);
    }
    elseif($interval->month)
    {
      $editTip = sprintf($lang->doc->monthsUpdated, $interval->month);
    }
    elseif($interval->day)
    {
      $editTip = sprintf($lang->doc->daysUpdated, $interval->day);
    }

    $docType = $doc->type == 'text' ? 'wiki-file' : $doc->type;

    $docItems[] = cell
    (
        set::width('32%'),
        setStyle('width', '32%'),
        setClass('border rounded-lg p-2'),
        a
        (
            setClass('text-left w-full'),
            set('href', $canView ? createLink('doc', 'view', "docID={$doc->id}") : null),
            span
            (
                setClass('text-gray my-2 pl-2 pull-right'),
                $editTip
            ),
            div
            (
                setClass('font-bold my-2 mr-2 clip'),
                img
                (
                    setClass('inline pr-1'),
                    set('src', "static/svg/{$docType}.svg")
                ),
                $doc->title
            ),
            p
            (
                setClass('edit-date text-gray'),
                $lang->doc->editedDate . (common::checkNotCN() ? ': ' : '：') . $doc->editedDate
            )
        )
    );
}

blockPanel(empty($docList) ? $lang->doc->noDoc : div(setClass('flex flex-wrap content-between gap-3'), $docItems));

render();
