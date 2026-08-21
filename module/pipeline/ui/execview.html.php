<?php
declare(strict_types=1);
/**
 * The exec view file of pipeline module of ZenTaoPMS.
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     pipleine
 * @link        https://www.zentao.net
 */
namespace zin;
if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
}

$backUrl = createLink('pipeline', "execution", "space={$spaceID}&repoID={$repoID}&type={$type}");

jsVar('window.executionID', (int)$execution->id);
jsVar('window.confirmCancelExec', $lang->pipeline->confirmCancelExec);

$execViewJS = file_get_contents(dirname(dirname(__FILE__)) . '/js/execview.ui.js');

div
(
    setClass('relative'),
    h::js($execViewJS),
    h::css('.execution-status-killed, .execution-status-canceled {color: var(--color-status-cancel);}'),
    zui::FlowExecutionApp
    (
        set::height('calc(100vh - 96px)'),
        set::goBack(jsRaw("() => {goBack('pipeline', '{$backUrl}')}")),
        set::id($execution->id),
        set::name('#' . $execution->id . ' ' . $pipeline->name),
        set::labels($lang->pipeline->flowApp->labels)
    ),
    btn
    (
        setID('cancelExecBtn'),
        set::type('primary'),
        setClass('absolute right-2 top-1 z-10 hidden'),
        $lang->pipeline->cancelBtn
    )
);
