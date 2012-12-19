<?php
/**
 * The user module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->user->common    = 'User';
$lang->user->id        = 'ID';
$lang->user->company   = 'Company';
$lang->user->dept      = 'Department';
$lang->user->account   = 'Account';
$lang->user->password  = 'Password';
$lang->user->password2 = 'Repeat password';
$lang->user->role      = 'Role';
$lang->user->realname  = 'Fullname';
$lang->user->nickname  = 'Nickname';
$lang->user->commiter  = 'Commit account';
$lang->user->avatar    = 'Avatar';
$lang->user->birthyear = 'Birth year';
$lang->user->gender    = 'Gender';
$lang->user->email     = 'Email';
$lang->user->msn       = 'MSN';
$lang->user->qq        = 'QQ';
$lang->user->yahoo     = 'Yahoo!';
$lang->user->gtalk     = 'GTalk';
$lang->user->wangwang  = 'Wangwang';
$lang->user->mobile    = 'Mobile';
$lang->user->phone     = 'Phone';
$lang->user->address   = 'Address';
$lang->user->zipcode   = 'Zipcode';
$lang->user->join      = 'Join date';
$lang->user->visits    = 'Visits';
$lang->user->ip        = 'Last IP';
$lang->user->last      = 'Last login';
$lang->user->status    = 'Status';
$lang->user->ditto     = 'Ditto';

$lang->user->index           = "Index";
$lang->user->view            = "Info";
$lang->user->create          = "Add";
$lang->user->batchCreate     = "Batch add user";
$lang->user->read            = "Info";
$lang->user->edit            = "Edit";
$lang->user->unlock          = "Unlock";
$lang->user->update          = "Upgrade";
$lang->user->delete          = "Delete";
$lang->user->browse          = "Browse";
$lang->user->login           = "Login";
$lang->user->userView        = "User view";
$lang->user->editProfile     = "Edit profile";
$lang->user->editPassword    = "Change password";
$lang->user->deny            = "Denied";
$lang->user->confirmDelete   = "Are you sure to delete this user?";
$lang->user->confirmActivate = "Are you sure to activate this user?";
$lang->user->confirmUnlock   = "Are you sure to unlock this user?";
$lang->user->relogin         = "Relogin";
$lang->user->asGuest         = "Guest";
$lang->user->goback          = "Back";
$lang->user->allUsers        = 'All users';
$lang->user->deleted         = '(deleted)';
$lang->user->select          = '--select user--';

$lang->user->profile     = 'Profile';
$lang->user->project     = 'Project';
$lang->user->task        = 'Task';
$lang->user->bug         = 'Bug';
$lang->user->todo        = 'Todo';
$lang->user->story       = 'Story';
$lang->user->team        = 'Team';
$lang->user->dynamic     = 'Dynamic';
$lang->user->ajaxGetUser = 'AJAX:get users';
$lang->user->editProfile = 'Edit profile';

$lang->user->errorDeny   = "Sorry, you can't access the <b>%s</b> module's <b>%s</b> feature";
$lang->user->loginFailed = "Login failed, please check your account and password.";
$lang->user->lockWarning = "You only have %s times to try.";
$lang->user->loginLocked = "You try the password too many times, please contact the administrator or try again after %s minutes.";

$lang->user->roleList['']       = '';
$lang->user->roleList['dev']    = 'Developer';
$lang->user->roleList['qa']     = 'Tester';
$lang->user->roleList['pm']     = 'Project manager';
$lang->user->roleList['po']     = 'Product owner';
$lang->user->roleList['td']     = 'Technical directory';
$lang->user->roleList['pd']     = 'Product director';
$lang->user->roleList['qd']     = 'Quality Director';
$lang->user->roleList['top']    = 'Top manager';
$lang->user->roleList['others'] = 'Others';

$lang->user->genderList['m'] = 'Male';
$lang->user->genderList['f'] = 'Female';

$lang->user->statusList['active'] = 'Activate';
$lang->user->statusList['delete'] = 'Deleted';

$lang->user->keepLogin['on']      = 'Keep login';
$lang->user->loginWithDemoUser    = 'Login with demo user:';

$lang->user->placeholder = new stdclass();
$lang->user->placeholder->account   = 'Letters/underline/numbers, three above';
$lang->user->placeholder->password1 = 'Six above';
$lang->user->placeholder->join      = 'The date the employee joined the company';
$lang->user->placeholder->commiter  = 'The account in version control systems';

$lang->user->error = new stdclass();
$lang->user->error->account       = "ID %s，account must be three letters at least";
$lang->user->error->accountDupl   = "ID %s，this account has been exist";
$lang->user->error->realname      = "ID %s，please input realname";
$lang->user->error->password      = "ID %s，password must be six letters at least";
$lang->user->error->mail          = "ID %s，please input correct email address";
