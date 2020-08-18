<?php include "../../common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <a href="" class="btn btn-link btn-active-text">
    <span class="text"><?php echo $lang->risk->browse;?></span>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('risk', 'batchCreate', "", "<i class='icon icon-plus'></i>" . $lang->risk->batchCreate, '', "class='btn btn-primary'");?>
    <?php common::printLink('risk', 'create', "", "<i class='icon icon-plus'></i>" . $lang->risk->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php include "../../common/view/footer.html.php"?>

