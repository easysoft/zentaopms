<?php
/**
 * The profile view file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: profile.html.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<div class='container mw-600px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix' title='USER'><?php echo html::icon($lang->icons['user']);?> <strong><?php echo $user->id;?></strong></span>
      <strong><?php echo $user->realname;?> (<small><?php echo $user->account;?></small>)</strong>
      <small class='text-muted'> <?php echo $lang->user->profile;?> <?php echo html::icon('eye-open');?></small>
    </div>
    <div class='actions'>
      <?php echo html::a($this->createLink('user', 'edit', "userID=$user->id"), html::icon('pencil') . ' ' . $lang->user->editProfile, '', "class='btn btn-primary'"); ?>
    </div>
  </div>
  <table class='table table-borderless table-data'>
    <tr>
      <th class='w-100px'><?php echo $lang->user->dept;?></th>
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
    </tr>
    <tr>
      <th><?php echo $lang->user->account;?></th>
      <td><?php echo $user->account;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->realname;?></th>
      <td><?php echo $user->realname;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->group->priv;?></th>
      <td><?php foreach($groups as $group) echo $group->name . ' '; ?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->role;?></th>
      <td><?php echo $lang->user->roleList[$user->role];?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->commiter;?></th>
      <td><?php echo $user->commiter;?></td>
    </tr>
    <!--
    <tr>
      <?php // echo $lang->user->nickname;?>
      <?php // echo $user->nickname;?>
    </tr>
    -->
    <tr>
      <th><?php echo $lang->user->email;?></th>
      <td><?php echo $user->email;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->join;?></th>
      <td><?php echo $user->join;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->visits;?></th>
      <td><?php echo $user->visits;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->ip;?></th>
      <td><?php echo $user->ip;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->last;?></th>
      <td><?php echo $user->last;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->skype;?></th>
      <td><?php if($user->skype) echo html::a("callto://$user->skype", $user->skype);?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->qq;?></th>
      <td><?php if($user->qq) echo html::a("tencent://message/?uin=$user->qq", $user->qq);?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->yahoo;?></th>
      <td><?php echo $user->yahoo;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->gtalk;?></th>
      <td><?php echo $user->gtalk;?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->wangwang;?></th>
      <td><?php echo $user->wangwang;?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->mobile;?></th>
      <td><?php echo $user->mobile;?></td>
    </tr>
     <tr>
      <th><?php echo $lang->user->phone;?></th>
      <td><?php echo $user->phone;?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->address;?></th>
      <td><?php echo $user->address;?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->user->zipcode;?></th>
      <td><?php echo $user->zipcode;?></td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
