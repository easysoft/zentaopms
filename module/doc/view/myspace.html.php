<?php
/**
 * The browse view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<?php js::set('appTab', $app->tab)?>
<?php js::set('treeData', $libTree)?>
<div id="mainMenu" class="clearfix">
  <div id="leftBar" class="btn-toolbar pull-left">
    <?php echo $objectDropdown;?>
    <?php if(!empty($libTree)):?>
    <?php foreach($lang->doc->featureBar['tableContents'] as $barType => $barName):?>
    <?php $active     = $barType == $browseType ? 'btn-active-text' : '';?>
    <?php $linkParams = "type=$type&libID=$libID&moduleID=$moduleID&browseType=$barType";?>
    <?php echo html::a($this->createLink('doc', $app->rawMethod, $linkParams), "<span class='text'>{$barName}</span>" . ($active ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' id='{$barType}Tab'");?>
    <?php endforeach;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->doc->searchDoc;?></a>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-right">
  <?php
  if($canExport)
  {
      $exportLink = $this->createLink('doc', 'mine2export', "libID=$libID&moduleID=$moduleID", 'html', true);
      echo html::a($exportLink, "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export' data-width='480px' id='mine2export'");
  }

  if(common::hasPriv('doc', 'createLib'))
  {
      echo html::a(helper::createLink('doc', 'createLib', "type=mine"), '<i class="icon icon-plus"></i> ' . $this->lang->doc->createLib, '', 'class="btn btn-secondary iframe" data-width="800px"');
  }

  if($libID and common::hasPriv('doc', 'create')) echo $this->doc->printCreateBtn($lib, $moduleID);
  ?>
  </div>
</div>
<div id='mainContent'class="fade <?php if(!empty($libTree)) echo 'flex';?>">
<?php if(empty($libTree)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->doc->noLib;?></span>
    </p>
  </div>
<?php else:?>
  <div id='sideBar' class="panel side side-col col overflow-auto">
    <?php include 'lefttree.html.php';?>
  </div>
  <div class="sidebar-toggle flex-center"><i class="icon icon-angle-left"></i></div>
  <div class="main-col flex-full overflow-visible flex-auto">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module=<?php echo $type . $libType . 'Doc';?>></div>
    <?php include 'mydoclist.html.php'; ?>
  </div>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
