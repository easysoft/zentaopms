<?php
/**
 * The browse view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<?php js::set('appTab', $app->tab)?>
<div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo $type . 'Doc';?>></div>
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
    <div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" style="min-width: 400px" id="queryBox" data-module=<?php echo $type . $libType . 'Doc';?>></div>
    <?php include 'doclist.html.php'; ?>
  </div>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
