<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a('###', "<span class='text'> {$lang->gitlab->browseBranchPriv}</span>");?>
    </span>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='privForm'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th class='c-names'><?php echo $lang->gitlab->branch->name;?></th>
          <th class='c-levels'><?php echo $lang->gitlab->branch->mergeAllowed;?></th>
          <th class='c-levels'><?php echo $lang->gitlab->branch->pushAllowed;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php if(!empty($hasAccessBranches)):?>
        <?php foreach($hasAccessBranches as $branch):?>
        <tr>
          <td><?php echo html::input("names[$i]", $branch->name, "class='form-control' readonly");?></td>
          <td><?php echo html::select("mergeLevels[$i]", $lang->gitlab->branch->branchCreationLevelList, $branch->mergeAccess, "class='form-control user-picker'");?></td>
          <td>
            <?php echo html::select("pushLevels[$i]", $lang->gitlab->branch->branchCreationLevelList, $branch->pushAccess, "class='form-control user-picker'");?>
            <?php echo html::hidden("branches[$i]", $branch->name);?>
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
          <td><?php echo html::select("branches[$i]", array(''=>'') + $noAccessBranches, '', "class='form-control user-picker'");?></td>
          <td><?php echo html::select("mergeLevels[$i]", $lang->gitlab->branch->branchCreationLevelList, 40, "class='form-control user-picker'");?></td>
          <td><?php echo html::select("pushLevels[$i]", $lang->gitlab->branch->branchCreationLevelList, 40, "class='form-control user-picker'");?></td>
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
              echo html::commonButton($lang->save, 'onclick="savePriv()" id="saveBtn"', 'btn btn-wide btn-primary');
              echo html::backButton();
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php js::set('itemIndex', $i);?>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td><?php echo html::select("branches[]", array(''=>'') + $noAccessBranches, '', "class='form-control'");?></td>
      <td><?php echo html::select("mergeLevels[]", $lang->gitlab->branch->branchCreationLevelList, 40, "class='form-control'");?></td>
      <td><?php echo html::select("pushLevels[]", $lang->gitlab->branch->branchCreationLevelList, 40, "class='form-control'");?></td>
      <td class='c-actions text-center'>
        <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
        <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
