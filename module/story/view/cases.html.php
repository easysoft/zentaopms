<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span><?php echo html::icon($lang->icons['report']);?></span>
    <small class='text-muted'> <?php echo $lang->story->cases;?></small>
  </div>
</div>
<div class='casesList'>
  <form class='form-condensed' target='hiddenwin'>
    <table class='table table-fixed'>
      <thead>
        <tr class='text-center'>
          <th class='w-20px'>    <?php echo $lang->idAB;?></th>
          <th class='w-p30'>   <?php echo $lang->testcase->title;?></th>
          <th class='w-pri'>   <?php echo $lang->priAB;?></th>
          <th class='w-type'>  <?php echo $lang->testcase->type;?></th>
          <th class='w-status'><?php echo $lang->statusAB;?></th>
          <th class='w-user'>  <?php echo $lang->testcase->lastRunner;?></th>
          <th class='w-60px'>  <?php echo $lang->testcase->lastRunDate;?></th>
          <th class='w-40px'>  <?php echo $lang->testcase->lastRunResult;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($cases as $key => $case):?>
        <tr class='text-center'>
          <td><?php echo $case->id;?></td>
          <td class='text-left' title="<?php echo $case->title?>"><?php echo $case->title;?></td>
          <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo $case->pri == '0' ? '' : zget($lang->case->priList, $case->pri, $case->pri);?></span></td>
          <td><?php echo $lang->testcase->typeList[$case->type];?></td>
          <td><?php echo $lang->testcase->statusList[$case->status];?></td>
          <td><?php echo zget($users, $case->lastRunner, $case->lastRunner);?></td>
          <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
          <td><?php echo $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : '';?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
