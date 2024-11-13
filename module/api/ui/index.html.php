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

docApp
(
    set::spaceType('api'),
    set::spaceID($objectType == 'nolink' ? 'nolink' : "$objectType.$objectID"),
    set::mode($mode),
    set::pager(array('recTotal' => $recTotal, 'recPerPage' => $recPerPage, 'page' => $pageID)),
    set::privs($privs),
    set::docID($apiID),
    set::fetcher(createLink('api', 'ajaxGetData', 'spaceID={spaceID}&picks={picks}')),
    set::docFetcher(null),
    set::libSummariesFetcher(null),
    set::maxHomeLibsOfSpace(0),
    set::params($params),
    set::autoSelectLib(),
    set('$options', jsRaw('window.setDocAppOptions'))
);
