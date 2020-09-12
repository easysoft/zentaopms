<?php
/**
 * The extension module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      wuhongjie
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common = 'プラグイン管理';
$lang->extension->browse = 'プラグイン閲覧';
$lang->extension->install = 'プラグインインストール';
$lang->extension->installAuto = '自動インストール';
$lang->extension->installForce = '強制インストール';
$lang->extension->uninstall = 'アンインストール';
$lang->extension->uninstallAction = '卸载插件';
$lang->extension->activate = 'アクティベート';
$lang->extension->activateAction = '激活插件';
$lang->extension->deactivate = '無効';
$lang->extension->deactivateAction = '禁用插件';
$lang->extension->obtain = 'プラグイン取得';
$lang->extension->view = '詳細';
$lang->extension->downloadAB = 'ダウンロード';
$lang->extension->upload = 'ローカルインストール';
$lang->extension->erase = 'クリア';
$lang->extension->eraseAction = '清除插件';
$lang->extension->upgrade = 'プラグインアップグレード';
$lang->extension->agreeLicense = '当該ライセンスに同意する';

$lang->extension->structure = 'ディレクトリー仕組み';
$lang->extension->structureAction = 'ディレクトリー仕組み';
$lang->extension->installed = 'インストール済み';
$lang->extension->deactivated = '無効された';
$lang->extension->available = 'ダウンロード済み';

$lang->extension->name = 'プラグイン名称';
$lang->extension->code = 'コードネーム';
$lang->extension->desc = '説明';
$lang->extension->type = 'タイプ';
$lang->extension->dirs = 'インストールディレクトリー';
$lang->extension->files = 'インストールファイル';
$lang->extension->status = 'ステータス';
$lang->extension->version = 'バージョン';
$lang->extension->latest = '<small>最新バージョン<strong><a href="%s" target="_blank" class="extension">%s</a></strong>、禅道との互換性を持ち<a href="https://api.zentao.net/goto.php?item=latest" target="_blank" class="alert-link"><strong>%s</strong></a></small>';
$lang->extension->author = '作成者';
$lang->extension->license = 'ライセンス';
$lang->extension->site = '公式サイト';
$lang->extension->downloads = 'ダウンロード数';
$lang->extension->compatible = '互換性';
$lang->extension->grade = '採点';
$lang->extension->depends = '依存';
$lang->extension->expireDate = '期限切れ';
$lang->extension->zentaoCompatible = '適用バージョン';
$lang->extension->installedTime = 'インストール時間';

$lang->extension->publicList[0] = '手動ダウンロード';
$lang->extension->publicList[1] = '直接ダウンロード';

$lang->extension->compatibleList[0] = '未知';
$lang->extension->compatibleList[1] = '互換';

$lang->extension->obtainOfficial[0] = '第三者';
$lang->extension->obtainOfficial[1] = 'オフィシャル';

$lang->extension->byDownloads = '一番多いダウンロード';
$lang->extension->byAddedTime = '最新追加';
$lang->extension->byUpdatedTime = '最近更新';
$lang->extension->bySearch = '検索';
$lang->extension->byCategory = '分類閲覧';

$lang->extension->installFailed = '%sに失敗しました、エラー原因は:';
$lang->extension->uninstallFailed = 'アンインストールに失敗しました、エラー原因は：';
$lang->extension->confirmUninstall = 'プラグインをアンインストールしたら関連データベースが削除または更新されるので、続いてアンインストールしてもよろしいですか？';
$lang->extension->installFinished = 'おめでとうございます、プラグインは順調に％ｓに成功しました！';
$lang->extension->refreshPage = 'ページ更新';
$lang->extension->uninstallFinished = 'プラグインのアンインストールに成功しました';
$lang->extension->deactivateFinished = 'プラグインはすでに無効になりました';
$lang->extension->activateFinished = 'プラグインのアクティベーションに成功しました';
$lang->extension->eraseFinished = 'プラグインの削除に成功しました';
$lang->extension->unremovedFiles = '少しファイルとディレクトリーは削除されません、手動で削除してください';
$lang->extension->executeCommands = '<h3>下記のコマンドを実行して問題を解決します：</h3>';
$lang->extension->successDownloadedPackage = 'プラグインのダウンロードに成功しました';
$lang->extension->successCopiedFiles = 'ファイルコピーに成功しました';
$lang->extension->successInstallDB = 'データベースのインストールに成功しました';
$lang->extension->viewInstalled = 'インストールしたプラグインを表示します';
$lang->extension->viewAvailable = 'インストールできるプラグインを表示します';
$lang->extension->viewDeactivated = '無効されたプラグインを表示します';
$lang->extension->backDBFile = 'プラグインの関連データは%sファイルにバックアップしました！';
$lang->extension->noticeOkFile = '<h5>セキュリティのため、システムはあなたの管理者情報を確認します</h5>
    <h5>请登录禅道所在的服务器，创建%s文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>'; 

$lang->extension->upgradeExt = '升级';
$lang->extension->installExt = 'インストール';
$lang->extension->upgradeVersion = '（%sから%sにアップグレードします）';

$lang->extension->waring = '注意';

$lang->extension->errorOccurs = 'エラー：';
$lang->extension->errorGetModules = 'www.zentao.netからプラグイン分類の取得に失敗しました。ネットワーク接続の問題かもしれません、チェックしてからページを更新してください。';
$lang->extension->errorGetExtensions = 'www.zentao.netからプラグインの取得に失敗しました。ネットワーク接続の問題かもしれません、<a href="https://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.net</a> で手動でプラグインをダウンロードして、アップロードしてインストールしてください。';
$lang->extension->errorDownloadPathNotFound = 'プラグインのメモリアドレス<strong>%s</strong>が存在していません。<br />直すにはlinuxで下記のコマンドを実行してください：<strong>mkdir -p %s</strong>。';
$lang->extension->errorDownloadPathNotWritable = 'プラグインのメモリアドレス<strong>%s</strong>が編集できません。<br />直すにはlinuxで下記のコマンドを実行してください：<strong>sudo chmod 777 %s</strong>。';
$lang->extension->errorPackageFileExists = 'メモリアドレスは<strong>%s</strong>の添付ファイルがありました。<h5>改めて%s、<a href="%s" class="alert-link">このリンクをクリックしてください</a></h5>';
$lang->extension->errorDownloadFailed = 'ダウンロードに失敗しました、改めてダウンロードしてください。繰り返して試してもダウンロードできませんなら、手動でダウンロードして、そしてアップロード機能を利用してアップロードしてください。';
$lang->extension->errorMd5Checking = 'ダウンロードしたファイルが不完全です、改めてダウンロードしてください。繰り返して試してもダウンロードできませんなら、手動でダウンロードして、そしてアップロード機能を利用してアップロードしてください。';
$lang->extension->errorCheckIncompatible = 'このプラグインは禅道バージョンとの互換性が悪いので、%s後は使用できないかもしれません。<h5>以下の解決策を選択してください： <a href="%s" class="btn btn-sm">強制%s</a> 或いは <a href="#" onclick=parent.location.href="%s" class="btn btn-sm">キャンセル</a></h5>';
$lang->extension->errorFileConflicted = '次のファイルは競合があります：<br />%s <h5> 以下の解決策を選択してください： <a href="%s" class="btn btn-sm">上書き</a> 或いは <a href="#" onclick=parent.location.href="%s" class="btn btn-sm">キャンセル</a></h5>';
$lang->extension->errorPackageNotFound = 'パッケージのファイル <strong>%s </strong>が見つかりませんでした、自動ダウンロードに失敗したかもしれません。改めてダウンロードしてください。';
$lang->extension->errorTargetPathNotWritable = 'ターゲットパス<strong>%s </strong>が編集できません。';
$lang->extension->errorTargetPathNotExists = 'ターゲットパス<strong>%s </strong>が存在していません。';
$lang->extension->errorInstallDB = 'データベースのSQL文の実行に失敗しました。エラーメッセージは：%s';
$lang->extension->errorConflicts = 'プラグイン“%s”と競合があります！';
$lang->extension->errorDepends = '下記の依存プラグインは未インストール或いはバージョンが間違っています：<br /><br /> %s';
$lang->extension->errorIncompatible = '当該プラグインはあなたの禅道バージョンとの互換性が悪いです';
$lang->extension->errorUninstallDepends = 'プラグイン“%s”は当該プラグインに依存しています、アンインストールできません';
