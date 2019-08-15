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
<div id='mainContent' class='main-row'>
  <div class='main-col col-6'>
    <div class='cell'>
      <div class='main-header'>
        <h2><?php echo $lang->user->legendBasic;?></h2>
        <div class='actions pull-right'>
          <?php echo html::a($this->createLink('my', 'editprofile'), $lang->user->editProfile, '', "class='btn btn-primary'");?>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-6'>
          <dl class='dl-horizontal info'>
            <dt><?php echo $lang->user->account;?></dt>
            <dd><?php echo $user->account;?></dd>
            <dt><?php echo $lang->user->realname;?></dt>
            <dd><?php echo $user->realname;?></dd>
            <dt><?php echo $lang->user->gender;?></dt>
            <dd><?php echo zget($lang->user->genderList, $user->gender);?></dd>
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
            <dt><?php echo $lang->user->role;?></dt>
            <dd><?php echo zget($lang->user->roleList, $user->role);?></dd>
            <dt><?php echo $lang->group->priv;?></dt>
            <dd><?php foreach($groups as $group) echo $group->name . ' '; ?></dd>
            <dt><?php echo $lang->user->commiter;?></dt>
            <dd><?php echo $user->commiter;?></dd>
            <dt><?php echo $lang->user->join;?></dt>
            <dd><?php echo formatTime($user->join);?></dd>
            <dt><?php echo $lang->user->visits;?></dt>
            <dd><?php echo $user->visits;?></dd>
            <dt><?php echo $lang->user->ip;?></dt>
            <dd><?php echo $user->ip;?></dd>
            <dt><?php echo $lang->user->last;?></dt>
            <dd><?php echo $user->last;?></dd>
            <?php if($user->ranzhi):?>
            <dt><?php echo $lang->user->ranzhi;?></dt>
            <dd>
              <?php echo $user->ranzhi . ' ';?>
              <?php if(common::hasPriv('my', 'unbind')) echo html::a($this->createLink('my', 'unbind'), "<i class='icon-unlink'></i>", 'hiddenwin', "class='bin-icon' title='{$lang->user->unbind}'");?>
            </dd>
            <?php endif;?>
          </dl>
        </div>
        <div class='divider'></div>
        <div class='col-md-6'>
          <dl class='dl-horizontal contact'>
            <dt><?php echo $lang->user->email;?></dt>
            <dd title='<?php echo $user->email;?>'><?php echo $user->email;?></dd>
            <?php if(!empty($config->user->contactField)):?>
            <?php foreach(explode(',', $config->user->contactField) as $field):?>
            <dt><?php echo $lang->user->$field;?></dt>
            <dd>
              <?php
              if($field == 'skype' and $user->$field)
              {
                  echo html::a("callto://$user->skype", $user->skype);
              }
              elseif($field == 'qq' and $user->$field)
              {
                  echo html::a("tencent://message/?uin=$user->qq", $user->qq);
              }
              else
              {
                  echo $user->$field;
              }
              ?>
            </dd>
            <?php endforeach;?>
            <?php endif;?>
            <dt><?php echo $lang->user->address;?></dt>
            <dd title='<?php echo $user->address;?>'><?php echo $user->address;?></dd>
            <dt><?php echo $lang->user->zipcode;?></dt>
            <dd><?php echo $user->zipcode;?></dd>
          </dl>
        </div>
      </div>
    </div>
  </div>
  <div class='side-col col-6'>
    <div class='cell'>
      <div class='main-header'>
        <h2><?php echo $lang->user->legendContribution;?></h2>
      </div>
      <div class='row tiles'>
        <?php foreach($lang->user->personalData as $key => $title):?>
        <div class='col-4 col tile'>
          <div class='tile-title'><?php echo $title;?></div>
          <div class='tile-amount'><?php echo zget($personalData, $key, 0);?></div>
        </div>
        <?php endforeach;?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
