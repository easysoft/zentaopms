<?php
declare(strict_types=1);
/**
 * The browse view file of stage module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     stage
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar();

$canCreateStage      = hasPriv('stage', 'create');
$canbatchCreateStage = hasPriv('stage', 'batchCreate');
if($canCreateStage) $createItem = array('icon' => 'plus', 'class' => 'primary', 'text' => $lang->stage->create, 'url' => $this->createLink('stage', 'create', "groupID={$groupID}&type={$type}"), 'data-toggle' => 'modal');
if($canbatchCreateStage) $batchCreateItem = array('icon' => 'plus', 'class' => 'primary mr-4', 'text' => $lang->stage->batchCreate, 'url' => $this->createLink('stage', 'batchCreate', "groupID={$groupID}&type={$type}"));
toolbar
(
    !empty($batchCreateItem) ? item(set($batchCreateItem)) : null,
    !empty($createItem) ? item(set($createItem)) : null
);

$tableData = initTableData($stages, $config->stage->dtable->fieldList, $this->stage);
dtable
(
    set::cols($config->stage->dtable->fieldList),
    set::data($tableData),
    set::orderBy($orderBy),
    set::sortLink(createLink('stage', 'browse', "orderBy={name}_{sortType}&type={$type}"))
);
