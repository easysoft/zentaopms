<?php include '../../common/view/header.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('globe');?></span>
      <strong><?php echo $lang->sso->common;?></strong>
      <small class='text-muted'> <?php echo $lang->sso->create;?> <?php echo html::icon('plus');?></small>
    </div>
  </div>
  <div class='row'>
    <div class='col-md-6'>
      <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
        <table class='table table-form'> 
          <tr>
            <th class='w-80px'><?php echo $lang->sso->title;?></th>
            <td><?php echo html::input('title', '', "class='form-control' placeholder='{$lang->sso->note->title}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->sso->code;?></th>
            <td><?php echo html::input('code', '', "class='form-control' placeholder='{$lang->sso->note->code}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->sso->key;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('key', $key, "class='form-control' readonly='readonly'");?>
                <span class='input-group-btn'><?php echo html::a('javascript:void(0)', $lang->sso->createKey, '', 'onclick="createKey()" class="btn"')?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->sso->ip;?></th>
            <td><?php echo html::input('ip', '', "class='form-control' placeholder='{$lang->sso->note->ip}'");?></td>
          </tr>
          <tr><td></td><td><?php echo html::submitButton() . html::backButton();?></td></tr>
        </table>
      </form>
    </div>
    <div class='col-md-6'>
      <div class='article-content instruction alert'>
        <?php echo $lang->sso->instruction;?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
