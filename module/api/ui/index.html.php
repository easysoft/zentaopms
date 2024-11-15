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

$privs = array();
$privs['collect']      = 'no';
$privs['createApi']    = hasPriv('api', 'create');
$privs['exportApi']    = $this->config->edition != 'open' && hasPriv('api', 'export');
$privs['edit']         = hasPriv('api', 'edit');
$privs['view']         = hasPriv('api', 'view');
$privs['delete']       = hasPriv('api', 'delete');
$privs['createLib']    = hasPriv('api', 'createLib');
$privs['editLib']      = hasPriv('api', 'editLib');
$privs['moveLib']      = hasPriv('api', 'moveLib');
$privs['sortDoclib']   = hasPriv('doc', 'sortDoclib');
$privs['deleteLib']    = hasPriv('api', 'deleteLib');
$privs['createStruct'] = hasPriv('api', 'createStruct');
$privs['createRelease']= hasPriv('api', 'createRelease');
$privs['addModule']    = hasPriv('doc', 'addCatalog');
$privs['deleteModule'] = hasPriv('doc', 'deleteCatalog');
$privs['editModule']   = hasPriv('doc', 'editCatalog');
$privs['sortModule']   = hasPriv('doc', 'sortCatalog');
$privs['releases']     = hasPriv('api', 'releases');
$privs['struct']       = hasPriv('api', 'struct');

docApp
(
    set::spaceType('api'),
    set::spaceID($objectType == 'nolink' ? 'nolink' : "$objectType.$objectID"),
    set::mode($mode),
    set::pager(array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'page' => $pageID)),
    set::privs($privs),
    set::docID($apiID),
    set::libIcon('interface-lib'),
    set::fetcher(createLink('api', 'ajaxGetData', 'spaceID={spaceID}&picks={picks}')),
    set::docFetcher(null),
    set::libSummariesFetcher(null),
    set::maxHomeLibsOfSpace(0),
    set::params($params),
    set::autoSelectLib(),
    set::fetchOnChangeSpace(),
    set('$options', jsRaw('window.setDocAppOptions'))
);
