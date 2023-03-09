<?php
/**
 * The editManagePriv view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Feilong Guo <guofeilong@cnezsoft.com>
 * @package     group
 * @version     $id editManagePriv.html.php 4769 2023-03-07 07:24:21Z guofeilong $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class='flex-space-center'>
  <div class="btn-toolbar">
    <?php echo html::a($this->createLink('group', 'browse', ''), '<i class="icon icon-angle-left"></i>' . $lang->goback, '', 'class="btn btn-back"');?>
    <a href="#" class="btn btn-link btn-active-text"><?php echo $lang->group->all?></a>
  </div>
  <div class="btn-toolbar">
    <div class="btn-group">
      <a href="#" data-type="bycard" class="btn btn-icon btn-switch <?php if($browseType == 'bycard') echo 'text-primary' ?>"><i class="icon icon-cards-view"></i></a>
      <a href="#" data-type="bylist" class="btn btn-icon btn-switch <?php if($browseType == 'bylist' or $browseType == 'bysearch') echo 'text-primary' ?>"><i class="icon icon-list"></i></a>
    </div>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->addPriv?></a>
    <?php if(common::hasPriv('group', 'managePrivPackage')) echo html::a($this->createLink('group', 'managePrivPackage', ''), $lang->group->managePrivPackage, '', 'class="btn btn-primary"');?>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->batchSetDependency?></a>
  </div>
</div>
<div id='mainContent'>
  <div class="main main-content">
  <?php
  if($browseType == 'bycard')
  {
      include 'editmanageprivbycard.html.php';
  }
  else
  {
      include 'editmanageprivbylist.html.php';
  }?>

  </div>
  <div class="side">
    <div class="priv-panel">
      <div class="panel-title">依赖的权限</div>
      <div class="panel-content">
        <div class="menuTree depend menu-active-primary menu-hover-primary"></div>
      </div>
      <div class="panel-bottom">
        <?php echo html::commonButton ('<i class="icon icon-plus"></i>' . $lang->group->add, '', 'btn btn-primary');?>
      </div>
    </div>
    <div class="priv-panel mt-m">
      <div class="panel-title">推荐的权限</div>
      <div class="panel-content">
        <div class="menuTree recommend menu-active-primary menu-hover-primary"></div>
      </div>
      <div class="panel-bottom">
        <?php echo html::a(inlink('addRecommendation', 'privID=1'), '<i class="icon icon-plus"></i>' . $lang->group->add, '', "class='btn btn-primary iframe'");?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

