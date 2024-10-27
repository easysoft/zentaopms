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
$hasCustomSpace = $type == 'mine' || $type == 'custom';
$privs = array();
$privs['create']       = hasPriv('doc', 'create');
$privs['edit']         = hasPriv('doc', 'edit');
$privs['delete']       = hasPriv('doc', 'delete');
$privs['effort']       = $this->config->edition != 'open' && hasPriv('effort', 'createForObject');
$privs['exportDoc']    = $this->config->edition != 'open' && hasPriv('doc', $type . '2export');
$privs['moveDoc']      = hasPriv('doc', 'moveDoc');
$privs['collect']      = hasPriv('doc', 'collect');
$privs['createLib']    = hasPriv('doc', 'createLib');
$privs['editLib']      = hasPriv('doc', 'editLib');
$privs['moveLib']      = hasPriv('doc', 'moveLib');
$privs['deleteLib']    = hasPriv('doc', 'deleteLib');
$privs['sortDocLib']   = hasPriv('doc', 'sortDocLib');
$privs['exportFiles']  = hasPriv('doc', 'exportFiles');
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
$privs['createOffice'] = true;

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
    set('$options', jsRaw('window.setDocAppOptions'))
);
