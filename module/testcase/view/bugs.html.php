<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span><?php echo html::icon($lang->icons['report']);?></span>
    <small class='text-muted'> <?php echo $lang->testcase->bugs;?></small>
  </div>
</div>
<div class='bugsList'>
  <form class='form-condensed' target='hiddenwin'>
    <table class='table table-fixed'>
      <thead>
        <tr class='text-center'>
          <th class='w-20px'>    <?php echo $lang->idAB;?></th>
          <th class='w-p30'>   <?php echo $lang->bug->title;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class='w-type'>  <?php echo $lang->bug->type;?></th>
          <th class='w-40px'><?php echo $lang->statusAB;?></th>
          <th class='w-user'>  <?php echo $lang->bug->assignedTo;?></th>
          <th class='w-50px'>  <?php echo $lang->bug->resolvedBy;?></th>
          <th class='w-50px'>  <?php echo $lang->bug->resolution;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($bugs as $key => $bug):?>
        <tr class='text-center'>
          <td><?php echo $bug->id;?></td>
          <td class='text-left' title="<?php echo $bug->title?>"><?php echo $bug->title;?></td>
          <td><span class='<?php echo 'pri' . zget($lang->bug->priList, $bug->pri, $bug->pri)?>'><?php echo $bug->pri == '0' ? '' : zget($lang->bug->priList, $bug->pri, $bug->pri);?></span></td>
          <td><?php echo $lang->bug->typeList[$bug->type];?></td>
          <td><?php echo $lang->bug->statusList[$bug->status];?></td>
          <td><?php echo zget($users, $bug->assignedTo, $bug->assignedTo);?></td>
          <td><?php echo zget($users, $bug->resolvedBy, $bug->resolvedBy);?></td>
          <td><?php echo $lang->bug->resolutionList[$bug->resolution];?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
