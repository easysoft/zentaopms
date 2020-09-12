<?php
/**
 * The group module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin wuhongjie wangguannan
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->group->common = '権限グルーピング';
$lang->group->browse = 'グループ閲覧';
$lang->group->create = 'グループ新規作成';
$lang->group->edit = 'グループ編集';
$lang->group->copy = 'グループコピー';
$lang->group->delete = 'グループ削除';
$lang->group->manageView = 'ビュー管理';
$lang->group->managePriv = '権限管理';
$lang->group->managePrivByGroup = '権限管理';
$lang->group->managePrivByModule = 'モジュール毎に権限を割り当て';
$lang->group->byModuleTips = '<span class="tips">（ShiftキーまたはCtrlキーを押しながら複数選択可能）</span>';
$lang->group->manageMember = 'メンバー管理';
$lang->group->confirmDelete = '当該ユーザグループを削除してもよろしいですか？';
$lang->group->successSaved = '保存成功';
$lang->group->errorNotSaved = '保存していません。権限データが選択されたことを確認してください。';
$lang->group->viewList = 'アクセス可能ビュー';
$lang->group->productList = 'アクセス可能' . $lang->productCommon;
$lang->group->projectList = 'アクセス可能' . $lang->projectCommon;
$lang->group->dynamic = '表示可能活動';
$lang->group->noticeVisit = '空欄にアクセス制限がありません';
$lang->group->noneProduct        = "暂时没有{$lang->productCommon}";
$lang->group->noneProject        = "暂时没有{$lang->projectCommon}";

$lang->group->id = '番号';
$lang->group->name = 'グループ名';
$lang->group->desc = 'グループ説明';
$lang->group->role = '役割';
$lang->group->acl = '権限';
$lang->group->users = 'ユーザリスト';
$lang->group->module = 'モジュール';
$lang->group->method = '方法';
$lang->group->priv = '権限';
$lang->group->option = 'オプション';
$lang->group->inside = 'グループ内';
$lang->group->outside = 'グループ外';
$lang->group->other = '他のモジュール';
$lang->group->all = '全権限';

$lang->group->copyOptions['copyPriv'] = 'コピー権限';
$lang->group->copyOptions['copyUser'] = 'ユーザコピー';

$lang->group->versions[''] = '更新履歴';
$lang->group->versions['12_3']      = 'ZenTao12.3';
$lang->group->versions['11_6_2']    = 'ZenTao11.6.2';
$lang->group->versions['10_6'] = 'ZenTao10.6';
$lang->group->versions['10_1'] = 'ZenTao10.1';
$lang->group->versions['10_0_alpha'] = 'ZenTao10.0.alpha';
$lang->group->versions['9_8'] = 'ZenTao9.8';
$lang->group->versions['9_6'] = 'ZenTao9.6';
$lang->group->versions['9_5'] = 'ZenTao9.5';
$lang->group->versions['9_2'] = 'ZenTao9.2';
$lang->group->versions['9_1'] = 'ZenTao9.1';
$lang->group->versions['9_0'] = 'ZenTao9.0';
$lang->group->versions['8_4'] = 'ZenTao8.4';
$lang->group->versions['8_3'] = 'ZenTao8.3';
$lang->group->versions['8_2_beta'] = 'ZenTao8.2.beta';
$lang->group->versions['8_0_1'] = 'ZenTao8.0.1';
$lang->group->versions['8_0'] = 'ZenTao8.0';
$lang->group->versions['7_4_beta'] = 'ZenTao7.4.beta';
$lang->group->versions['7_3'] = 'ZenTao7.3';
$lang->group->versions['7_2'] = 'ZenTao7.2';
$lang->group->versions['7_1'] = 'ZenTao7.1';
$lang->group->versions['6_4'] = 'ZenTao6.4';
$lang->group->versions['6_3'] = 'ZenTao6.3';
$lang->group->versions['6_2'] = 'ZenTao6.2';
$lang->group->versions['6_1'] = 'ZenTao6.1';
$lang->group->versions['5_3'] = 'ZenTao5.3';
$lang->group->versions['5_1'] = 'ZenTao5.1';
$lang->group->versions['5_0_beta2'] = 'ZenTao5.0.beta2';
$lang->group->versions['5_0_beta1'] = 'ZenTao5.0.beta1';
$lang->group->versions['4_3_beta'] = 'ZenTao4.3.beta';
$lang->group->versions['4_2_beta'] = 'ZenTao4.2.beta';
$lang->group->versions['4_1'] = 'ZenTao4.1';
$lang->group->versions['4_0_1'] = 'ZenTao4.0.1';
$lang->group->versions['4_0'] = 'ZenTao4.0';
$lang->group->versions['4_0_beta2'] = 'ZenTao4.0.beta2';
$lang->group->versions['4_0_beta1'] = 'ZenTao4.0.beta1';
$lang->group->versions['3_3'] = 'ZenTao3.3';
$lang->group->versions['3_2_1'] = 'ZenTao3.2.1';
$lang->group->versions['3_2'] = 'ZenTao3.2';
$lang->group->versions['3_1'] = 'ZenTao3.1';
$lang->group->versions['3_0_beta2'] = 'ZenTao3.0.beta2';
$lang->group->versions['3_0_beta1'] = 'ZenTao3.0.beta1';
$lang->group->versions['2_4'] = 'ZenTao2.4';
$lang->group->versions['2_3'] = 'ZenTao2.3';
$lang->group->versions['2_2'] = 'ZenTao2.2';
$lang->group->versions['2_1'] = 'ZenTao2.1';
$lang->group->versions['2_0'] = 'ZenTao2.0';
$lang->group->versions['1_5'] = 'ZenTao1.5';
$lang->group->versions['1_4'] = 'ZenTao1.4';
$lang->group->versions['1_3'] = 'ZenTao1.3';
$lang->group->versions['1_2'] = 'ZenTao1.2';
$lang->group->versions['1_1'] = 'ZenTao1.1';
$lang->group->versions['1_0_1'] = 'ZenTao1.0.1';

include (dirname(__FILE__) . '/resource.php');
