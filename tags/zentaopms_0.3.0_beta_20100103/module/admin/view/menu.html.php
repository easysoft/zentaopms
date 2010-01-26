<?php $companyVar = "companyID={$app->company->id}";?>
<table class='table-1'>
  <caption><?php echo $lang->admin->company;?></caption>
  <tr>
    <td>
      <?php echo html::a($this->createLink('admin',   'browsecompany'),      $lang->company->browse);?><br />
      <?php echo html::a($this->createLink('company', 'create'),             $lang->company->create);?><br />
      <?php echo html::a($this->createLink('company', 'edit', $companyVar ), $lang->company->edit);?>  <br />
    </td>
  </tr>
</table>
<table class='table-1'>
  <caption><?php echo $lang->admin->user;?></caption>
  <tr>
    <td>
      <?php echo html::a($this->createLink('admin', 'browseuser', $companyVar), $lang->user->browse);?><br />
      <?php echo html::a($this->createLink('user',  'create',     $companyVar), $lang->user->create);?><br />
    </td>
  </tr>
  <table class='table-1'>
  <caption><?php echo $lang->admin->group;?></caption>
  <tr>
    <td>
      <?php echo html::a($this->createLink('admin', 'browsegroup', $companyVar), $lang->group->browse);?><br />
      <?php echo html::a($this->createLink('group', 'create', $companyVar), $lang->group->create);?><br />
    </td>
  </tr>
</table>

