<?php
/**
 * The view lib file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     caselib
 * @version     $Id: view.html.php 4141 2013-01-18 06:15:13Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->caselibList ? $this->session->caselibList : $this->createLink('caselib', 'browse', "libID=$lib->id");?>
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
  <div class="main-col">
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->caselib->legendDesc;?></div>
        <div class='detail-content article-content'><?php echo $lib->desc;?></div>
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
    <div class='main-actions'>
      <nav class="container"></nav>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php echo "<div class='divider'></div>";?>
        <?php echo $this->caselib->buildOperateMenu($lib, 'view');?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
