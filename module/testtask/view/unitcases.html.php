<?php
/**
 * The unit view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/treetable.html.php';
js::set('flow', $config->global->flow);
?>
<style>
<?php if(isonlybody()):?>
#mainMenu {border-bottom:1px solid #ddd;}
.main-table {padding-top:60px;}
<?php endif;?>
</style>
<?php if($config->global->flow == 'full'):?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->testtaskList ? $this->session->testtaskList : $this->createLink('testtask', 'browseUnits');?>
    <?php echo html::a($browseLink, '<i class="icon-goback icon-back"></i> ' . $lang->goback, '', "class='btn btn-secondary' data-app={$this->app->tab}");?>
    <?php if(isonlybody()) echo "<div class='page-title'>{$task->name}</div>";?>
  </div>
  <div class='btn-toolbar pull-right <?php if(isonlybody()) echo 'hidden';?>'>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>
        <i class='icon icon-export muted'></i> <?php echo $lang->export ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' id='exportActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'export') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=t1.id&taskID=0&browseType=") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->export, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
  </div>
</div>
<?php endif;?>
<div class="main-table" data-ride="table" data-checkable="false" data-group="true">
  <?php include 'unitgroup.html.php';?>
</div>
<script>
$(function()
{
    $('#subNavbar li[data-id="case"]').addClass('active');
})
</script>
<?php include '../../common/view/footer.html.php';?>
