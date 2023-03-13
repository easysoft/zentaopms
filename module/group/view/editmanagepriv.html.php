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
    <?php echo html::a(inlink('editManagePriv', "browseType={$browseType}&view="), $lang->group->all, '', "class='btn btn-link btn-active-text'");?>
    <?php if($browseType != 'bycard'):?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
    <?php endif;?>
  </div>
  <div class="btn-toolbar">
    <div class="btn-group">
      <a href="#" data-type="bycard" class="btn btn-icon btn-switch <?php if($browseType == 'bycard') echo 'text-primary' ?>"><i class="icon icon-cards-view"></i></a>
      <a href="#" data-type="bylist" class="btn btn-icon btn-switch <?php if($browseType == 'bylist' or $browseType == 'bysearch') echo 'text-primary' ?>"><i class="icon icon-list"></i></a>
    </div>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->addPriv?></a>
    <?php if(common::hasPriv('group', 'managePrivPackage')) echo html::a($this->createLink('group', 'managePrivPackage', ''), $lang->group->managePrivPackage, '', 'class="btn btn-primary"');?>
    <?php if(common::hasPriv('group', 'addRelation')):?>
    <a href="#" class="btn btn-primary iframe" disabled id='batchSetDepend'><?php echo $lang->group->batchSetDependency?></a>
    <a href="#" class="btn btn-primary iframe" disabled id='batchSetRecommend'><?php echo $lang->group->batchSetRecommendation?></a>
    <?php endif;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module="priv"></div>
<div id='mainContent'>
  <div class="main main-content">
  <?php if($browseType == 'bycard'):?>
    <?php include 'editmanageprivbycard.html.php';?>
  </div>
  <div class="side">
    <div class="priv-panel">
      <div class="panel-title">
        <?php echo $lang->group->dependentPrivs?>
        <span class='priv-count'></span>
      </div>
      <div class="panel-content">
        <div class="menuTree depend menu-active-primary menu-hover-primary"></div>
        <div class="empty-tip flex-center">
          <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
        </div>
      </div>
      <div class="panel-bottom">
        <?php echo html::a('#', '<i class="icon icon-plus"></i>' . $lang->group->add, '', "class='btn btn-primary iframe' disabled id='addDependent'");?>
      </div>
    </div>
    <div class="priv-panel mt-m">
      <div class="panel-title">
        <?php echo $lang->group->recommendPrivs?>
        <span class='priv-count'></span>
      </div>
      <div class="panel-content">
        <div class="menuTree recommend menu-active-primary menu-hover-primary"></div>
        <div class="empty-tip flex-center">
          <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
        </div>
      </div>
      <div class="panel-bottom">
        <?php echo html::a('#', '<i class="icon icon-plus"></i>' . $lang->group->add, '', "class='btn btn-primary iframe' disabled id='addRecommendation'");?>
      </div>
    </div>
  </div>
  <?php else:?>
  <?php include 'editmanageprivbylist.html.php';?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>

