<?php
/**
 * The tree module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      zengqingyang wangguannan
 * @package     tree
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->tree = new stdclass();
$lang->tree->common = 'モジュール管理';
$lang->tree->edit = '編集';
$lang->tree->delete = '削除';
$lang->tree->browse = '通用モジュール管理';
$lang->tree->browseTask = 'タスクモジュール管理';
$lang->tree->manage = 'モジュール管理';
$lang->tree->fix = 'データ修正';
$lang->tree->manageProduct = "{$lang->productCommon}ビューモジュール管理";
$lang->tree->manageProject = "{$lang->projectCommon}ビューモジュール管理";
$lang->tree->manageLine = 'プロダクトライン管理';
$lang->tree->manageBug = 'テストビューモジュール管理';
$lang->tree->manageCase = 'ケースビューモジュール管理';
$lang->tree->manageCaseLib = 'ケースライブラリモジュール管理';
$lang->tree->manageCustomDoc = 'ドキュメントライブラリモジュール管理';
$lang->tree->updateOrder = 'ソート更新';
$lang->tree->manageChild = 'サブモジュール管理';
$lang->tree->manageStoryChild = 'サブモジュール管理';
$lang->tree->manageLineChild = 'プロダクトライン管理';
$lang->tree->manageBugChild = 'バグサブモジュール管理';
$lang->tree->manageCaseChild = 'ケースサブモジュール管理';
$lang->tree->manageCaselibChild = 'ケースライブラリサブモジュール管理';
$lang->tree->manageTaskChild = "{$lang->projectCommon}サブモジュール管理";
$lang->tree->syncFromProduct = 'モジュールコピー';
$lang->tree->dragAndSort = 'ドラッグしてソート';
$lang->tree->sort = 'ソート';
$lang->tree->addChild = 'サブモジュール追加';
$lang->tree->confirmDelete = '当該モジュールとサブモジュールが削除され、削除してよろしいですか？';
$lang->tree->confirmDeleteLine = '当該プロダクトラインを削除してよろしいですか？';
$lang->tree->confirmRoot = "モジュールの所属{$lang->productCommon}を変更したら、このモジュールと関連している{$lang->storyCommon}、バグ、ケースの所属{$lang->productCommon}、{$lang->projectCommon}と{$lang->productCommon}の関連関係も変更します。この操作はリスクがありますので、慎重に操作してください。変更してもよろしいですか？";
$lang->tree->confirmRoot4Doc    = "修改所属文档库，会同时修改该分类下文档的关联关系。该操作比较危险，请谨慎操作。是否确认修改？";
$lang->tree->successSave = '保存成功';
$lang->tree->successFixed = 'データ修正成功！';
$lang->tree->repeatName = 'モジュール名“%s”が存在しています！';
$lang->tree->shouldNotBlank     = '模块名不能为空格！';

$lang->tree->module = 'モジュール';
$lang->tree->name = 'モジュール名';
$lang->tree->line = 'プロダクトライン名';
$lang->tree->cate = '分類名';
$lang->tree->root = '所属ルート';
$lang->tree->branch = 'プラットフォーム/ブランチ';
$lang->tree->path = 'パス';
$lang->tree->type = 'タイプ';
$lang->tree->parent = '上級モジュール';
$lang->tree->parentCate = '上級分類';
$lang->tree->child = 'サブモジュール';
$lang->tree->lineChild = 'サブプロダクトライン';
$lang->tree->owner = '責任者';
$lang->tree->order = 'ソート';
$lang->tree->short = '略称';
$lang->tree->all = '全モジュール';
$lang->tree->projectDoc = "{$lang->projectCommon}ドキュメント";
$lang->tree->product = "所属{$lang->productCommon}";
