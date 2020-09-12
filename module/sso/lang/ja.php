<?php
/**
 * The sso module ja file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      admin
 * @package     sso
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = '配置';
$lang->sso->turnon = 'オープンしますか';
$lang->sso->redirect = '自動で然之へジャンプ';
$lang->sso->code = 'コードネーム';
$lang->sso->key = '認証キー';
$lang->sso->addr = 'インタフェースアドレス';
$lang->sso->bind = 'アカウントバインド';
$lang->sso->addrNotice = '例：http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = 'オープン';
$lang->sso->turnonList[0] = 'クローズ';

$lang->sso->bindType = 'バインド方法';
$lang->sso->bindUser = 'ユーザをバインド';

$lang->sso->bindTypeList['bind'] = '既存ユーザをバインド';
$lang->sso->bindTypeList['add'] = '新しいユーザ追加';

$lang->sso->help = <<<EOD
<p>1、パスアドレスの入力について、PATH_INFOの場合、http://YOUR ZDOO ADDRESS/sys/sso-check.html。GETの場合、http://YOUR ZDOO ADDRESS/sys/index.php?m=sso&f=check です。</p>
<p>2、コードと秘密鍵は然之サイトと一致する必要があります。</p>
EOD;
$lang->sso->bindNotice = '追加の新しいユーザはしばらく権限がありません、権限を割り当てるために、禅道管理者に連絡してください。';
$lang->sso->bindNoPassword = 'パスワードを入力してください';
$lang->sso->bindNoUser = 'パスワードが間違い、またはユーザが存在していません';
$lang->sso->bindHasAccount = 'ユーザ名が存在していますので、ユーザ名を変えて、またはこのユーザにバインドしてください。';
