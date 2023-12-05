<?php
/**
 * The create view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('users', $users);?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->user->create;?></h2>
      </div>
      <form id='gitlabForm' method='post' class='form-ajax' enctype="multipart/form-data">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->user->bind;?></th>
            <td class='required'><?php echo html::select('account', $userPairs, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->name;?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control' placeholder='{$lang->gitlab->user->name}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->username;?></th>
            <td class='required'><?php echo html::input('username', '', "class='form-control' placeholder='{$lang->gitlab->user->username}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->email;?></th>
            <td class='required'><?php echo html::input('email', '', "class='form-control' placeholder='{$lang->gitlab->user->email}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->password;?></th>
            <td class='required'><?php echo html::password('password', '', "class='form-control' placeholder='{$lang->gitlab->user->password}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->passwordRepeat;?></th>
            <td class='required'><?php echo html::password('password_repeat', '', "class='form-control' placeholder='{$lang->gitlab->user->passwordRepeat}'");?></td>
            <td class="tips-git"></td>
          </tr>
          <tr class="hidden">
            <th><?php echo $lang->gitlab->user->projectsLimit;?></th>
            <td><?php echo html::input('projects_limit', 100000, "class='form-control' placeholder='{$lang->gitlab->user->projectsLimit}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->canCreateGroup;?></th>
            <td>
              <div class="checkbox-primary">
                <input type="checkbox" name='can_create_group' id="canCreateGroup" value='1' checked /><label for="external" class="no-margin">&nbsp;</label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->user->external;?></th>
            <td colspan='2'>
              <div class="checkbox-primary">
                <input type="checkbox" name='external' id="external" value='1'/><label for="external" class="no-margin"><?php echo $lang->gitlab->user->externalTip; ?></label>
              </div>
            </td>
          </tr>
          <?php if(function_exists('curl_file_create')):?>
          <tr>
            <th><?php echo $lang->gitlab->user->avatar;?></th>
            <td>
              <div id="avatarUpload" class="text-center">
                <?php echo html::avatar(array('avatar'=>$defaultTheme . 'images/repo/avatar.jpeg', 'account'=>''), 50); ?>
                <input type="file" name="avatar" id="files" class="form-control hidden">
                <?php echo html::a('javascript:void(0);', '<i class="icon icon-pencil icon-2x"></i>', '', "class='btn-avatar' id='avatarUploadBtn' data-toggle='tooltip' data-container='body' data-placement='bottom' title='{$lang->gitlab->user->avatar}'");?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <td colspan="2" class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseUser', "gitlabID=$gitlabID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
