<?php
/**
 * The html template file of PGMBrowse method of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('orderBy', $orderBy);?>
<?php if($programType == 'bygrid'):?>
<style> #mainMenu{padding-left: 10px; padding-right: 10px;} </style>
<?php endif;?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->program->featureBar as $key => $label):?>
    <?php $active = $status == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php echo html::a(inlink('browse', "status=$key&orderBy=$orderBy"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php echo html::checkbox('showClosed', array('1' => $lang->program->showClosed), '', $this->cookie->showClosed ? 'checked=checked' : '');?>
  </div>
  <div class='pull-right'>
    <?php if(isset($lang->pageActions)) echo $lang->pageActions;?>
    <?php if(isset($this->config->maxVersion)):?>
    <?php common::printLink('project', 'createGuide', "programID=0&from=PGM", '<i class="icon icon-plus"></i>' . $lang->project->create, '', 'class="btn btn-primary" data-toggle="modal" data-target="#guideDialog"');?>
    <?php elseif($this->config->systemMode == 'new'):?>
    <?php common::printLink('project', 'create', "mode=scrum&programID=0", '<i class="icon icon-plus"></i>' . $lang->project->create, '', 'class="btn btn-primary"');?>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <?php if(empty($programs)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->program->noProgram;?></span>
      <?php common::printLink('program', 'create', '', "<i class='icon icon-plus'></i> " . $lang->program->create, '', "class='btn btn-info'");?>
    </p>
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
