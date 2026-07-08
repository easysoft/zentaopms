<?php
declare(strict_types=1);
/**
 * The execution view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     pipeline
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('repoID', $repoID);

if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
    unset($lang->pipeline->featureBar['execution']);
    unset($config->pipeline->execution->dtable->fieldList['repo']);
    unset($config->pipeline->execution->dtable->fieldList['scope']);
}

if($type == 'space')
{
    unset($config->pipeline->execution->dtable->fieldList['ref']);
    unset($config->pipeline->execution->dtable->fieldList['scope']);
}

featureBar
(
    backBtn
    (
        setClass('mr-2'),
        set::icon('back'),
        set::type('secondary'),
        set::url($this->createLink('pipeline', 'browse', "space={$spaceID}&repoID={$repoID}&type={$type}")),
        $lang->goback . $lang->pipeline->common
    ),
    div(searchToggle(set::module('pipelineexec'), set::open($type == 'bySearch')))
);
if(isset($config->pipeline->execution->dtable->fieldList['repo'])) $config->pipeline->execution->dtable->fieldList['repo']['map'] = $repos;
$config->pipeline->execution->dtable->fieldList['actions']['list']['view']['url'] = array('module' => 'pipeline', 'method' => 'execview', 'params' => "id={id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}");
$tableData = initTableData($executionList, $config->pipeline->execution->dtable->fieldList, $this->pipeline);
dtable
(
    set::cols($config->pipeline->execution->dtable->fieldList),
    set::data($tableData),
    set::userMap($users),
    set::sortLink(createLink('pipeline', 'execution', "space={$spaceID}&repoID={$repoID}&type={$type}&queryID={$queryID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
