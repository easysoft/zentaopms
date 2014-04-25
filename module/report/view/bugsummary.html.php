<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['report-file']);?></span>
    <strong> <?php echo $title;?></strong>
  </div>
</div>
<div class='side'>
  <?php include 'blockreportlist.html.php';?>
  <div class='panel panel-body' style='padding: 10px 6px'>
    <div class='text proversion'>
      <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo $lang->report->proVersion;?></span>
    </div>
  </div>
</div>
<div class='main'>
  <div class='input-group w-300px input-group-sm'>
    <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $begin, "class='w-100px form-control form-date' onchange='changeDate(this.value, \"$end\")'");?></div>
    <span class='input-group-addon'><?php echo $lang->report->to;?></span>
    <div class='datepicker-wrapper datepicker-date'><?php echo html::input('date', $end, "class='form-control form-date' onchange='changeDate(\"$begin\", this.value)'");?></div>
  </div>
  <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled' id="bug">
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
      <th title='<?php echo $lang->report->validRateTips;?>'><?php echo $lang->report->validRate;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($bugs as $user => $bug):?>
      <?php if(!array_key_exists($user, $users)) continue;?>
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
        <td><?php echo round($bug['validRate'] * 100, 2) . '%';?></td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table> 
</div>

<?php include '../../common/view/footer.html.php';?>
