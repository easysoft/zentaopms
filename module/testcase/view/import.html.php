<?php include '../../common/view/header.lite.html.php';?>
<div class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->testcase->importFile;?></h2>
  </div>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' style="padding:30px">
  <table class='table table-form w-p100'>
    <tr>
      <td align='center'>
        <input type='file' name='file' class='form-control'/>
      </td>
      <td>
        <?php echo html::select('encode', $config->charsets[$this->cookie->lang], 'utf-8', "class='form-control'");?>
      </td>
      <td>
        <?php echo html::submitButton('', '', 'btn btn-primary btn-wide');?>
      </td>
    </tr>
  </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
