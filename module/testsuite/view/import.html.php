<?php include '../../common/view/header.lite.html.php';?>
<div class='container mw-600px'>
  <form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' style="padding:30px">
  <table class='table table-form'>
    <tr>
      <td align='center'>
        <input type='file' name='file' class='form-control'/>
      </td>
      <td>
        <?php echo html::select('encode', $config->charsets[$this->cookie->lang], 'utf-8', "class='form-control'");?>
      </td>
      <td>
        <?php echo html::submitButton();?>
      </td>
    </tr>
  </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
