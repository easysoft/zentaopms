<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a('###', "<span class='text'> {$lang->gitlab->manageProjectMembers}</span>");?>
    </span>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='teamForm'>
    <table class='table-form'>
      <thead>
        <tr class='text-center'>
          <th class='c-names'><?php echo $lang->gitlab->group->memberName;?></th>
          <th class='c-levels'><?php echo $lang->gitlab->group->memberAccessLevel;?></th>
          <th class='c-date'><?php echo $lang->gitlab->group->memberExpiresAt;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php if(!empty($repo->acl->users)):?>
        <?php foreach($repo->acl->users as $user):?>
        <tr>
          <td><?php echo html::input("names[$i]", $users[$user], "class='form-control' readonly");?></td>
          <td><?php echo html::select("levels[$i]", array(''=>'') + $this->lang->gitlab->accessLevels, isset($userAccessData[$user]) ? $userAccessData[$user]->access_level : '', "class='form-control chosen'");?></td>
          <td>
            <?php echo html::input("expires[$i]", isset($userAccessData[$user]) ? $userAccessData[$user]->expires_at : '', "class='form-control form-date'");?>
            <?php echo html::hidden("accounts[$i]", $user);?>
          </td>
          <td class='c-actions text-center'>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endif;?>

        <?php for($j = 0; $j < 5; $j ++):?>
        <tr class='addedItem'>
          <td><?php echo html::select("accounts[$i]", array(''=>'') +$users, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("levels[$i]", array(''=>'') + $this->lang->gitlab->accessLevels, '', "class='form-control chosen'");?></td>
          <td><?php echo html::input("expires[$i]", '', "class='form-control form-date'");?></td>
          <td class='c-actions text-center'>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::submitButton('', '', 'hidden btn btn-wide btn-primary');
              echo html::commonButton($lang->save, 'onclick="saveMembers()" id="saveBtn"', 'btn btn-wide btn-primary');

              echo html::backButton();
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php js::set('i', $i);?>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td><?php echo html::select("accounts[]", array(''=>'') +$users, '', "class='form-control'");?></td>
      <td><?php echo html::select("levels[]", array(''=>'') + $this->lang->gitlab->accessLevels, '', "class='form-control'");?></td>
      <td><?php echo html::input("expires[$i]", '', "class='form-control form-date'");?></td>
      <td class='c-actions text-center'>
        <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
        <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
