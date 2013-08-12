<?php include '../../common/view/header.lite.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
<table class='table-1 mt-10px'>
  <caption><?php echo $lang->testcase->import?></caption>
  <tr>
    <td align='center'>
      <input type='file' name='file'/>
      <?php echo html::select('encode', $config->charsets[$this->cookie->lang], 'utf-8');?>
      <?php echo html::submitButton();?>
    </td>
  <tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
