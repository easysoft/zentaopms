<?php
/**
 * The profile view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id: profile.html.php 4694 2013-05-02 01:40:54Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-600px'>
  <div id='titlebar'>
    <div class='heading'><?php echo html::icon($lang->icons['user']);?> <?php echo $lang->my->profile;?></div>
    <div class='actions'>
      <?php echo html::a($this->createLink('my', 'editprofile'), $lang->user->editProfile, '', "class='btn btn-primary'");?>
    </div>
  </div>
  <table class='table table-borderless table-data'>
    <tr>
      <th class='rowhead w-100px'><?php echo $lang->user->dept;?></th>
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
      <th><?php echo $lang->user->role;?></th>
      <td><?php echo $lang->user->roleList[$user->role];?></td>
    </tr>
    <tr>
      <th><?php echo $lang->group->priv;?></th>
      <td><?php foreach($groups as $group) echo $group->name . ' '; ?></td>
    </tr>
    <tr>
      <th><?php echo $lang->user->commiter;?></th>
      <td><?php echo $user->commiter;?></td>
    </tr>
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
    <?php if($user->ranzhi):?>
    <tr>
      <th><?php echo $lang->user->ranzhi;?></th>
      <td>
        <?php echo $user->ranzhi . ' ';?>
        <?php if(common::hasPriv('my', 'unbind')) echo html::a($this->createLink('my', 'unbind'), "<i class='icon-unlink'></i>", 'hiddenwin', "class='bin-icon' title='{$lang->user->unbind}'");?>
      </td>
    </tr>
    <?php endif;?>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
