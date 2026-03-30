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

panel
(
    zui::FlowExecutionApp
    (
        set::height('calc(100vh - 96px)'),
        set::goBack(jsRaw("() => {goBack('pipeline', '{$backUrl}')}")),
        set::id($execution->id),
        set::name('#' . $execution->id . ' ' . $pipeline->name),
        set::labels($lang->pipeline->flowApp->labels)
    )
);
