<?php
declare(strict_types=1);
/**
 * The database view step view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

$count = $viewCount;
$isEn  = $app->getClientLang() == 'en';

$items = array();
foreach($viewList as $view)
{
    $items[] = row
    (
        setClass('change-item items-center gap-3'),
        setData(['view' => $view['name']]),
        span
        (
            setClass("label gray-pale text-gray-400 px-2.5 py-1 " . ($isEn ? 'w-14' : 'w-11')),
            setData(['text' => $lang->upgrade->dbView->todo]),
            icon(setClass('animate-spin'), 'spinner-indicator')
        ),
        span
        (
            sprintf($lang->upgrade->dbView->regenerate, $view['name'])
        ),
        a
        (
            set::href('javascript:showSQL(' . json_encode($view['sql']) . ')'),
            icon(setClass('text-lg text-gray-400'), 'fields')
        )
    );
}

col
(
    setClass('gap-4 w-full h-full'),
    row
    (
        setClass('items-center justify-between'),
        span
        (
            setClass('text-lg font-medium'),
            $lang->upgrade->dbView->common
        ),
        span
        (
            html(sprintf($lang->upgrade->dataProcessProcessed, "<span id='viewProcessedCount'>0</span>", $count))
        )
    ),
    col
    (
        setID('viewBox'),
        setClass('gap-2 overflow-x-hidden overflow-y-auto flex-1'),
        $items
    )
);

render();
