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
    <?php foreach($lang->program->PGMFeatureBar as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($status == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('pgmbrowse', "status=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('showClosed', array('1' => $lang->program->PGMShowClosed), '', $this->cookie->showClosed ? 'checked=checked' : '');?>
  </div>
  <div class='pull-right'>
    <?php if(isset($lang->pageActions)) echo $lang->pageActions;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->program->noPGM;?></span> <?php common::printLink('program', 'pgmcreate', '', "<i class='icon icon-plus'></i> " . $lang->program->PGMCreate, '', "class='btn btn-info'");?></p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <?php 
    if($programType == 'bygrid')
    {
        include 'pgmbrowsebygrid.html.php';
    }
    else
    {
        include 'pgmbrowsebylist.html.php';
    }
    ?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
