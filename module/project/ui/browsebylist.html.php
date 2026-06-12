<?php
declare(strict_types=1);
/**
 * The browsebylist view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('langManDay',   $lang->program->manDay);
jsVar('delayWarning', $lang->task->delayWarning);

/* zin: Define the sidebar in main content. */
$settingLink = hasPriv('project', 'programTitle') ? createLink('project', 'programTitle') : '';
empty($globalDisableProgram) && $config->vision != 'lite' && helper::hasFeature('program') ? sidebar
(
    moduleMenu(set(array
    (
        'modules'     => $programTree,
        'activeKey'   => $programID,
        'closeLink'   => $this->createLink('project', 'browse', "programID=0&browseType={$browseType}"),
        'settingLink' => $settingLink,
        'settingText' => $lang->project->moduleSetting,
        'showDisplay' => false,
        'isInModal'   => true
    )))
) : null;

$canBatchEdit = hasPriv('project', 'batchEdit');
$footToolbar  = array();
if($canBatchEdit)
{
    $footToolbar['items'][] = array
    (
        'type'  => 'btn-group',
        'items' => array
        (
            array('text' => $lang->edit, 'className' => 'btn size-sm', 'btnType' => 'secondary', 'data-url' => createLink('project', 'batchEdit'), 'onClick' => jsRaw('(e) => handleBatchBtnClick(e)'))
        )
    );
}

if($config->edition != 'open') $config->project->dtable->fieldList['workflowGroup']['map'] = $this->loadModel('workflowGroup')->getPairs('project', 'all', 1, 'all');
$settings = $this->loadModel('datatable')->getSetting('project');
foreach($settings as $key => $value)
{
    if($value['name'] == 'status' && strpos(',all,bysearch,undone,', ",$browseType,") === false)      unset($settings[$key]);
    if(commonModel::isTutorialMode() && in_array($value['name'], array('PM', 'budget', 'teamCount'))) unset($settings[$key]);
}
$tableData = initTableData($projectStats, $settings, $this->project);
$tableData = array_map(function($item)
{
    if(!$item->workflowGroup) $item->workflowGroup = '';
    return $item;
}, $tableData);

/* zin: Define the dtable in main content. */
dtable
(
    set::id('table-project-browse'),
    set::groupDivider(true),
    set::cols($settings),
    set::data($tableData),
    set::checkable($canBatchEdit),
    set::footToolbar($footToolbar),
    set::orderBy($orderBy),
    set::sortLink(createLink('project', 'browse', "programID=$programID&browseType=$browseType&param=$param&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::customCols(true),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::emptyTip($lang->project->empty),
    set::createTip($lang->project->create),
    set::createLink(hasPriv('project', 'create') ? createLink('project', 'createGuide') : ''),
    set::createAttr("data-toggle='modal'")
);

/* ====== Render page ====== */
render();
