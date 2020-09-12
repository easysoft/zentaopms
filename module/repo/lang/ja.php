<?php
$lang->repo->common = 'ソース';
$lang->repo->browse = '一覧';
$lang->repo->viewRevision    = '查看修订';
$lang->repo->create = 'バージョンライブラリ作成';
$lang->repo->createAction    = '创建版本库';
$lang->repo->maintain        = '版本库列表';
$lang->repo->edit            = '编辑';
$lang->repo->editAction      = '编辑版本库';
$lang->repo->delete = '削除';
$lang->repo->showSyncCommit = 'シンクロ進捗表示';
$lang->repo->ajaxSyncCommit = 'インタフェース：コメントAJAXシンクロ';
$lang->repo->setRules        = '指令配置';
$lang->repo->download = 'ダウンロード';
$lang->repo->downloadDiff = 'Diffダウンロード';
$lang->repo->diffAction = '比較';
$lang->repo->revisionAction = '詳細';
$lang->repo->blameAction = '版本追溯';
$lang->repo->addBug = '新規';
$lang->repo->editBug = '編集';
$lang->repo->deleteBug = '削除';
$lang->repo->addComment = 'コメント追加';
$lang->repo->editComment = '編集';
$lang->repo->deleteComment = '削除';

$lang->repo->submit = '提出';
$lang->repo->cancel = 'キャンセル';
$lang->repo->addComment = 'コメント追加';

$lang->repo->product = $lang->productCommon;
$lang->repo->module = 'モジュール';
$lang->repo->project = $lang->projectCommon;
$lang->repo->type = 'タイプ';
$lang->repo->assign = 'アサイン';
$lang->repo->title = 'タイトル';
$lang->repo->detile = '詳細';
$lang->repo->lines = 'コード';
$lang->repo->line = '行';
$lang->repo->expand = 'クリックして展開';
$lang->repo->collapse = 'クリックして折りたたむ';

$lang->repo->id = '番号';
$lang->repo->SCM = 'タイプ';
$lang->repo->name = '名称';
$lang->repo->path = 'アドレス';
$lang->repo->prefix = 'アドレス拡張';
$lang->repo->config = '配置ディレクトリー';
$lang->repo->desc      = '描述';
$lang->repo->account = 'ユーザ名';
$lang->repo->password = 'パスワード';
$lang->repo->encoding = 'エンコード';
$lang->repo->client = 'クライアント';
$lang->repo->size = 'サイズ';
$lang->repo->revision = 'バージョン';
$lang->repo->revisionA = 'バージョン';
$lang->repo->revisions = 'バージョン';
$lang->repo->time = '提出時間';
$lang->repo->committer = '作成者';
$lang->repo->commits = '提出数';
$lang->repo->synced = 'シンクロ初期化';
$lang->repo->lastSync = '最終シンクロ時間';
$lang->repo->deleted = '削除済み';
$lang->repo->commit = '提出';
$lang->repo->comment = 'コメント';
$lang->repo->view = 'ファイル表示';
$lang->repo->viewA = '表示';
$lang->repo->log = 'バージョン履歴';
$lang->repo->blame = '遡及';
$lang->repo->date = '日付';
$lang->repo->diff = '区別';
$lang->repo->diffAB = '比較';
$lang->repo->diffAll = '全部比較';
$lang->repo->viewDiff = '差異表示';
$lang->repo->allLog = '全てバージョン';
$lang->repo->location = '場所';
$lang->repo->file = 'ファイル';
$lang->repo->action = '操作';
$lang->repo->code = 'コード';
$lang->repo->review = '承認';
$lang->repo->acl = '権限';
$lang->repo->group = 'グルーピング';
$lang->repo->user = 'ユーザ';
$lang->repo->info = 'バージョン情報';

$lang->repo->title = 'タイトル';
$lang->repo->status = 'ステータス';
$lang->repo->openedBy = '作成者';
$lang->repo->assignedTo = 'アサイン';
$lang->repo->openedDate = '作成日付';

$lang->repo->latestRevision = '最新更新バージョン';
$lang->repo->actionInfo = '%sが%s追加';
$lang->repo->changes = 'レコード更新';
$lang->repo->reviewLocation = '%s@%s、%s行 - %s行';
$lang->repo->commentEdit = '<i class="icon-pencil"></i>';
$lang->repo->commentDelete = '<i class="icon-remove"></i>';
$lang->repo->allChanges = '他の変更';
$lang->repo->commitTitle = '第%s回提出';
$lang->repo->mark           = "开始标记";
$lang->repo->split          = "多ID间隔";

