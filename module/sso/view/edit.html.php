<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('globe');?></span>
      <strong><?php echo $lang->sso->common;?></strong>
      <small class='text-muted'> <?php echo $lang->sso->edit;?> <?php echo html::icon('pencil');?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-90px'><?php echo $lang->sso->title;?></th>
        <td><?php echo html::input('title', $auth->title, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->sso->code;?></th>
        <td><?php echo $code;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->sso->key;?></th>
        <td>
          <div class='input-group'>
          <?php echo html::input('key', $auth->key, "class='form-control' readonly='readonly'");?>
          <span class='input-group-btn'><?php echo html::a('javascript:void(0)', $lang->sso->createKey, '', 'onclick="createKey()" class="btn"')?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->sso->ip;?></th>
        <td><?php echo html::input('ip', $auth->ip, "class='form-control'");?></td>
      </tr>
      <tr><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
