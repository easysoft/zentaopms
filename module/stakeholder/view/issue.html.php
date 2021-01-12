<?php include '../../common/view/header.html.php';?>
<style> table td{overflow: hidden;white-space: nowrap;text-overflow: ellipsis;} </style>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <?php if(!empty($issues)):?>
    <form class='main-table' method='post' id='issueForm'>
      <table class='table'>
        <thead> 
          <tr>
            <th class='w-50px'><?php echo $lang->issue->id;?></th>
            <th class='w-100px'><?php echo $lang->issue->type;?></th>
            <th><?php echo $lang->issue->title;?></th>
            <th class='w-80px'><?php echo $lang->issue->severity;?></th>
            <th class='w-80px'><?php echo $lang->issue->pri;?></th>
            <th class='w-80px'><?php echo $lang->issue->owner;?></th>
            <th class='w-120px'><?php echo $lang->issue->status;?></th>
            <th class='w-120px'><?php echo $lang->issue->createdDate;?></th>
            <th class='c-actions'><?php echo $lang->actions;?></th>
          </tr>
        </thead> 
        <tbody> 
          <?php foreach($issues as $issue):?>
          <tr>
           <td><?php echo $issue->id;?></td>
           <td><?php echo zget($lang->issue->typeList, $issue->type);?></td>
           <td><?php echo html::a($this->createLink('issue', 'view', "issueID=$issue->id"), $issue->title);?></td>
           <td><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
           <td><?php echo zget($lang->issue->priList, $issue->pri);?></td>
           <td><?php echo zget($users, $issue->owner);?></td>
           <td><?php echo zget($lang->issue->statusList, $issue->status);?></td>
           <td><?php echo substr($issue->createdDate, 0, 11);?></td>
           <td class='c-actions'>
             <?php common::printIcon('issue', 'resolve', "id=$issue->id", $issue, 'list', 'checked', '', 'iframe', 'yes', '');?>
             <?php common::printIcon('issue', 'edit', "id=$issue->id", $issue, 'list');?>
           </td>
          </tr>
          <?php endforeach;?>
        </tbody> 
      </table>
    </form>
    <?php else:?>
    <div class='table-empty-tip'><?php echo $lang->noData;?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
