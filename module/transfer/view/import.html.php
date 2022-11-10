<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style>input[type=file]:focus, input[type=checkbox]:focus, input[type=radio]:focus {outline: none;}</style>
<main>
  <div class="container">
    <div id="mainContent" class='main-content'>
      <div class='main-header'>
        <h2><?php echo $title;?></h2>
      </div>
      <form enctype='multipart/form-data' method='post' target='hiddenwin' style='padding: 20px 0 15px'>
        <table class='table table-form w-p100'>
          <tr>
            <td><input type='file' name='file' class='form-control'/></td>
            <td class='w-150px'><?php echo html::submitButton('', '', 'btn btn-primary');?></td>
          </tr>
          <tr>
            <td class='text-left' colspan='2'><span class='label label-info'><?php echo $lang->transfer->importNotice;?></span></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</main>
<script>
$.cookie('maxImport', 0);
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
