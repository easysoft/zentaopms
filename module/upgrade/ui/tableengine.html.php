<?php
declare(strict_types=1);
/**
 * The table engine step view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

$isMysql = $config->db->driver == 'mysql';
$count   = count($myisamTables);
$isEn    = $app->getClientLang() == 'en';

$items = array();
foreach($myisamTables as $table)
{
    $sql = "ALTER TABLE `{$table}` ENGINE='InnoDB'";
    $items[] = row
    (
        setClass('change-item items-center gap-3'),
        setData(['table' => $table]),
        span
        (
            setClass("label gray-pale text-gray-400 px-2.5 py-1 " . ($isEn ? 'w-14' : 'w-11')),
            setData(['text' => $lang->upgrade->tableEngine->todo, 'doneText' => $lang->upgrade->tableEngine->done, 'failText' => $lang->upgrade->tableEngine->failed]),
            icon(setClass('animate-spin'), 'spinner-indicator')
        ),
        span
        (
            sprintf($lang->upgrade->tableEngine->change, $table)
        ),
        a
        (
            set::href('javascript:showSQL(' . json_encode($sql) . ')'),
            icon(setClass('text-lg text-gray-400'), 'fields')
        )
    );
}

jsVar('myisamTables', $myisamTables);

if(!$isMysql || empty($myisamTables))
{
    div
    (
        setClass('text-gray-400'),
        $lang->upgrade->noNeedProcess
    );
}
else
{
    col
    (
        setClass('gap-4 w-full h-full'),
        row
        (
            setClass('items-center justify-between'),
            span
            (
                setClass('text-lg font-medium'),
                $lang->upgrade->tableEngine->common
            ),
            span
            (
                html(sprintf($lang->upgrade->dataProcessProcessed, "<span id='engineProcessedCount'>0</span>", $count))
            )
        ),
        col
        (
            setID('engineBox'),
            setClass('gap-2 overflow-x-hidden overflow-y-auto flex-1'),
            $items
        )
    );
}

render();
