<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo '<span>' . $lang->doc->selectLibType . '</span>';?></h2>
    </div>
  </div>
  <form method='post' class='form-ajax'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->doc->libType;?></th>
        <td class='w-p85'><?php echo html::radio('objectType', $lang->doc->libTypeList, 'product');?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php
          echo html::submitButton($lang->confirm);
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
