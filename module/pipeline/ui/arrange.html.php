<?php
declare(strict_types=1);
/**
 * The edit view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     pipeline
 * @link        https://www.zentao.net
 */
namespace zin;
if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
}

$backUrl = createLink('pipeline', "browse", "space={$spaceID}&repoID={$repoID}&type={$type}");

panel
(
    zui::FlowApp
    (
        set::height('calc(100vh - 96px)'),
        set::goBack(jsRaw("() => {goBack('pipeline', '{$backUrl}')}")),
        set::id($pipeline->id),
        set::name($pipeline->name),
        set::published($pipeline->status == 'active'),
        set::labels($lang->pipeline->flowApp->labels),
    )
);
