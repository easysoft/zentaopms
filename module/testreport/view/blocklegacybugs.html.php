<tr>
  <th class='text-top w-80px'><?php echo $lang->testreport->legacyBugs?></th>
  <td colspan='2'>
    <?php if($legacyBugs):?>
    <table class='table' id='bugs'>
      <thead>
        <tr>
          <th class='w-id'>   <?php echo $lang->idAB;?></th>
          <th class='w-pri'>  <?php echo $lang->priAB;?></th>
          <th>                <?php echo $lang->bug->title;?></th>
          <th class='w-user'> <?php echo $lang->openedByAB;?></th>
          <th class='w-user'><?php echo $lang->bug->resolvedBy;?></th>
          <th class='w-130px'><?php echo $lang->bug->resolvedDate;?></th>
          <th class='w-80px'> <?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php foreach($legacyBugs as $bug):?>
        <tr>
          <td><?php echo $bug->id?></td>
          <td><span class='pri<?php echo $bug->pri?>'><?php echo zget($lang->bug->priList, $bug->pri);?></span></td>
          <td class='text-left' title='<?php echo $bug->title?>'><?php echo $bug->title?></td>
          <td><?php echo zget($users, $bug->openedBy);?></td>
          <td><?php echo zget($users, $bug->resolvedBy);?></td>
          <td><?php if($bug->resolvedDate != '0000-00-00 00:00:00') echo substr($bug->resolvedDate, 2);?></td>
          <td><?php echo zget($lang->bug->statusList, $bug->status);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php else:?>
    <?php echo $lang->testreport->none;?>
    <?php endif?>
  </td>
</tr>
