<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-right">
    <?php common::printLink('stage', 'batchCreate', "", "<i class='icon icon-plus'></i>" . $lang->stage->batchCreate, '', "class='btn btn-primary'");?>
    <?php common::printLink('stage', 'create', "", "<i class='icon icon-plus'></i>" . $lang->stage->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
