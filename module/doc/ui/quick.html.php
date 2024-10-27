<?php
declare(strict_types=1);
/**
 * The quick view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$privs = array();
$privs['edit']    = hasPriv('doc', 'edit');
$privs['delete']  = hasPriv('doc', 'delete');
$privs['moveDoc'] = hasPriv('doc', 'moveDoc');
$privs['collect'] = hasPriv('doc', 'collect');

$spaceID = 1;
$libID   = $menu['id'];
$data    = array('spaceID' => $spaceID, 'docs' => array_values($docs));
$data['spaces'][] = array('name' => $lang->doc->quick, 'id' => $spaceID);
foreach($config->doc->quickMenu as $key => $item) $data['libs'][] = $item + array('space' => $spaceID, 'quickType' => $key);

$langData = array();
$langData['searchLibPlaceholder'] = $lang->searchAB;

docApp
(
    set::data($data),
    set::spaceID(1),
    set::libID($libID),
    set::docID($docID),
    set::noSpace(),
    set::noModule(),
    set::homeName(false),
    set::mode($docID ? 'view' : 'list'),
    set::privs($privs),
    set::userMap($users),
    set::spaceIcon(''),
    set::langData($langData),
    set::pager(array('recTotal' => count($docs), 'recPerPage' => $recPerPage, 'page' => $pageID)),
    set::docFetcher(createLink('doc', 'ajaxGetDoc', 'docID={docID}&version={version}&details=yes')),
    set::viewModeUrl(createLink('doc', 'quick', 'type={spaceType}&spaceID={spaceID}&libID={libID}&moduleID={moduleID}&docID={docID}&mode={mode}&orderBy={orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}&filterType={filterType}&search={search}&noSpace={noSpace}'))
);
