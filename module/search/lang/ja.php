<?php
/**
 * The search module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wangguannan
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->search->common = '検索';
$lang->search->reset = 'リセット';
$lang->search->saveQuery = '保存';
$lang->search->myQuery = 'マイ検索';
$lang->search->group1 = '第一組';
$lang->search->group2 = '第二組';
$lang->search->buildForm = 'リスト検索';
$lang->search->buildQuery = '検索実行';
$lang->search->savedQuery = '保存した検索条件';
$lang->search->deleteQuery = '検索削除';
$lang->search->setQueryTitle = '検索見出しを記入してください（保存の前に検索してください）：';
$lang->search->select = $lang->storyCommon . '/タスクスクリーニング';
$lang->search->me = '自分';
$lang->search->noQuery = '検索はまだ保存していません';
$lang->search->onMenuBar = 'メニューバーで表示する';
$lang->search->custom = 'カスタマイズ';

$lang->search->account = 'ユーザ名';
$lang->search->module = 'モジュール';
$lang->search->title = '名称';
$lang->search->form = 'リストフィールド';
$lang->search->sql = 'SQL条件';
$lang->search->shortcut = $lang->search->onMenuBar;

$lang->search->operators['='] = '=';
$lang->search->operators['!='] = '!=';
$lang->search->operators['>'] = '>';
$lang->search->operators['>='] = '>=';
$lang->search->operators['<'] = '<';
$lang->search->operators['<='] = '<=';
$lang->search->operators['include'] = '含む';
$lang->search->operators['between'] = 'の間';
$lang->search->operators['notinclude'] = '含まない';
$lang->search->operators['belong'] = '属する';

$lang->search->andor['and'] = 'かつ';
$lang->search->andor['or'] = 'または';

$lang->search->null = '空';

$lang->userquery        = new stdclass();
$lang->userquery->title = '标题';
