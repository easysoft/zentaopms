<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <?php echo $lang->measurement->createTemplate;?>
      </h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tr>
          <th><?php echo $lang->meastemplate->name;?></th>
          <td><?php echo html::input('name', '', "class='form-control'");?></td>
        </tr>
        <?php if(helper::hasFeature('scrum_measrecord')):?>
        <tr>
          <th><?php echo $lang->measurement->model;?></th>
          <td><?php echo html::radio('model', $lang->measurement->modelList, 'waterfall');?></td>
        </tr>
        <?php else:?>
        <?php echo html::hidden('model', 'waterfall');?>
        <?php endif?>
        <tr>
          <th><?php echo $lang->meastemplate->content;?></th>
          <td colspan='2' class='tips'>
            <?php echo html::textarea('content', '', "class='form-control'");?>
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
<script>
$('#subNavbar ul li[data-id="settips"]').removeClass('active');
$('#subNavbar ul li[data-id="define"]').removeClass('active');
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
