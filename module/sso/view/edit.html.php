<?php include '../../common/view/header.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
  <table class='table table-form'> 
    <caption><?php echo $lang->sso->edit;?></caption>
    <tr>
      <th><?php echo $lang->sso->title;?></th>
      <td><?php echo html::input('title', $auth->title, "class='form-control'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->sso->code;?></th>
      <td><?php echo $code;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->sso->key;?></th>
      <td>
        <?php echo html::input('key', $auth->key, "class='form-control' readonly='readonly'");?>
        <?php echo html::a('javascript:void(0)', $lang->sso->createKey, '', 'onclick="createKey()"')?>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->sso->ip;?></th>
      <td><?php echo html::input('ip', $auth->ip, "class='text-5'");?></td>
    </tr>
    <tr><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
