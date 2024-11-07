<?php
declare(strict_types=1);
/**
 * The api index file of api module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     api
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
$privs['collect']      = 'no';
$privs['create']       = hasPriv('api', 'create');
$privs['edit']         = hasPriv('api', 'edit');
$privs['delete']       = hasPriv('api', 'delete');
$privs['createLib']    = hasPriv('api', 'createLib');
$privs['editLib']      = hasPriv('api', 'editLib');
$privs['moveLib']      = hasPriv('api', 'moveLib');
$privs['sortDoclib']   = hasPriv('doc', 'sortDoclib');
$privs['deleteLib']    = hasPriv('api', 'deleteLib');
$privs['addModule']    = hasPriv('doc', 'addCatalog');
$privs['deleteModule'] = hasPriv('doc', 'deleteCatalog');
$privs['editModule']   = hasPriv('doc', 'editCatalog');
$privs['sortModule']   = hasPriv('doc', 'sortCatalog');

$langData = array();
$langData['filterTypes']      = $lang->api->filterTypes;
$langData['spaceFilterTypes'] = $lang->api->homeFilterTypes;
$langData['createLib']        = $lang->api->createLib;
$langData['createDoc']        = $lang->api->createApi;
$langData['struct']           = $lang->api->struct;
$langData['releases']         = $lang->api->releases;
$langData['module']           = $lang->api->module;
$langData['noDocs']           = $lang->api->noApi;
$langData['version']          = $lang->api->version;
$langData['defaultVersion']   = $lang->api->defaultVersion;
$langData['createStruct']     = $lang->api->createStruct;
$langData['createRelease']    = $lang->api->createRelease;
$langData['save']             = $lang->save;

docApp
(
    set::spaceType('api'),
    set::libTypes($libTypes),
    set::mode($mode),
    set::pager(array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'page' => $pageID)),
    set::privs($privs),
    set::docID($apiID),
    set::fetcher(createLink('api', 'ajaxGetData', 'spaceID={spaceID}&picks={picks}')),
    set::docFetcher(null),
    set::libSummariesFetcher(null),
    set::fetchOnChangeSpace(false),
    set::maxHomeLibsOfSpace(0),
    set::langData($langData),
    set('$options', jsRaw('window.setDocAppOptions'))
);
