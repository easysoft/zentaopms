<?php include '../../common/view/header.html.php';?>
<style>
.affix {position:fixed; top:0px; width:95.6%;z-index:10000;}
</style>
<form target='hiddenwin' method='post' class='form-condensed'>
<table class='table table-fixed active-disabled'>
  <thead>
    <tr>
      <th class='w-80px'><?php echo $lang->testcase->id?></th>
      <th><?php echo $lang->testcase->title?></th>
      <th class='w-90px'><?php echo $lang->testcase->module?></th>
      <th class='w-100px'><?php echo $lang->testcase->story?></th>
      <th class='w-70px'><?php echo $lang->testcase->pri?></th>
      <th class='w-100px'><?php echo $lang->testcase->type?></th>
      <th class='w-80px'><?php echo $lang->testcase->status?></th>
      <th><?php echo $lang->testcase->stage?></th>
      <th><?php echo $lang->testcase->precondition?></th>
      <th class='w-300px'>
        <table class='w-p100 table-borderless'>
          <tr>
            <th colspan='2'><?php echo $lang->testcase->steps?></th>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->stepDesc?></th>
            <th><?php echo $lang->testcase->stepExpect?></th>
          </tr>
        </table>
      </th>
    </tr>
  </thead>
  <?php foreach($caseData as $key => $case):?>
  <tr valign='top' align='center'>
    <td>
    <?php
      if(!empty($case->id))
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
    <td><?php echo html::input("title[$key]", $case->title, "class='form-control' style='margin-top:2px'")?></td>
    <td class='text-left' style='overflow:visible'><?php echo html::select("module[$key]", $modules, isset($case->module) ? $case->module : (!empty($case->id) ? $cases[$case->id]->module : ''), "class='form-control chosen'")?></td>
    <td class='text-left' style='overflow:visible'><?php echo html::select("story[$key]", $stories, isset($case->story) ? $case->story : (!empty($case->id) ? $cases[$case->id]->story : ''), "class='form-control chosen'")?></td>
    <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : (!empty($case->id) ? $cases[$case->id]->pri : ''), "class='form-control'")?></td>
    <td><?php echo html::select("type[$key]", $lang->testcase->typeList, $case->type, "class='form-control'")?></td>
    <td><?php echo html::select("status[$key]", $lang->testcase->statusList, isset($case->status) ? $case->status : '', "class='form-control'")?></td>
    <td class='text-left' style='overflow:visible'><?php echo html::select("stage[$key][]", $lang->testcase->stageList, isset($case->stage) ? $case->stage : '', "multiple='multiple' class='form-control chosen'")?></td>
    <td><?php echo html::textarea("precondition[$key]", isset($case->precondition) ? $case->precondition : "", "class='form-control'")?></td>
    <td>
      <?php if(isset($stepData[$key]['desc'])):?>
      <table class='w-p100 bd-0'>
      <?php foreach($stepData[$key]['desc'] as $id => $desc):?>
        <tr>
          <td><?php echo html::textarea("desc[$key][$id]", $desc, "class='form-control'")?></td>
          <td><?php echo html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]) ? $stepData[$key]['expect'][$id] : '', "class='form-control'")?></td>
        </tr>
      <?php endforeach;?>
      </table>
      <?php endif;?>
    </td>
  </tr>
  <?php unset($caseData[$key]);?>
  <?php endforeach;?>
  <tfoot>
    <tr>
      <td colspan='10' class='text-center'><?php echo html::submitButton() . ' &nbsp; ' . html::backButton()?></td>
    </tr>
  </tfoot>
</table>
</form>
<script>
$(function(){affix('thead')})
function affix(obj)
{
    var fixH = $(obj).offset().top;
    var first = true;          
    $(window).scroll(function()
    {
        var scroH = $(this).scrollTop();
        if(scroH>=fixH && first)
        {
            $(obj).parent().parent().before("<table id='headerClone' class='table'></table>");
            $('#headerClone').append($(obj).clone()).addClass('affix');
            $('.active-disabled ' + obj + ' th').each(function(i){$('#headerClone ' + obj + ' th').eq(i).width($(this).width())});
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
