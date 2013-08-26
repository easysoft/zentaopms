<?php include '../../common/view/header.html.php';?>
<style>
.affix {position:fixed; top:0px; width:95.6%;z-index:10000;}
</style>
<form target='hiddenwin' method='post'>
<table class='table-1'>
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
  <tr valign='top' align='center'>
    <td>
    <?php
      if(isset($case->id))
      {
          echo $case->id . html::hidden("id[$key]", $case->id);
      }
      else
      {
          echo $key . " <sub class='gray' style='vertical-align:sub;'>{$lang->testcase->new}</sub>";
      }
      echo html::hidden("product[$key]", $productID);
      ?>
    </td>
    <td><?php echo html::input("title[$key]", $case->title, "class='text-1' style='margin-top:2px'")?></td>
    <td><?php echo html::select("module[$key]", $modules, isset($case->module) ? $case->module : (isset($case->id) ? $cases[$case->id]->module : ''), "class='select-2'")?></td>
    <td><?php echo html::select("story[$key]", $stories, isset($case->story) ? $case->story : (isset($case->id) ? $cases[$case->id]->story : ''), "class='select-2'")?></td>
    <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : (isset($case->id) ? $cases[$case->id]->pri : ''))?></td>
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
<p><?php echo html::submitButton() . html::backButton()?></p>
</form>
<script>
$(function(){affix('.colhead')})
function affix(obj)
{
    var fixH = $(obj).offset().top;
    var first = true;          
    $(window).scroll(function()
    {
        var scroH = $(this).scrollTop();
        if(scroH>=fixH && first)
        {
            $(obj).parent().parent().before("<table id='headerClone'></table>");
            $('#headerClone').append($(obj).clone()).addClass('affix');
            $('.table-1 ' + obj + ' th').each(function(i){$('#headerClone ' + obj + ' th').eq(i).width($(this).width())});
            first = false;     
        }
        else if(scroH<fixH)    
        {
            $("#headerClone").remove();
            first = true;
        }
    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
