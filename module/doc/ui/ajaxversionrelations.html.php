<?php
declare(strict_types=1);
/**
 * The version relations view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ruogu Liu <liuruogu@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

rsort($relationVersions, SORT_NUMERIC);
$versionItems = array();
foreach($relationVersions as $relationVersion)
{
    $versionItems[] = array
    (
        'text'    => "#$relationVersion",
        'active'  => $relationVersion == $version,
        'onClick' => jsRaw("() => loadModal('" . createLink('doc', 'ajaxVersionRelations', "docID={$docID}&version={$relationVersion}") . "', 'current')")
    );
}

$versionSwitcher = dropdown
(
    btn(set::type('ghost'), "#$version", set::caret()),
    set::items($versionItems)
);

modalHeader
(
    set::title($lang->doc->versionRelation),
    set::titleClass('font-bold text-lg'),
    to::suffix($versionSwitcher)
);

$cols = array();
$cols['deliverableType'] = array('name' => 'deliverableType', 'title' => $lang->deliverable->name, 'type' => 'text', 'link' => array('module' => 'project', 'method' => 'viewDeliverable', 'params' => 'id={deliverableID}&reviewID={reviewID}', 'target' => '_blank'));
$cols['deliverableVersion'] = array('name' => 'deliverableVersion', 'title' => $lang->deliverable->common . (common::checkNotCN() ? ' ' : '') . $lang->deliverable->version, 'type' => 'text');
$cols['baseline'] = array('name' => 'baseline', 'title' => $lang->deliverable->baseline, 'type' => 'text', 'link' => array('module' => 'cm', 'method' => 'view', 'params' => 'baselineID={baselineID}', 'target' => '_blank'));
$cols['baselineVersion'] = array('name' => 'baselineVersion', 'title' => $lang->deliverable->baselineVersion, 'type' => 'text');

dtable
(
    set::bordered(true),
    set::cols(array_values($cols)),
    set::data($relations),
    set::emptyTip($lang->doc->docLang->noDataYet)
);

div
(
    setClass('flex justify-center py-4'),
    btn(setClass('primary'), set('data-dismiss', 'modal'), $lang->close)
);
