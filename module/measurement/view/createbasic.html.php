<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class="main-header">
      <h2><?php echo $lang->measurement->create;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->measurement->name;?></th>
            <td><?php echo html::input('name', '', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->code;?></th>
            <td><?php echo html::input('code', '', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->scope;?></th>
            <td><?php echo html::select('scope', $lang->measurement->scopeList, 'program', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->object;?></th>
            <td><?php echo html::select('object', $lang->measurement->objectList, 'program', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->purpose;?></th>
            <td><?php echo html::select('purpose', $lang->measurement->purposeList, 'program', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->unit;?></th>
            <td><?php echo html::input('unit', '', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->measurement->definition;?></th>
            <td><?php echo html::textarea('definition', '', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <?php include './createcollectconf.html.php';?>
        </tbody>
        <tfoot>
          <tr class='form-actions text-center'>
            <td colspan='3'><?php echo html::submitButton(); echo html::backButton();?></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script>
$('#subNavbar ul li[data-id="settips"]').removeClass('active');
$('#subNavbar ul li[data-id="template"]').removeClass('active');
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
