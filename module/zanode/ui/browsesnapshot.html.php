<?php
declare(strict_types=1);
/**
 * The browsesnapshot view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;

foreach($snapshotList as $snapshot)
{
    $snapshot->nodeID    = $nodeID;
    $snapshot->isDefault = $snapshot->name == 'defaultSnap' && $snapshot->createdBy == 'system';
    $snapshot->createdBy = $snapshot->isDefault ? $lang->zanode->snapshot->defaultSnapUser : zget($users, $snapshot->createdBy);
}

$snapshotList = initTableData($snapshotList, $config->zanode->snapshotDtable->fieldList, $this->zanode);

to::header('');

dtable
(
    set::cols($config->zanode->snapshotDtable->fieldList),
    set::data($snapshotList),
    set::afterRender(jsRaw('window.afterRender'))
);

render();

