<?php include '../../common/view/header.html.php';?>
<form target='hiddenwin' method='post'>
<table class='table-1'>
  <caption class='caption-tl'><?php echo $lang->testcase->editHaved?></caption>
  <tr class='colhead'>
    <th><?php echo $lang->testcase->id?></th>
    <th><?php echo $lang->testcase->title?></th>
    <th><?php echo $lang->testcase->module?></th>
    <th><?php echo $lang->testcase->story?></th>
    <th><?php echo $lang->testcase->pri?></th>
    <th><?php echo $lang->testcase->type?></th>
    <th><?php echo $lang->testcase->status?></th>
    <th><?php echo $lang->testcase->frequency?></th>
    <th><?php echo $lang->testcase->stage?></th>
    <th><?php echo $lang->testcase->precondition?></th>
    <th><?php echo $lang->testcase->steps?>
      <table class='table-1'>
        <tr>
          <th><?php echo $lang->testcase->stepDesc?></th>
          <th><?php echo $lang->testcase->stepExpect?></th>
        </tr>
      </table>
    </th>
  </tr>
  <?php foreach($caseData as $key => $case):?>
  <?php if(empty($case->id)) continue?>
  <tr valign='top' align='center'>
    <td><?php echo $case->id . html::hidden("id[$key]", $case->id) . html::hidden("product[$key]", $productID)?></td>
    <td><?php echo html::input("title[$key]", $case->title, "class='text-1' style='margin-top:2px'")?></td>
    <td><?php echo html::select("module[$key]", $modules, isset($case->module) ? $case->module : $cases[$case->id]->module, "class='select-2'")?></td>
    <td><?php echo html::select("story[$key]", $stories, isset($case->story) ? $case->story : $cases[$case->id]->story, "class='select-2'")?></td>
    <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : $cases[$case->id]->pri)?></td>
    <td><?php echo html::select("type[$key]", $lang->testcase->typeList, $case->type)?></td>
    <td><?php echo html::select("status[$key]", $lang->testcase->statusList, isset($case->status) ? $case->status : '')?></td>
    <td><?php echo html::input("frequency[$key]", isset($case->frequency) ? $case->frequency : 1, "size='2'")?></td>
    <td><?php echo html::select("stage[$key][]", $lang->testcase->stageList, isset($case->stage) ? $case->stage : '', "multiple='multiple'")?></td>
    <td><?php echo html::textarea("precondition[$key]", isset($case->precondition) ? $case->precondition : "", "style='margin-top:2px; height:65px;'")?></td>
    <td>
      <?php if(isset($stepData[$key]['desc'])):?>
      <table class='table-1'>
      <?php foreach($stepData[$key]['desc'] as $id => $desc):?>
        <tr>
          <td><?php echo html::textarea("desc[$key][$id]", $desc)?></td>
          <td><?php echo html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]) ? $stepData[$key]['expect'][$id] : '')?></td>
        </tr>
      <?php endforeach;?>
      </table>
      <?php endif;?>
    </td>
  </tr>
  <?php unset($caseData[$key]);?>
  <?php endforeach;?>
</table>
<table class='table-1'>
  <caption class='caption-tl'><?php echo $lang->testcase->addNew?></caption>
  <tr class='colhead'>
    <th><?php echo $lang->testcase->title?></th>
    <th><?php echo $lang->testcase->module?></th>
    <th><?php echo $lang->testcase->story?></th>
    <th><?php echo $lang->testcase->pri?></th>
    <th><?php echo $lang->testcase->type?></th>
    <th><?php echo $lang->testcase->status?></th>
    <th><?php echo $lang->testcase->frequency?></th>
    <th><?php echo $lang->testcase->stage?></th>
    <th><?php echo $lang->testcase->precondition?></th>
    <th><?php echo $lang->testcase->steps?>
      <table class='table-1'>
        <tr>
          <th><?php echo $lang->testcase->stepDesc?></th>
          <th><?php echo $lang->testcase->stepExpect?></th>
        </tr>
      </table>
    </th>
  </tr>
  <?php foreach($caseData as $key => $case):?>
  <tr valign='top' align='center'>
    <td><?php echo html::input("title[$key]", $case->title, "class='text-1' style='margin-top:2px'")?></td>
    <td><?php echo html::select("module[$key]", $modules, isset($case->module) ? $case->module : '', "class='select-2'") . html::hidden("product[$key]", $productID)?></td>
    <td><?php echo html::select("story[$key]", $stories, isset($case->story) ? $case->story : '', "class='select-2'")?></td>
    <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : '')?></td>
    <td><?php echo html::select("type[$key]", $lang->testcase->typeList, $case->type)?></td>
    <td><?php echo html::select("status[$key]", $lang->testcase->statusList, isset($case->status) ? $case->status : 'normal')?></td>
    <td><?php echo html::input("frequency[$key]", isset($case->frequency) ? $case->frequency : 1, "size='2'")?></td>
    <td><?php echo html::select("stage[$key][]", $lang->testcase->stageList, isset($case->stage) ? $case->stage : '', "multiple='multiple'")?></td>
    <td><?php echo html::textarea("precondition[$key]", isset($case->precondition) ? $case->precondition : "", "style='margin-top:2px; height:69px'")?></td>
    <td>
      <?php if(isset($stepData[$key]['desc'])):?>
      <table class='table-1'>
      <?php foreach($stepData[$key]['desc'] as $id => $desc):?>
        <tr>
          <td><?php echo html::textarea("desc[$key][$id]", $desc)?></td>
          <td><?php echo html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]) ? $stepData[$key]['expect'][$id] : '')?></td>
        </tr>
      <?php endforeach;?>
      </table>
      <?php endif;?>
    </td>
  </tr>
  <?php endforeach;?>
</table>
<p><?php echo html::submitButton() . html::backButton()?></p>
</form>
<?php include '../../common/view/footer.html.php';?>
