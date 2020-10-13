<?php include '../../common/view/header.html.php';?>
<style>
table td{overflow: hidden;white-space: nowrap;text-overflow: ellipsis;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $activity->id;?></span>
        <?php echo isonlybody() ? ('<span title="' . $activity->name. '">' . $activity->name. '</span>') . $lang->arrow . $lang->stakeholder->viewIssue : html::a($this->createLink('activity', 'view', 'activity=' . $activity->id), $activity->name);?>
        <?php if(!isonlybody()):?>
        <?php endif;?>
      </h2>
      <div class='pull-right'>
        <?php common::printLink('issue', 'create', 'owner=show&account=&activityID=' . $activity->id, "<i class='icon icon-plus'></i>" . $lang->issue->create, '', "class='btn btn-primary'");?>
      </div>
    </div>
    <?php if(!empty($issues)):?>
    <table class='table'>
      <thead> 
        <tr>
          <th class='w-50px'><?php echo $lang->issue->id;?></th>
          <th class='w-100px'><?php echo $lang->issue->type;?></th>
          <th><?php echo $lang->issue->title;?></th>
          <th class='w-80px'><?php echo $lang->issue->severity;?></th>
          <th class='w-80px'><?php echo $lang->issue->pri;?></th>
          <th class='w-80px'><?php echo $lang->issue->owner;?></th>
          <th class='w-150px'><?php echo $lang->issue->desc;?></th>
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
         <td><?php echo $issue->desc;?></td>
        </tr>
        <?php endforeach;?>
      </tbody> 
    </table>
    <?php else:?>
    <div class='table-empty-tip'><?php echo $lang->noData;?></div>
    <?php endif;?>
  </div>
</div>

<?php include '../../common/view/footer.html.php';?>
