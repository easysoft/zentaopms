<?php
/**
 * The permissionedit view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Feilong Guo <guofeilong@cnezsoft.com>
 * @package     group
 * @version     permissionedit.html.php 4769 2023-03-07 07:24:21Z guofeilong $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class='flex-space-center'>
  <div class="btn-toolbar">
    <?php echo html::backButton('<i class="icon icon-angle-left"></i>' . $lang->goback, '', '');?>
    <a href="#" class="btn btn-link btn-active-text"><?php echo $lang->group->all?></a>
  </div>
  <div class="btn-toolbar">
    <div class="btn-group">
    <?php echo html::a($this->createLink('', '', '', '', false), '<i class="icon icon-list"></i>', '', 'class="btn"');?>
    <?php echo html::a($this->createLink('', '', '', '', false), '<i class="icon icon-cards-view"></i>', '', 'class="btn"');?>
    </div>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->addPriv?></a>
    <?php if(common::hasPriv('group', 'managePrivPackage')) echo html::a($this->createLink('group', 'managePrivPackage', ''), $lang->group->managePrivPackage, '', 'class="btn btn-primary"');?>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->batchSetDependency?></a>
  </div>
</div>
<div id='mainContent'>
  <div class="main main-content">
    <form class="load-indicator main-form form-ajax" id="permissionEditForm" method="post" target='hiddenwin'>
      <table class='table table-hover table-striped table-bordered' id='privList'>
        <thead>
          <tr class="text-center">
            <th class="thWidth">模块</th>
            <th class="thWidth">权限包</th>
            <th colspan="2">权限</th>
          </tr>
        </thead>
      </table>
    </form>
  </div>
  <div class="side">
    <div class="priv-panel">
      <div class="panel-title">依赖的权限</div>
      <div class="panel-content">
        <div class="priv-title"><i class="icon icon-caret-right"></i>地盘</div>
        <div class="panel-list">
          <div class="priv-item"> 地盘仪表盘 <i class="icon icon-close hidden"></i></div>
          <div class="priv-item"> 地盘仪表盘 <i class="icon icon-close hidden"></i></div>
          <div class="priv-item"> 我的项目 <i class="icon icon-close hidden"></i></div>
          <div class="priv-item"> 我的项目 <i class="icon icon-close hidden"></i></div>
        </div>
        <?php echo html::commonButton ('<i class="icon icon-plus"></i>' . $lang->group->add, '', 'btn btn-primary');?>
      </div>
    </div>
    <div class="priv-panel mt-m">
      <div class="panel-title">依赖的权限</div>
      <div class="panel-content">
        <div class="priv-title"><i class="icon icon-caret-right"></i>地盘</div>
        <div class="panel-list">
          <div class="priv-item"> 地盘仪表盘 <i class="icon icon-close hidden"></i></div>
          <div class="priv-item"> 地盘仪表盘 <i class="icon icon-close hidden"></i></div>
          <div class="priv-item"> 我的项目 <i class="icon icon-close hidden"></i></div>
          <div class="priv-item"> 我的项目 <i class="icon icon-close hidden"></i></div>
        </div>
        <?php echo html::commonButton ('<i class="icon icon-plus"></i>' . $lang->group->add, '', 'btn btn-primary');?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

