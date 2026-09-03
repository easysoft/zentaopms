<?php
declare(strict_types=1);
/**
 * The view view file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 */
namespace zin;

if(isInModal()) set::size('lg');

$levelType = $this->errorlog->getLevelType((int)$log->level);

modalHeader(set::title($this->lang->errorlog->view . ' #' . $log->id));

div
(
    setClass('px-4 pt-3 pb-5'),
    div
    (
        setClass('flex items-center gap-3 pb-3 border-b'),
        span(setClass("label $levelType"), $log->levelName),
        span(setClass('font-bold'), $log->module . ' / ' . $log->method),
        span(setClass('text-muted text-sm flex-auto text-right'), $log->createdDate)
    ),
    div
    (
        setClass('space-y-2 pt-3'),
        div(setClass('flex gap-2'), div(setClass('w-28 flex-none text-muted'), $lang->errorlog->requestID),  div(setClass('flex-auto font-mono break-all'), $log->requestID)),
        div(setClass('flex gap-2'), div(setClass('w-28 flex-none text-muted'), $lang->errorlog->account),    div(setClass('flex-auto break-all'), $log->account)),
        div(setClass('flex gap-2'), div(setClass('w-28 flex-none text-muted'), $lang->errorlog->url),        div(setClass('flex-auto break-all'), $log->url)),
        div(setClass('flex gap-2'), div(setClass('w-28 flex-none text-muted'), $lang->errorlog->file),       div(setClass('flex-auto font-mono break-all'), $log->file . ':' . $log->line)),
        div(setClass('flex gap-2'), div(setClass('w-28 flex-none text-muted'), $lang->errorlog->message),    div(setClass('flex-auto break-all whitespace-pre-wrap'), $log->message)),
        div
        (
            setClass('flex gap-2'),
            div(setClass('w-28 flex-none text-muted'), $lang->errorlog->trace),
            div
            (
                setClass('flex-auto'),
                div
                (
                    setClass('font-mono text-sm whitespace-pre overflow-auto rounded border bg-gray-50 p-3'),
                    setStyle(array('max-height' => '60vh')),
                    $log->trace
                )
            )
        )
    )
);

render();
