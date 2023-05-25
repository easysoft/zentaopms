<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->measurement->editTemplate;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tr>
          <th><?php echo $lang->measurement->name;?></th>
          <td><?php echo html::input('name', $template->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->measurement->content;?></th>
          <td colspan='2' class='tips'>
            <?php echo html::textarea('content', $template->content, "class='form-control'");?>
            <i class="icon icon-exclamation-sign icon-rotate-180"></i> 
            <?php echo $lang->measurement->tips->click2InsertData;?>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='form-actions text-center'><?php echo html::submitButton() . html::backButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include './holdermodal.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
