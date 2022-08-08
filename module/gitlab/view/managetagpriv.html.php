<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a('###', "<span class='text'> {$lang->gitlab->browseTagPriv}</span>");?>
    </span>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='privForm'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th class='c-names'><?php echo $lang->gitlab->tag->name;?></th>
          <th class='c-levels'><?php echo $lang->gitlab->tag->accessLevel;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php if(!empty($hasAccessTags)):?>
        <?php foreach($hasAccessTags as $tag):?>
        <tr>
          <td><?php echo html::input("names[$i]", $tag->name, "class='form-control' readonly");?></td>
          <td><?php echo html::select("createLevels[$i]", $lang->gitlab->branch->branchCreationLevelList, $tag->createAccess, "class='form-control user-picker'");?></td>
          <td class='c-actions text-center'>
            <?php echo html::hidden("tags[$i]", $tag->name);?>
            <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
            <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endif;?>

        <?php for($j = 0; $j < 5; $j ++):?>
        <tr class='addedItem'>
          <td><?php echo html::select("tags[$i]", array(''=>'') + $noAccessTags, '', "class='form-control user-picker'");?></td>
          <td><?php echo html::select("createLevels[$i]", $lang->gitlab->branch->branchCreationLevelList, 40, "class='form-control user-picker'");?></td>
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
          <td colspan='3' class='text-center form-actions'>
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
      <td><?php echo html::select("tags[]", array(''=>'') + $noAccessTags, '', "class='form-control'");?></td>
      <td><?php echo html::select("createLevels[]", $lang->gitlab->branch->branchCreationLevelList, 40, "class='form-control'");?></td>
      <td class='c-actions text-center'>
        <?php echo html::a('javascript:;', "<i class='icon-plus'></i>", '', "onclick='addItem(this)' class='btn btn-link'");?>
        <?php echo html::a('javascript:;', "<i class='icon icon-close'></i>", '', "onclick='deleteItem(this)' class='btn btn-link'");?>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
