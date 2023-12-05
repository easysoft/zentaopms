<?php
/**
 * The detail view file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php js::set('instanceNotices', $lang->instance->notices);?>
<?php js::set('instanceIdList',  array($instance->id));?>
<?php js::set('demoAppLife',     $config->demoAppLife);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left instance-name'>
    <h3><?php echo $instance->name;?></h3>
    <span><?php echo $instance->appVersion;?></span>
    <?php echo html::a($this->createLink('instance', 'editname', "id=$instance->id", '', true), '<i class="icon-edit"></i>', '', "class='iframe' title='$lang->edit' data-width='600' data-app='space'");?>
    <?php if($instance->solution):?>
    <?php echo html::a(helper::createLink('solution', 'view', "id=$instance->solution"), $instance->solutionData->name, '', "class='label label-success label-outline solution-link'");?>
    <?php endif;?>
  </div>
  <div class='btn-toolbar pull-right instance-panel'>
    <div class="">
      <?php $this->instance->printTextActions($instance);?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <ul class="nav nav-tabs">
    <li class="<?php echo $tab == 'baseinfo' ? 'active' : '';?>"><a href="<?php echo $this->createLink('instance', 'view', "id=$instance->id&page=&perPage=&pageSize=&tab=baseinfo");?>"><?php echo $lang->instance->baseInfo;?></a></li>
    <li class="<?php echo $tab == 'backup' ? 'active' : '';?>"><a href="<?php echo $this->createLink('instance', 'view', "id={$instance->id}&total=&perPage=&pageID=&tab=backup");?>"><?php echo $lang->instance->backupAndRestore;?></a></li>
    <li class="<?php echo $tab == 'advance' ? 'active' : '';?>"><a href="<?php echo $this->createLink('instance', 'view', "id={$instance->id}&total=&perPage=&pageID=&tab=advance");?>"><?php echo $lang->instance->advance;?></a></li>
  </ul>
  <div class="tab-content">
  <div class="tab-pane <?php echo $tab == 'baseinfo' ? 'active' : '';?>" id="baseInfo">
      <?php include 'baseinfo.html.php';?>
    </div>
    <div class="tab-pane <?php echo $tab == 'backup' ? 'active' : '';?>" id="backup">
      <?php if($tab == 'backup') include 'backup.html.php';?>
    </div>
    <div class="tab-pane <?php echo $tab == 'advance' ? 'active' : '';?>" id="advance">
      <?php if($tab == 'advance') include 'advance.html.php';?>
    </div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
