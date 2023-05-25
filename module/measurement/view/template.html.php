<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
  <?php include './menu.html.php';?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if($type == 'complex') common::printLink('measurement', 'createTemplate', "", "<i class='icon icon-plus'></i>" . $lang->measurement->createTemplate, '', "class='btn btn-primary'");?>
    <?php if($type == 'single') common::printLink('report', 'custom', "step=0&reportID=0&from=cmmi", "<i class='icon icon-plus'></i>" . $lang->measurement->createSingle, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class='side-col'>
    <div class='panel'>
      <div class='panel-body'>
        <div class='list-group'>
        <?php $singleActive  = $type == 'single'  ? 'selected' : '';?>
        <?php $complexActive = $type == 'complex' ? 'selected' : '';?>
        <?php echo html::a($this->createLink('measurement','template', "type=complex"), '<span class="text">' . $lang->meastemplate->complex . '</span>', '', "class='$complexActive'");?>
        <?php echo html::a($this->createLink('measurement','template', "type=single"), '<span class="text">' . $lang->meastemplate->single . '</span>', '', "class='$singleActive'");?>
        </div>
      </div>
    </div>
  </div>
  <div class="main-col">
    <?php if(empty($templates)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
    </div>
    <?php else:?>
    <?php if($type == 'single') include './singletemplate.html.php';?>
    <?php if($type == 'complex') include './complextemplate.html.php';?>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
