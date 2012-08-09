<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<table class="cont-lt1">
  <tr valign='top'>
    <td class='side'>
      <?php include 'blockreportlist.html.php';?>
    </td>
    <td class='divider'></td>
    <td>
    <div class="choose-date mb-10px f-left">
        <?php echo html::input('date', $begin, "class='select-7 date' onchange='changeDate(this.value, \"$end\")'") . "<span> {$lang->report->to} </span>" . html::input('date', $end, "class='select-7 date' onchange='changeDate(\"$begin\", this.value)'");?>
    </div>
      <table class='table-1 fixed colored tablesorter datatable border-sep' id="bug">
        <thead>
        <tr class='colhead'>
          <th><?php echo $lang->bug->openedBy;?></th>
          <th><?php echo $lang->report->total;?></th>
          <th><?php echo $lang->bug->unResolved;?></th>
          <th><?php echo $lang->bug->resolutionList['bydesign'];?></th>
          <th><?php echo $lang->bug->resolutionList['duplicate'];?></th>
          <th><?php echo $lang->bug->resolutionList['external'];?></th>
          <th><?php echo $lang->bug->resolutionList['fixed'];?></th>
          <th><?php echo $lang->bug->resolutionList['notrepro'];?></th>
          <th><?php echo $lang->bug->resolutionList['postponed'];?></th>
          <th><?php echo $lang->bug->resolutionList['willnotfix'];?></th>
          <th><?php echo $lang->bug->resolutionList['tostory'];?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($bugs as $user => $bug):?>
          <tr class="a-center">
            <td><?php echo $users[$user];?></td>
            <td><?php echo $bug['all'];?></td>
            <td><?php echo isset($bug['']) ? $bug[''] : 0;?></td>
            <td><?php echo isset($bug['bydesign']) ? $bug['bydesign'] : 0;?></td>
            <td><?php echo isset($bug['duplicate']) ? $bug['duplicate'] : 0;?></td>
            <td><?php echo isset($bug['external']) ? $bug['external'] : 0;?></td>
            <td><?php echo isset($bug['fixed']) ? $bug['fixed'] : 0;?></td>
            <td><?php echo isset($bug['notrepro']) ? $bug['notrepro'] : 0;?></td>
            <td><?php echo isset($bug['postponed']) ? $bug['postponed'] : 0;?></td>
            <td><?php echo isset($bug['willnotfix']) ? $bug['willnotfix'] : 0;?></td>
            <td><?php echo isset($bug['tostory']) ? $bug['tostory'] : 0;?></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table> 
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
