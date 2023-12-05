<?php
/**
 * The create view file of article module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     article
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('system');?></div>
</div>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><i class='icon-refresh'></i> <?php echo $lang->search->index;?></h2>
  </div>
  <div>
    <div class='form-group'>
      <ul id='resultBox'></ul>
    </div>
    <div class='from-group'><?php echo html::a(inlink('buildIndex'), $lang->search->buildIndex, '', "class='btn btn-primary' id='execButton'");?></div>
  </div>
</div>
</div>
<script>
$('#subNavbar li[data-id=system]').addClass('active');
</script>
<?php include '../../common/view/footer.html.php';?>
