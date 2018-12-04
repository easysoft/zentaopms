<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->downloadClient;?></h2>
  </div>
  <form method='post' target='hiddenwin'>
    <table class='w-p100'>
      <tr>
        <td>
          <?php echo html::radio('os', $lang->misc->client->osList, 'windows64', '', 'block');?>
        </td>
      </tr>
      <tr class='text-center'>
        <td>
          <?php echo html::submitButton($lang->misc->client->download, '', 'btn btn-primary');?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
