<?php
/**
 * The view lib file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     caselib
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->caseList ? $this->session->caseList : $this->createLink('caselib', 'browse', "libID=$lib->id");?>
    <?php common::printBack($browseLink, 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $lib->id;?></span>
      <span class='text' title='<?php echo $lib->name;?>'><?php echo $lib->name;?></span>
      <?php if($lib->deleted):?>
      <span class='label label-danger'><?php echo $lang->caselib->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent'>
  <div class='cell'>
    <div class='detail'>
      <div class='detail-title'><?php echo $lang->caselib->legendDesc;?></div>
      <div class='detail-content article-content'><?php echo $lib->desc;?></div>
    </div>
    <?php include '../../common/view/action.html.php';?>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
  <div class="btn-toolbar">
    <?php
    common::printBack($browseLink);
    if(!$lib->deleted)
    {
        echo "<div class='divider'></div>";
        common::printIcon('caselib', 'edit',   "libID=$lib->id");
        common::printIcon('caselib', 'delete', "libID=$lib->id", '', 'button', 'trash', 'hiddenwin');
    }
    ?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
