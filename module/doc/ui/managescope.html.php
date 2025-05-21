<?php
declare(strict_types=1);
/**
 * The managescope view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xie Qiyu<xieqiyu@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->docTemplate->manageScope), set::titleClass('text-lg font-bold'));

$scopeItems = array();
$scopeItems[] = array('name' => 'scopes');
$scopeItems[] = array('name' => 'id', 'class' => 'hidden');

$dataItems = array();
foreach($scopeList as $scopeID => $scope)
{
    $item = new stdClass();
    $item->id     = 'id' . $scopeID;
    $item->scopes = $scope->name;
    $item->main   = $scope->main;
    $dataItems[]  = $item;
}

$batchFormOptions = array();
$batchFormOptions['onClickAction'] = jsRaw('window.handleClickBatchFormAction'); // 定义操作列按钮点击事件处理函数。
$batchFormOptions['onRenderRow']   = jsRaw('window.handleRenderRow'); // 定义行渲染事件处理函数。

formBatchPanel
(
    set::items($scopeItems),
    set::data($dataItems),
    set::minRows(6),
    set::actionsText(''),
    set::batchFormOptions($batchFormOptions)
);
