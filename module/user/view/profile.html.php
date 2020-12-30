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
<div id='mainContent' class='main-row'>
  <div class='main-col col-6'>
    <div class='cell'>
      <div class='main-header'>
        <h2><?php echo $lang->user->profile;?></h2>
        <div class='actions pull-right'>
          <?php if(common::hasPriv('user', 'edit')) echo html::a($this->createLink('user', 'edit', "userID=$user->id"), html::icon('pencil') . ' ' . $lang->user->editProfile, '', "class='btn btn-primary'"); ?>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <div class='info-title'><?php echo $lang->user->basicInfo;?></div>
          <dl class='dl-horizontal info'>
            <dt><?php echo $lang->user->realname;?></dt>
            <dd><?php echo $user->realname;?></dd>
            <dt><?php echo $lang->user->account;?></dt>
            <dd><?php echo $user->account;?></dd>
            <dt><?php echo $lang->user->dept;?></dt>
            <dd>
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
            </dd>
            <dt><?php echo $lang->user->join;?></dt>
            <dd><?php echo formatTime($user->join);?></dd>
          </dl>
          <div class='info-title'><?php echo $lang->user->contactInfo;?></div>
          <dl class='dl-horizontal contact'>
            <dt><?php echo $lang->user->mobile;?></dt>
            <dd><?php echo $user->mobile;?></dd>
            <dt><?php echo $lang->user->phone;?></dt>
            <dd><?php echo $user->phone;?></dd>
            <dt><?php echo $lang->user->weixin;?></dt>
            <dd><?php echo $user->weixin;?></dd>
            <dt><?php echo $lang->user->qq;?></dt>
            <dd><?php echo $user->qq;?></dd>
            <dt><?php echo $lang->user->zipcode;?></dt>
            <dd><?php echo $user->zipcode;?></dd>
          </dl>
          <div class='info-title'><?php echo $lang->user->else;?></div>
          <dl class='dl-horizontal else'>
            <dt><?php echo $lang->user->commiter;?></dt>
            <dd><?php echo $user->commiter;?></dd>
            <dt><?php echo $lang->user->ip;?></dt>
            <dd><?php echo $user->ip;?></dd>
          </dl>
        </div>
        <div class='col-md-6'>
          <dl class='dl-horizontal right-info'>
            <dt><?php echo $lang->user->gender;?></dt>
            <dd><?php echo zget($lang->user->genderList, $user->gender);?></dd>
            <dt><?php echo $lang->user->email;?></dt>
            <dd title='<?php echo $user->email;?>'><?php echo $user->email;?></dd>
            <dt><?php echo $lang->user->role;?></dt>
            <dd><?php echo zget($lang->user->roleList, $user->role);?></dd>
            <dt><?php echo $lang->group->priv;?></dt>
            <dd><?php foreach($groups as $group) echo $group->name . ' '; ?></dd>
          </dl>
          <dl class='dl-horizontal right-info'>
            <dt><?php echo $lang->user->skype;?></dt>
            <dd><?php if($user->skype) echo html::a("callto://$user->skype", $user->skype);?></dd>
            <dt><?php echo $lang->user->whatsapp;?></dt>
            <dd><?php echo $user->whatsapp;?></dd>
            <dt><?php echo $lang->user->slack;?></dt>
            <dd><?php echo $user->slack;?></dd>
            <dt><?php echo $lang->user->dingding;?></dt>
            <dd><?php echo $user->dingding;?></dd>
            <dt><?php echo $lang->user->address;?></dt>
            <dd title='<?php echo $user->address;?>'><?php echo $user->address;?></dd>
          </dl>
          <dl class='dl-horizontal right-info'>
            <dt><?php echo $lang->user->visits;?></dt>
            <dd><?php echo $user->visits;?></dd>
            <dt><?php echo $lang->user->last;?></dt>
            <dd><?php echo $user->last;?></dd>
          </dl>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
