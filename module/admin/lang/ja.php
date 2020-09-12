<?php
/**
 * The admin module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wuhongjie
 * @package     admin
 * @version     $Id: zh-cn.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->admin->common = '設定';
$lang->admin->index = 'バックエンド管理トップページ';
$lang->admin->checkDB = 'データベース検査';
$lang->admin->sso = '然之インテグレーション';
$lang->admin->ssoAction = '然之統合';
$lang->admin->safeIndex = 'セキュリティ';
$lang->admin->checkWeak = '弱パスワード検査';
$lang->admin->certifyMobile = '携帯認証';
$lang->admin->certifyEmail = 'メールアドレス認証';
$lang->admin->ztCompany = '企業認証';
$lang->admin->captcha = '検証コード';
$lang->admin->getCaptcha = '検証コード取得';

$lang->admin->api = 'インターフェイス';
$lang->admin->log = '日報';
$lang->admin->setting = '設定';
$lang->admin->days = '日報保存日数';

$lang->admin->info = new stdclass();
$lang->admin->info->version = '現在のシステムのバージョンは%sです。';
$lang->admin->info->links = '以下のリンクにアクセスできます：';
$lang->admin->info->account = 'ご禅道コミュニティのアカウントは%sです。';
$lang->admin->info->log = '保存日数を越えるログは削除され、プランタスクを開始することが必要となります。';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = 'お知らせ：まだ禅道コミュニティ(www.zentao.jp)で登録しない、速やかに最新情報が取れるために、%s登録しましょう。';
$lang->admin->notice->ignore = '知らせ情報不要';
$lang->admin->notice->int = '『%s』が正整数べきです。';

$lang->admin->register = new stdclass();
$lang->admin->register->common = '新しいアカウントをサインアップしてバインド';
$lang->admin->register->caption = '禅道コミュニティ登録';
$lang->admin->register->click = 'ここをクリック';
$lang->admin->register->lblAccount = 'ユーザ名を入力してください：アルファベットと数字の組み合わせ、3文字以上。';
$lang->admin->register->lblPasswd = 'パスワードを入力してください：数字とアルファベットの組み合わせ、6文字以上。';
$lang->admin->register->submit = '登録';
$lang->admin->register->bind = '既存のアカウントをバインド';
$lang->admin->register->success = 'アカウント登録成功';

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'コミュニティのアカウントと関連付け';
$lang->admin->bind->success = 'アカウントと関連付けた';

$lang->admin->safe = new stdclass();
$lang->admin->safe->common = 'セキュリティ策略';
$lang->admin->safe->set = 'パスワードセキュリティ設定';
$lang->admin->safe->password = 'パスワードセキュリティ';
$lang->admin->safe->weak = '常用の弱パスワード';
$lang->admin->safe->reason = 'タイプ';
$lang->admin->safe->checkWeak = '弱パスワードスキャン';
$lang->admin->safe->changeWeak = '弱パスワード更新';
$lang->admin->safe->modifyPasswordFirstLogin = '初ログインしてからパスワードを更新してください';

$lang->admin->safe->modeList[0] = '検査しない';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '強';

$lang->admin->safe->modeRuleList[1] = '6文字以上、アルファベット（大文字と小文字）と数字が含まれています。';
$lang->admin->safe->modeRuleList[2] = '10文字以上、アルファベット（大文字と小文字）、数字、特殊文字が含まれています。';

$lang->admin->safe->reasonList['weak'] = '常用の弱パスワード';
$lang->admin->safe->reasonList['account'] = 'アカウントと同じ';
$lang->admin->safe->reasonList['mobile'] = '携帯番号と同じ';
$lang->admin->safe->reasonList['phone'] = '電話番号と同じ';
$lang->admin->safe->reasonList['birthday'] = '生年月日と同じ';

$lang->admin->safe->modifyPasswordList[1] = '更新してください';
$lang->admin->safe->modifyPasswordList[0] = '強制しない';

$lang->admin->safe->noticeMode = 'システムはログインの時、ユーザを作成と更新する時、<br />パスワードを更新する時に、ユーザパスワードを検査します。';
$lang->admin->safe->noticeStrong = 'パスワードの長さがもっと長ければ、<br />アルファベット大文字、数字、特殊文字がもっと多ければ、パスワードのアルファベットの不重複が多ければ、セキュリティが強くなります！';
