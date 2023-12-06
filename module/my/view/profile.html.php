<?php
/**
 * The profile view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id: profile.html.php 4694 2013-05-02 01:40:54Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('avatar', $this->app->user->avatar);?>
<?php js::set('userID', $this->app->user->id);?>
<?php if(!zget($lang->user->roleList, $user->role, '')):?>
<style>.user-name {line-height: 40px;}</style>
<?php endif;?>
<div id='mainContent'>
  <div class='cell'>
    <div class='main-header text-center'>
      <div id="avatarUpload">
        <?php echo html::avatar($user, 40); ?>
        <form method='post' class='form-ajax' action=<?php echo inlink('uploadAvatar');?> id='avatarForm' enctype='multipart/form-data'>
          <input type="file" name="files" id="files" class="form-control hidden">
          <?php if(common::hasPriv('my', 'uploadAvatar')) echo html::a('javascript:void(0);', '<i class="icon icon-pencil icon-2x"></i>', '', "class='btn-avatar' id='avatarUploadBtn' data-toggle='tooltip' data-container='body' data-placement='bottom' title='{$lang->my->uploadAvatar}'");?>
        </form>
      </div>
      <div class='user-name'><?php echo $user->realname;?></div>
      <div class='user-role'><?php echo zget($lang->user->roleList, $user->role, '');?></div>
    </div>
    <div class='row'>
      <table>
        <tr>
          <th><?php echo $lang->user->realname;?></th>
          <td><?php echo $user->realname;?></td>
          <th><?php echo $lang->user->gender;?></th>
          <td><?php echo zget($lang->user->genderList, $user->gender);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->account;?></th>
          <td><?php echo $user->account;?></td>
          <th><?php echo $lang->user->email;?></th>
          <td title='<?php echo $user->email;?>'><?php echo html::mailto($user->email);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->dept;?></th>
          <td>
          <?php
          if(empty($deptPath))
          {
              echo "/";
          }
          else
          {
              foreach($deptPath as $key => $dept)
              {
                  if($dept->name) echo $dept->name;
                  if(isset($deptPath[$key + 1])) echo $lang->arrow;
              }
          }
          ?>
          </td>
          <th><?php echo $lang->user->role;?></th>
          <td><?php echo zget($lang->user->roleList, $user->role, '');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->abbr->join;?></th>
          <td><?php echo formatTime($user->join);?></td>
          <th><?php echo $lang->user->priv;?></th>
          <td><?php foreach($groups as $group) echo $group->name . ' ';?></td>
        </tr>
      </table>
      <table>
        <tr>
          <th><?php echo $lang->user->mobile;?></th>
          <td><?php echo $user->mobile;?></td>
          <th><?php echo $lang->user->weixin;?></th>
          <td><?php echo $user->weixin;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->phone;?></th>
          <td><?php echo $user->phone;?></td>
          <th><?php echo $lang->user->qq;?></th>
          <td><?php echo $user->qq;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->zipcode;?></th>
          <td><?php echo $user->zipcode;?></td>
          <th><?php echo $lang->user->abbr->address;?></th>
          <td title='<?php echo $user->address;?>'><?php echo $user->address;?></td>
        </tr>
      </table>
      <table>
        <tr>
          <th><?php echo $lang->user->commiter;?></th>
          <td><?php echo $user->commiter;?></td>
          <th><?php echo $lang->user->skype;?></th>
          <td><?php if($user->skype) echo html::a("callto://$user->skype", $user->skype);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->visits;?></th>
          <td><?php echo $user->visits;?></td>
          <th><?php echo $lang->user->whatsapp;?></th>
          <td><?php echo $user->whatsapp;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->last;?></th>
          <td><?php echo $user->last;?></td>
          <th><?php echo $lang->user->slack;?></th>
          <td><?php echo $user->slack;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->user->ip;?></th>
          <td><?php echo $user->ip;?></td>
          <th><?php echo $lang->user->dingding;?></th>
          <td><?php echo $user->dingding;?></td>
        </tr>
      </table>
    </div>
  </div>
  <div class='main-actions'>
    <?php if(!isset($fromModule)):?>
    <div class='btn-toolbar'>
      <?php common::printLink('my', 'changepassword', "", $lang->changePassword, '', "title={$lang->changePassword} class='btn'");?>
      <?php common::printLink('my', 'editprofile', "", $lang->user->editProfile, '', "title={$lang->user->editProfile} class='btn'");?>
      <?php if(common::hasPriv('my', 'uploadAvatar')) echo html::a('javascript:uploadAvatar();', $lang->my->uploadAvatar, '', "class='btn'");?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
