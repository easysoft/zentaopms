<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1'> 
    <caption><?php echo $lang->sso->create;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->title;?></th>
      <td><?php echo html::input('title', '', "class='form-control' placeholder='{$lang->sso->note->title}'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->code;?></th>
      <td><?php echo html::input('code', '', "class='form-control' placeholder='{$lang->sso->note->code}'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->key;?></th>
      <td>
        <?php echo html::input('key', $key, "class='form-control' readonly='readonly'");?>
        <?php echo html::a('javascript:void(0)', $lang->sso->createKey, '', 'onclick="createKey()"')?>
      </td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->sso->ip;?></th>
      <td><?php echo html::input('ip', '', "class='text-5' placeholder='{$lang->sso->note->ip}'");?></td>
    </tr>
    <tr><td></td><td colspan='2' class='text-left'><?php echo html::submitButton() . html::backButton();?></td></tr>
  </table>
</form>
<div class="instruction"><?php echo $lang->sso->instruction;?></div>
<?php include '../../common/view/footer.html.php';?>
