<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='page-title'>
      <span title='<?php echo $title;?>' class='text'><?php echo $title;?></span>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <form method='post' target='hiddenwin' id='dataform' class="form-ajax">
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->dataview->group;?></th>
          <td><?php echo html::select('group', $groups, '', "class='form-control chosen' data-drop_direction='down'");?></td>
        </tr>
        <tr>
          <th class='thWidth'><?php echo $lang->dataview->name;?></th>
          <td class='w-400px'>
            <?php echo html::input('name', '', 'class="form-control"');?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->dataview->code;?></th>
          <td><?php echo html::input('code', '', "class='form-control'");?></td>
        </tr>
        <tr class="error hidden"><th></th><td></td></tr>
        <tr>
          <td colspan='2' class='form-actions text-center'>
            <?php echo html::submitButton() . html::hidden('sql') . html::hidden('fields') . html::hidden('langs');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
