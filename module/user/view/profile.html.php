<?php
/**
 * The profile view file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: profile.html.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<?php if(!isonlybody()):?>
<style>#mainContent{width: 60%; margin-left: 20%;}</style>
<?php endif;?>
<div id='mainContent'>
  <div class='cell'>
    <div class='main-header text-center'>
      <span class="avatar avatar bg-secondary avatar-circle">
      <?php echo $user->avatar ? html::image($user->avatar) : strtoupper($user->account[0]);?>
      </span>
      <span class='user-name'><?php echo $user->realname;?></span>
      <span class='user-role'><?php echo zget($lang->user->roleList, $user->role, '');?></span>
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
          <th><?php echo $lang->user->join;?></th>
          <td><?php echo formatTime($user->join);?></td>
          <th><?php echo $lang->group->priv;?></th>
          <td><?php foreach($groups as $group) echo $group->name . ' ';?></td>
        </tr>
      </table>
      <div class='line'></div>
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
          <th><?php echo $lang->user->address;?></th>
          <td title='<?php echo $user->address;?>'><?php echo $user->address;?></td>
        </tr>
      </table>
      <?php if(!isonlybody()):?>
      <div class='line'></div>
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
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