$lang->repo->objectRule   = '对象匹配规则';
$lang->repo->objectIdRule = '对象ID匹配规则';
$lang->repo->actionRule   = '动作匹配规则';
$lang->repo->manHourRule  = '工时匹配规则';
$lang->repo->ruleUnit     = "单位";
$lang->repo->ruleSplit    = "多关键字用';'分割，如：任务多关键字： Task;任务";

$lang->repo->viewDiffList['inline'] = '直列';
$lang->repo->viewDiffList['appose'] = '並列';

$lang->repo->encryptList['plain']  = '不加密';
$lang->repo->encryptList['base64'] = 'BASE64';

$lang->repo->logStyles['A'] = '追加';
$lang->repo->logStyles['M'] = '更新';
$lang->repo->logStyles['D'] = '削除';

$lang->repo->encodingList['utf_8'] = 'UTF-8';
$lang->repo->encodingList['gbk'] = 'GBK';

$lang->repo->scmList['Git'] = 'Git';
$lang->repo->scmList['Subversion'] = 'Subversion';

$lang->repo->notice = new stdclass();
$lang->repo->notice->syncing = '平行中、お待ちください...';
$lang->repo->notice->syncComplete = '平行済み、ジャンプしています...';
$lang->repo->notice->syncedCount = '平行したレコード件数';
$lang->repo->notice->delete = '当該バージョンライブラリを削除してもよろしいですか？';
$lang->repo->notice->successDelete = 'バージョンライブラリを削除しました';
$lang->repo->notice->commentContent = '内容を入力してください';
$lang->repo->notice->deleteBug = '当該バグを削除してもよろしいですか？';
$lang->repo->notice->deleteComment = '当該コメントを削除してもよろしいですか？';
$lang->repo->notice->lastSyncTime = '最終更新は：';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "注释示例";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error = new stdclass();
$lang->repo->error->useless = 'サーバはexec、shell_execメソッドを無効にしますので、この機能は使えません';
$lang->repo->error->connect = 'バージョンライブラリとの接続に失敗しました。正しいユーザ名、パスワードとバージョンライブラリアドレスを入力してください！';
$lang->repo->error->version = 'httpsとsvn契約は1.8バージョン以上のクライアントが必要です。最新バージョンにアップグレードしてください！詳しいのはhttp://subversion.apache.org/にアクセスしてください';
$lang->repo->error->path = 'バージョンライブラリアドレスは直接ファイルパスを記入してください。例：/home/test。';
$lang->repo->error->cmd = 'クライアントエラー！';
$lang->repo->error->diff = '二つのバージョンを選んでください';
$lang->repo->error->safe          = '因为安全原因，需要检测客户端版本，请将版本号写入文件 %s <br /> 可以执行命令：%s';
$lang->repo->error->product = "{$lang->productCommon}を選んでください！";
$lang->repo->error->commentText = 'レビュー内容を記入してください';
$lang->repo->error->comment = '内容を記入してください';
$lang->repo->error->title = 'タイトルを記入してください';
$lang->repo->error->accessDenied = '当該バージョンライブラリにアクセス拒否されています';
$lang->repo->error->noFound = 'アクセスのバージョンライブラリが存在していません';
$lang->repo->error->noFile = 'ディレクトリー %s が存在していません';
$lang->repo->error->noPriv = 'プログラムはディレクトリー %s に切り替える権限がありません';
$lang->repo->error->output = 'コマンドを実行します：%snエラー結果(%s)： %sn';
$lang->repo->error->clientVersion = "客户端版本过低，请升级或更换SVN客户端";
$lang->repo->error->encoding      = "编码可能错误，请更换编码重试。";

$lang->repo->syncTips      = '请参照<a target="_blank" href="https://www.zentao.net/book/zentaopmshelp/207.html">这里</a>，设置版本库定时同步。';
$lang->repo->encodingsTips = "提交日志的编码，可以用逗号连接起来的多个，比如utf-8。";

$lang->repo->example = new stdclass();
$lang->repo->example->client      = new stdclass();
$lang->repo->example->path        = new stdclass();
$lang->repo->example->client->git = "例如：/usr/bin/git";
$lang->repo->example->client->svn = "例如：/usr/bin/svn";
$lang->repo->example->path->git   = "例如：/home/user/myproject";
$lang->repo->example->path->svn   = "例如：http://example.googlecode.com/svn/trunk/myproject";
$lang->repo->example->config = 'httpｓは配置ディレクトリーの位置が必要です。config-dirオプションで配置ディレクトリーを作成します';
$lang->repo->example->encoding = 'バージョンライブラリのファイルのエンコーディングを記入してください';

$lang->repo->typeList['standard'] = '標準';
$lang->repo->typeList['performance'] = 'パフォーマンス';
$lang->repo->typeList['security'] = 'セキュリティ';
$lang->repo->typeList['redundancy'] = 'リダンダンシー';
$lang->repo->typeList['logicError'] = 'ロジックエラー';
