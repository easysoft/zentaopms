<?php
declare(strict_types=1);
/**
 * The managescope view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xie Qiyu<xieqiyu@chandao.com>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->doc->manageScope), set::titleClass('text-lg font-bold'));

$scopeItems = array();
$scopeItems[] = array('name' => 'scopes');
$scopeItems[] = array('name' => 'id', 'class' => 'hidden');

/*
 * TODO【文档-模板广场维护范围-保存】
 * 现状：已有范围直接把数据库 ID 作为批量表单行键，新增行使用行列索引作为行键；两者可能重名并互相覆盖。
 * 设计（伪代码）：
 *   为每个已有范围设置 rowKey = 'scope' . scopeID;                                      // 已有范围使用独立命名空间行键
 *   保留已有范围的 id = scopeID;                                                        // 隐藏字段继续提交真实数据库 ID
 *   batchFormOptions['idKey'] = 'rowKey';                                                // 仅改变 scopes/id 字段的数组下标
 *   已有行提交为 scopes[scope{ID}]、id[scope{ID}]；新增行保持 scopes[行号]、id[行号]；  // 两侧不会再撞键
 *   控制器继续按 $index 读取 $_POST['id'][$index]，保持现有保存、删除和插入逻辑不变。   // 保持现有行为
 * 备注：
 *   - idKey 只控制表单字段下标，不会改变 id 隐藏字段的值，控制器无需识别 scope 前缀。
 *   - 回归场景需覆盖内置范围 ID 与新增行行号相同，保存后全部已有范围仍存在，且新增范围正确写入。
 */
$dataItems = array();
foreach($scopeList as $scopeID => $scope)
{
    $item = new stdClass();
    $item->id     = $scopeID;
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
