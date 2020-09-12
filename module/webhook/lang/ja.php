<?php
$lang->webhook->common = 'Webhook';
$lang->webhook->list = 'Webhookリスト';
$lang->webhook->api = 'インタフェース';
$lang->webhook->entry = 'アプリケーション';
$lang->webhook->log = 'ログ';
$lang->webhook->bind       = '绑定用户';
$lang->webhook->chooseDept = '选择同步部门';
$lang->webhook->assigned = 'にアサイン';
$lang->webhook->setting = '設定';

$lang->webhook->browse = 'Webhook閲覧';
$lang->webhook->create = 'Webhook追加';
$lang->webhook->edit = 'Webhook編集';
$lang->webhook->delete = 'Webhook削除';

$lang->webhook->id = 'ID';
$lang->webhook->type = 'タイプ';
$lang->webhook->name = '名称';
$lang->webhook->url = 'Hookアドレス';
$lang->webhook->domain = '禅道ドメイン名';
$lang->webhook->contentType = '内容タイプ';
$lang->webhook->sendType = '発信方法';
$lang->webhook->secret      = '密钥';
$lang->webhook->product = "関連{$lang->productCommon}";
$lang->webhook->project = "関連{$lang->projectCommon}";
$lang->webhook->params = 'パラメータ';
$lang->webhook->action = '触発動作';
$lang->webhook->desc = '説明';
$lang->webhook->createdBy = '誰より作成';
$lang->webhook->createdDate = '作成時間';
$lang->webhook->editedby = '最終編集';
$lang->webhook->editedDate = '編集時間';
$lang->webhook->date = '発信時間';
$lang->webhook->data = 'データ';
$lang->webhook->result = '結果';

$lang->webhook->typeList[''] = '';
$lang->webhook->typeList['dinggroup'] = 'DingTalk';
$lang->webhook->typeList['dinguser']    = '钉钉工作消息通知';
$lang->webhook->typeList['wechatgroup'] = '企业微信群机器人';
$lang->webhook->typeList['wechatuser']  = '企业微信应用消息';
$lang->webhook->typeList['default'] = 'その他';

$lang->webhook->sendTypeList['sync'] = '同期';
$lang->webhook->sendTypeList['async'] = '非同期';

$lang->webhook->dingAgentId     = '钉钉AgentId';
$lang->webhook->dingAppKey      = '钉钉AppKey';
$lang->webhook->dingAppSecret   = '钉钉AppSecret';
$lang->webhook->dingUserid      = '钉钉用户';
$lang->webhook->dingBindStatus  = '钉钉绑定状态';
$lang->webhook->chooseDeptAgain = '重选部门';

$lang->webhook->wechatCorpId     = '企业ID';
$lang->webhook->wechatCorpSecret = '应用的凭证密钥';
$lang->webhook->wechatAgentId    = '企业应用的ID';
$lang->webhook->wechatUserid     = '微信用户';
$lang->webhook->wechatBindStatus = '微信绑定状态';

$lang->webhook->zentaoUser  = '禅道用户';

$lang->webhook->dingBindStatusList['0'] = '未绑定';
$lang->webhook->dingBindStatusList['1'] = '已绑定';

$lang->webhook->paramsList['objectType'] = 'オブジェクトタイプ';
$lang->webhook->paramsList['objectID'] = 'オブジェクトID';
$lang->webhook->paramsList['product'] = "所属{$lang->productCommon}";
$lang->webhook->paramsList['project'] = "所属{$lang->projectCommon}";
$lang->webhook->paramsList['action'] = '動作';
$lang->webhook->paramsList['actor'] = '操作者';
$lang->webhook->paramsList['date'] = '操作日付';
$lang->webhook->paramsList['comment'] = '付記';
$lang->webhook->paramsList['text'] = '操作内容';

$lang->webhook->confirmDelete = '当該webhookを削除してもよろしいですか？';

$lang->webhook->trimWords = '済み';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async = '非同期の場合プランタスクを開いてください';
$lang->webhook->note->bind    = '只有[钉钉/微信]工作通知类型才需要绑定用户。';
$lang->webhook->note->product = "この項目が空の場合、{$lang->productCommon}のすべての動作がフックが触発されます。そうでない場合、{$lang->productCommon}に関連付けられた動作のみ触発されます。";
$lang->webhook->note->project = "この項目が空の場合、{$lang->projectCommon}のすべての動作がフックが触発されます。そうでない場合、{$lang->projectCommon}に関連付けられた動作のみ触発されます。";

$lang->webhook->note->dingHelp   = " <a href='http://www.zentao.net/book/zentaopmshelp/358.html' target='_blank'><i class='icon-help'></i></a>";
$lang->webhook->note->wechatHelp = " <a href='http://www.zentao.net/book/zentaopmshelp/367.html' target='_blank'><i class='icon-help'></i></a>";

$lang->webhook->note->typeList['bearychat'] = 'BearyChatで禅道ロボットを追加、ここにwebhookを記入してください。';
$lang->webhook->note->typeList['dingding'] = 'DingTalkで禅道ロボットを追加、ここにwebhookを記入してください。';
$lang->webhook->note->typeList['weixin']    = '请在企业微信中添加一个自定义机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['default'] = '第三者システムからwebhookを取得、ここに記入してください。';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl = 'php-curl拡張機能をロードする必要があります。';
$lang->webhook->error->noDept = '没有选择部门，请先选择同步部门。';
