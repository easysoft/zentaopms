<?php
declare(strict_types=1);
/**
 * The app view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

include($this->app->getModuleRoot() . 'ai/ui/promptmenu.html.php');

/*
 * 定义库类型名称和图标。
 * Define the lib types and icons.
 */
$libTypes = array();
if($type === 'project')
{
    $libTypes[] = array('type' => 'project',   'name' => $lang->projectCommon,     'icon' => 'project');
    $libTypes[] = array('type' => 'execution', 'name' => $lang->execution->common, 'icon' => 'run');
}

/**
 * 定义文档界面上的权限。
 * Define the privs of doc app.
 */
$hasCustomSpace  = $type == 'mine' || $type == 'custom';
$projectTemplate = ($type === 'project' && !empty($project->isTpl)) || ($type == 'execution' && !empty($execution->isTpl));
$privs = array();
$privs['create']       = hasPriv('doc', 'create') && !$projectTemplate;
$privs['edit']         = hasPriv('doc', 'edit') && !$projectTemplate;
$privs['delete']       = hasPriv('doc', 'delete') && !$projectTemplate;
$privs['view']         = hasPriv('doc', 'view');
$privs['effort']       = $this->config->edition != 'open' && hasPriv('effort', 'createForObject');
$privs['exportDoc']    = $this->config->edition != 'open' && hasPriv('doc', $type . '2export') && !$projectTemplate;
$privs['exportApi']    = $this->config->edition != 'open' && hasPriv('api', 'export');
$privs['moveDoc']      = hasPriv('doc', 'moveDoc') && !$projectTemplate;
$privs['collect']      = hasPriv('doc', 'collect');
$privs['createLib']    = hasPriv('doc', 'createLib');
$privs['editLib']      = hasPriv('doc', 'editLib');
$privs['moveLib']      = hasPriv('doc', 'moveLib') && !$projectTemplate;
$privs['deleteLib']    = hasPriv('doc', 'deleteLib');
$privs['sortDocLib']   = hasPriv('doc', 'sortDocLib');
$privs['uploadFile']   = hasPriv('doc', 'create') && !$projectTemplate;
$privs['editFile']     = hasPriv('file', 'edit');
$privs['deleteFile']   = hasPriv('doc', 'deleteFile');
$privs['exportFiles']  = hasPriv('doc', 'exportFiles') && !$projectTemplate;
$privs['createSpace']  = $hasCustomSpace && hasPriv('doc', 'createSpace');
$privs['deleteSpace']  = $hasCustomSpace && hasPriv('doc', 'deleteSpace');
$privs['editSpace']    = $hasCustomSpace && hasPriv('doc', 'editSpace');
$privs['addModule']    = hasPriv('doc', 'addCatalog');
$privs['deleteModule'] = hasPriv('doc', 'deleteCatalog');
$privs['editModule']   = hasPriv('doc', 'editCatalog');
$privs['sortModule']   = hasPriv('doc', 'sortCatalog');
$privs['sortDoclib']   = hasPriv('doc', 'sortDoclib');
$privs['sortDoc']      = hasPriv('doc', 'sortDoc');
$privs['batchMoveDoc'] = hasPriv('doc', 'batchMoveDoc');
$privs['showFiles']    = hasPriv('doc', 'showFiles');
$privs['createApi']    = hasPriv('api', 'create');
$privs['editApi']      = hasPriv('api', 'edit');
$privs['viewApi']      = hasPriv('api', 'view');
$privs['createStruct'] = hasPriv('api', 'createStruct');
$privs['createRelease']= hasPriv('api', 'createRelease');
$privs['releases']     = hasPriv('api', 'releases');
$privs['struct']       = hasPriv('api', 'struct');
$privs['createOffice'] = $privs['create'];
$privs['restoreDoc']   = $privs['edit'];

$privs['addChapter']    = hasPriv('doc', 'addChapter');
$privs['editChapter']   = hasPriv('doc', 'editChapter');
$privs['deleteChapter'] = hasPriv('doc', 'deleteChapter');
$privs['sortChapter']   = hasPriv('doc', 'sortChapter');

$privs['productStory']         = hasPriv('product', 'browse');
$privs['projectStory']         = hasPriv('projectstory', 'story');
$privs['executionStory']       = hasPriv('execution', 'story');
$privs['productCase']          = hasPriv('testcase', 'browse');
$privs['caselibBrowse']        = hasPriv('caselib', 'browse');
$privs['productBug']           = hasPriv('bug', 'browse');
$privs['taskBrowse']           = hasPriv('execution', 'task');
$privs['productplanBrowse']    = hasPriv('productplan', 'browse');
$privs['releaseBrowse']        = hasPriv('release', 'browse');
$privs['projectReleaseBrowse'] = hasPriv('projectRelease', 'browse');
$privs['feedbackBrowse']       = hasPriv('feedback', 'admin');
$privs['ticketBrowse']         = hasPriv('ticket', 'browse');
$privs['requirementBrowse']    = hasPriv('product', 'requirement');
$privs['epicBrowse']           = hasPriv('product', 'epic');

$privs['storyView']       = hasPriv('story', 'view');
$privs['taskView']        = hasPriv('task', 'view');
$privs['caseView']        = hasPriv('testcase', 'view');
$privs['bugView']         = hasPriv('bug', 'view');
$privs['productplanView'] = hasPriv('productplan', 'view');
$privs['releaseView']     = hasPriv('release', 'view');
$privs['feedbackView']    = hasPriv('feedback', 'adminView');
$privs['ticketView']      = hasPriv('ticket', 'view');

$privs['programplanBrowse'] = hasPriv('programplan', 'browse');

$privs['storyBrowse'] = $privs['productStory'] || $privs['executionStory'] || $privs['productplanView'];
$privs['caseBrowse']  = $privs['productCase'] || $privs['caselibBrowse'];
$privs['bugBrowse']   = $privs['productBug'] || $privs['productplanView'];

$homeName = false;
if($app->moduleName == 'doc' && isset($lang->doc->spaceList[$type]) && !$noSpace) $homeName = $lang->doc->spaceList[$type];

docApp
(
    set::spaceType($type),
    set::libTypes($libTypes),
    set::mode($mode),
    set::noSpace($noSpace),
    set::homeName($homeName),
    set::pager(array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'page' => $pageID)),
    set::privs($privs),
    set::showLibFiles($privs['showFiles'] ? array('product', 'project', 'execution') : false),
    set::formatDataItem
    (
        jsCallback('type', 'item')
            ->beginIf('type === "doc"', 'item.api', 'typeof item.id === "number"')
            ->do('item.id = `api.${item.id}`')
            ->endIf()
            ->do('return item')
    ),
    set('$options', jsRaw('window.setDocAppOptions'))
);
