<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class="main-header">
      <h2><?php echo $lang->measurement->editDerivation;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->measurement->name;?></th>
            <td><?php echo html::input('name', $measurement->name, 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->purpose;?></th>
            <td><?php echo html::input('purpose', $measurement->purpose, 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->aim;?></th>
            <td><?php echo html::input('aim', $measurement->aim, 'class="form-control"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->definition;?></th>
            <td><?php echo html::input('definition', '', 'class="form-control" readonly placeholder="'.$lang->measurement->selectFormula.'"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->analyst;?></th>
            <td><?php echo html::select('analyst', $users, $measurement->analyst, 'class="form-control chosen"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->analysisMethod;?></th>
            <td><?php echo html::input('analysisMethod', $measurement->analysisMethod, 'class="form-control"');?></td>
            <td></td>
          </tr>
          <?php include './editcollectconf.html.php';?>
          <tr>
            <th><?php echo $lang->measurement->noticeScope;?></th>
            <td><?php echo html::input('scope', $measurement->scope, 'class="form-control"');?></td>
            <td></td>
          </tr>
        </tbody>
        <tfoot>
          <tr class='form-actions text-center'>
            <td colspan='3'><?php echo html::submitButton();echo html::backButton();?></td>
          </tr>
        </tfoot>
      </table>
      <?php echo html::hidden('measurementID', $measurement->id);?>
      <?php echo html::hidden('definitionMethods', $measurement->definition, 'autocomplete="off"');?>
    </form>
    <?php include './definition.html.php';?>
  </div>
</div>
<script>
$('#subNavbar ul li[data-id="settips"]').removeClass('active');
$('#subNavbar ul li[data-id="template"]').removeClass('active');
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
