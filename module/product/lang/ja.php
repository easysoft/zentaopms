<?php
/**
 * The product module Japanese file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: en.php 993 2010-08-02 10:20:01Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->product->common = '製品';
$lang->product->index  = "インデックス";
$lang->product->browse = "ブラウズ";
$lang->product->view   = "情報";
$lang->product->edit   = "Edit";
$lang->product->create = "作成";
$lang->product->read   = "Info";
$lang->product->delete = "削除";

$lang->product->roadmap   = 'ロードマップ';
$lang->product->doc       = 'コックタイン';

$lang->product->selectProduct   = "製品を選択";
$lang->product->saveButton      = "保存（S）";
$lang->product->confirmDelete   = "あなたはこの製品を削除してよろしいですか？";
$lang->product->ajaxGetProjects = "APIの：製品のプロジェクト";
$lang->product->ajaxGetPlans    = "APIの：製品の計画";

$lang->product->errorFormat    = 'エラーの形式です。';
$lang->product->errorEmptyName = '名は空にすることはできません。';
$lang->product->errorEmptyCode = 'コードを空にすることはできません';
$lang->product->errorNoProduct = 'No product in system yet.';
$lang->product->accessDenied   = 'アクセスは、この製品にdenined。';

$lang->product->id        = 'IDは';
$lang->product->company   = '会社';
$lang->product->name      = '名';
$lang->product->code      = 'コード';
$lang->product->order     = '注文';
$lang->product->status    = 'ステータス';
$lang->product->desc      = '降順';
$lang->product->bugOwner  = 'バグの所有者';
$lang->product->acl       = 'アクセス制限';
$lang->product->whitelist = 'ホワイトリスト';

$lang->product->moduleStory = 'モジュールで';
$lang->product->searchStory = '検索で';
$lang->product->allStory    = 'すべての物語';

$lang->product->statusList['']       = '';
$lang->product->statusList['normal'] = 'ノーマル';
$lang->product->statusList['closed'] = 'クローズド';

$lang->product->aclList['open']    = 'デフォルトでは（こと、製品モジュールprividge）この商品を訪れることができます';
$lang->product->aclList['private'] = '（のみプライベートプロジェクトチームのメンバー訪れることができます）';
$lang->product->aclList['custom']  = 'ホワイト（プロジェクトチームのメンバー、誰whilelistグループ訪れることができますに属している）';
