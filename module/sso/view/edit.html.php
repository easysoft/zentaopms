<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1'> 
    <caption><?php echo $lang->sso->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->title;?></th>
      <td><?php echo html::input('title', $auth->title, "class='text-3'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->code;?></th>
      <td><?php echo $code;?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->key;?></th>
      <td>
        <?php echo html::input('key', $auth->key, "class='text-3' readonly='readonly'");?>
        <?php echo html::a('javascript:void(0)', $lang->sso->createKey, '', 'onclick="createKey()"')?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->ip;?></th>
      <td><?php echo html::input('ip', $auth->ip, "class='text-5'");?></td>
    </tr>
    <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
