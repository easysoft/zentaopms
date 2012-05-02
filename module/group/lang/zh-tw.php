<?php
/**
 * The group module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: zh-tw.php 2894 2012-05-02 14:06:00Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->group->common             = '權限分組';
$lang->group->browse             = '瀏覽分組';
$lang->group->create             = '新增分組';
$lang->group->edit               = '編輯分組';
$lang->group->copy               = '複製分組';
$lang->group->delete             = '刪除分組';
$lang->group->managePriv         = '權限維護';
$lang->group->managePrivByGroup  = '權限維護';
$lang->group->managePrivByModule = '按模組分配權限';
$lang->group->manageMember       = '成員維護';
$lang->group->linkMember         = '關聯用戶';
$lang->group->unlinkMember       = '移除用戶';
$lang->group->confirmDelete      = '您確定刪除該用戶分組嗎？';
$lang->group->successSaved       = '成功保存';
$lang->group->errorNotSaved      = '沒有保存，請確認選擇了權限數據。';

$lang->group->id       = '編號';
$lang->group->name     = '分組名稱';
$lang->group->desc     = '分組描述';
$lang->group->users    = '用戶列表';
$lang->group->module   = '模組';
$lang->group->method   = '方法';
$lang->group->priv     = '權限';
$lang->group->option   = '選項';
$lang->group->inside   = '組內用戶';
$lang->group->outside  = '組外用戶';

$lang->group->copyOptions['copyPriv'] = '複製權限';
$lang->group->copyOptions['copyUser'] = '複製用戶';

$lang->group->versions['']          = '顯示各版本新增權限';
$lang->group->versions['3.1']       = '禪道3.1';
$lang->group->versions['3.0.beta2'] = '禪道3.0.beta2';
$lang->group->versions['3.0.beta1'] = '禪道3.0.beta1';
$lang->group->versions['2.4']       = '禪道2.4';
$lang->group->versions['2.3']       = '禪道2.3';
$lang->group->versions['2.2']       = '禪道2.2';
$lang->group->versions['2.1']       = '禪道2.1';
$lang->group->versions['2.0']       = '禪道2.0';
$lang->group->versions['1.5']       = '禪道1.5';
$lang->group->versions['1.4']       = '禪道1.4';
$lang->group->versions['1.3']       = '禪道1.3';
$lang->group->versions['1.2']       = '禪道1.2';
$lang->group->versions['1.1']       = '禪道1.1';
$lang->group->versions['1.0.1']     = '禪道1.0.1';

include (dirname(__FILE__) . '/resource.php');
