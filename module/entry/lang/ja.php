<?php
$lang->entry->common = 'アプリケーション';
$lang->entry->list = 'アプリケーションリスト';
$lang->entry->api = 'インタフェース';
$lang->entry->webhook = 'Webhook';
$lang->entry->log = 'ログ';
$lang->entry->setting = '設定';

$lang->entry->browse = 'アプリケーション閲覧';
$lang->entry->create = 'アプリケーション新規';
$lang->entry->edit = 'アプリケーション編集';
$lang->entry->delete = 'アプリケーション削除';
$lang->entry->createKey = '新しいキー生成';

$lang->entry->id = 'ID';
$lang->entry->name = '名';
$lang->entry->account = 'アカウント';
$lang->entry->code = 'コード';
$lang->entry->freePasswd = 'パスワードなしでログイン';
$lang->entry->key = 'キー';
$lang->entry->ip = 'IP';
$lang->entry->desc = '説明';
$lang->entry->createdBy = '作成者';
$lang->entry->createdDate = '作成時間';
$lang->entry->editedby = '最終編集';
$lang->entry->editedDate = '編集時間';
$lang->entry->date = '申請時間';
$lang->entry->url = '申請アドレス';

$lang->entry->confirmDelete = '当該アプリケーションを削除してもよろしいですか。';
$lang->entry->help = '使用説明';
$lang->entry->notify = 'メッセージ通知';

$lang->entry->helpLink = 'https://www.zentao.net/book/zentaopmshelp/integration-287.html';
$lang->entry->notifyLink = 'https://www.zentao.net/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name = 'ライセンスのアプリケーション名';
$lang->entry->note->code = 'ライセンスのアプリケーションコードはアルファベットまたは数字の組み合わせであります';
$lang->entry->note->ip = 'APIアクセス許可のアプリケーションIP、複数があれば、コンマで区切ってください。サポートされるIPセグメント、例：192.168.1.*';
$lang->entry->note->allIP = '制限なし';
$lang->entry->note->account = 'ライセンスのアプリケーションアカウント';

$lang->entry->freePasswdList[1] = 'オープン';
$lang->entry->freePasswdList[0] = 'クローズ';

$lang->entry->errmsg['PARAM_CODE_MISSING'] = 'codeパラメータがありません';
$lang->entry->errmsg['PARAM_TOKEN_MISSING'] = 'tokenパラメータがありません';
$lang->entry->errmsg['SESSION_CODE_MISSING'] = 'session codeがありません';
$lang->entry->errmsg['EMPTY_KEY'] = 'アプリケーションはキーを設定しません';
$lang->entry->errmsg['INVALID_TOKEN'] = '無効なtokenパラメータ';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'session検証に失敗しました';
$lang->entry->errmsg['IP_DENIED'] = '当該IPのアクセスが制限されています';
$lang->entry->errmsg['ACCOUNT_UNBOUND'] = '未バインドユーザ';
$lang->entry->errmsg['INVALID_ACCOUNT'] = 'ユーザが存在しません';
$lang->entry->errmsg['EMPTY_ENTRY'] = 'アプリケーションが存在しません';
$lang->entry->errmsg['CALLED_TIME'] = 'Tokenの有効期限が切れました';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = '错误的时间戳。';
