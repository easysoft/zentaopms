<?php
/**
 * The editManagePriv view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Feilong Guo <guofeilong@cnezsoft.com>
 * @package     group
 * @version     $id editManagePriv.html.php 4769 2023-03-07 07:24:21Z guofeilong $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class='flex-space-center'>
  <div class="btn-toolbar">
    <?php echo html::a($this->createLink('group', 'browse', ''), '<i class="icon icon-angle-left"></i>' . $lang->goback, '', 'class="btn btn-primary"');?>
    <?php $active = empty($view) ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('editManagePriv', "browseType=&view="), $lang->group->all, '', "class='btn btn-link $active'");?>
    <?php
    $i = 0;
    $params = "browseType=&view=%s";
    $config->group->maxToolBarCount -= $browseType == 'bycard' ? 1 : 2;
    foreach($lang->mainNav as $moduleMenu => $title)
    {
        if(!is_string($title)) continue;
        $i ++;
        if($i == $config->group->maxToolBarCount) echo '<div class="btn-group"><a href="javascript:;" data-toggle="dropdown" class="btn btn-link">' . $lang->group->more . '<span class="caret"></span></a><ul class="dropdown-menu">';
        $active = $view == $moduleMenu ? 'btn-active-text' : '';
        if($i >= $config->group->maxToolBarCount) echo '<li>';
        echo html::a(inlink('editManagePriv', sprintf($params, $moduleMenu)), "<span class='text'>" . strip_tags(substr($title, 0, strpos($title, '|'))) . '</span>', '', "class='btn btn-link $active'");
        if($i >= $config->group->maxToolBarCount) echo '</li>';
    }
    if($i >= $config->group->maxToolBarCount) echo '</ul></div>';
    ?>

    <?php $active = $view == 'general' ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('editManagePriv', sprintf($params, 'general')), "<span class='text'>{$lang->group->general}</span>", '', "class='btn btn-link $active'");?>
    <?php if($browseType != 'bycard'):?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
    <?php endif;?>
  </div>
  <div class="btn-toolbar">
    <div class="btn-group">
      <a href="#" data-type="bycard" class="btn btn-icon btn-switch <?php if($browseType == 'bycard') echo 'text-primary' ?>"><i class="icon icon-cards-view"></i></a>
      <a href="#" data-type="bylist" class="btn btn-icon btn-switch <?php if($browseType == 'bylist' or $browseType == 'bysearch') echo 'text-primary' ?>"><i class="icon icon-list"></i></a>
    </div>
    <?php if(common::hasPriv('group', 'createPriv')) echo html::a($this->createLink('group', 'createPriv', '', '', true), $lang->group->createPriv, '', 'class="btn btn-primary iframe"');?>
    <?php if(common::hasPriv('group', 'managePrivPackage')) echo html::a($this->createLink('group', 'managePrivPackage', ''), $lang->group->managePrivPackage, '', 'class="btn btn-primary"');?>
    <div class="dropdown">
      <a href="javascript:;" id='batchActions' data-toggle="dropdown" class="btn btn-primary disabled"><?php echo $lang->group->batchActions?> <span class="caret"></span></a>
      <ul class="dropdown-menu pull-right">
        <?php if(common::hasPriv('group', 'addRelation')):?>
        <li><a href="#" class="iframe" id='batchSetDepend'><?php echo $lang->group->batchSetDependency?></a></li>
        <li><a href="#" class="iframe" id='batchSetRecommend'><?php echo $lang->group->batchSetRecommendation?></a></li>
        <?php endif;?>
        <?php if(common::hasPriv('group', 'batchDeleteRelation')):?>
        <li><a href="#" class="iframe" id='batchDeleteDepend'><?php echo $lang->group->batchDeleteDependency?></a></li>
        <li><a href="#" class="iframe" id='batchDeleteRecommend'><?php echo $lang->group->batchDeleteRecommendation?></a></li>
        <?php endif;?>
      </ul>
    </div>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module="priv"></div>
<div id='mainContent'>
  <div class="main main-content">
  <?php if($browseType == 'bycard'):?>
    <?php include 'editmanageprivbycard.html.php';?>
  <?php else:?>
  <?php include 'editmanageprivbylist.html.php';?>
  <?php endif;?>
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
</div>
<?php js::set('canDeleteRelation', common::hasPriv('group', 'deleteRelation'));?>
<?php include '../../common/view/footer.html.php';?>
