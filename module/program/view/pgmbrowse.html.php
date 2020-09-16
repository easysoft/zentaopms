<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('orderBy', $orderBy);?>
<?php if($programType == 'bygrid'):?>
<style>
#mainMenu{padding-left: 10px; padding-right: 10px;}
</style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "status=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('mine', array('1' => $lang->program->mine), '', $this->cookie->mine ? 'checked=checked' : '');?>
  </div>
  <div class='pull-right'>
    <div class='btn-group'>
      <?php echo html::a('javascript:setProgramType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title={$lang->program->bygrid} class='btn btn-icon " . ($programType == 'bygrid' ? 'text-primary' : '') . "'");?>
      <?php echo html::a('javascript:setProgramType("bylist")', "<i class='icon icon-bars'></i>", '', "title={$lang->program->bylist} class='btn btn-icon " . ($programType == 'bylist' ? 'text-primary' : '') . "'");?>
    </div>
    <?php common::printLink('program', 'export', "status=$status&orderBy=$orderBy", "<i class='icon-export muted'> </i>" . $lang->export, '', "class='btn btn-link export'")?>
    <?php if(isset($lang->pageActions)) echo $lang->pageActions;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->program->noPGM;?></span> <?php common::printLink('program', 'createguide', '', "<i class='icon icon-plus'></i> " . $lang->program->create, '', "class='btn btn-info' data-toggle=modal");?></p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <?php 
    if($programType == 'bygrid')
    {
        include 'browsebygrid.html.php';
    }
    else
    {
        include 'browsebylist.html.php';
    }
    ?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
