<?php include '../../common/view/header.lite.html.php';?>
<style> 
.actions {margin-top: 10px;}
.actions .btn {margin-right: 5px;}
.table {border: 1px solid #ddd;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo '<span title="' . $story->title . '">' . $story->title . '</span>';?>
        <small><?php echo $lang->arrow . $lang->story->URChanged;?></small>
      </h2>
    </div>
    <table class='table table-fixed'>
      <thead>
        <tr>
          <th class='w-60px'><?php echo $lang->story->id;?></th>
          <th><?php echo $lang->story->title;?></th>
          <th class='w-100px'><?php echo $lang->story->status;?></th>
          <th class='w-70px'><?php echo $lang->story->version;?></th>
          <th class='w-100px'><?php echo $lang->story->openedBy;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($changedStories as $story):?>
          <tr>
            <td><?php echo $story->id;?></td>
            <td><?php echo $story->title;?></td>
            <td style='color: red'><?php echo $lang->story->changed;?></td>
            <td><?php echo $story->version;?></td>
            <td><?php echo zget($users, $story->openedBy);?></td>
         </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="alert alert-info mg-0">
    <?php echo $lang->story->changeTips;?>
    </div>
    <div class='actions text-center'>
      <?php echo html::a(inlink('processstorychange', "id=$storyID&result=no"), $lang->story->changeList['no'], '', "class='btn btn-secondary'");?>
      <?php echo html::a(inlink('change', "id=$storyID"), $lang->story->changeList['yes'], '', "class='btn btn-primary showinonlybody'");?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
