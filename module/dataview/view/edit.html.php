<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('lang', $lang->dataview);?>
<?php js::set('dataview', $dataview);?>
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
          <td><?php echo html::select('group', $groups, $dataview->group, "class='form-control chosen' data-drop_direction='down'");?></td>
        </tr>
        <tr>
          <th class='thWidth'><?php echo $lang->dataview->name;?></th>
          <td class='w-400px'>
            <?php echo html::input('name', $dataview->name, 'class="form-control"');?>
          </td>
        </tr>
        <tr class="error hidden">
          <th></th>
          <td colspan='2'></td>
        </tr>
        <tr>
          <td></td>
          <td class='form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
