<?php include '../../common/view/header.html.php';?>
<style>
.affix {position:fixed; top:0px; width:95.6%;z-index:10000;}
</style>
<?php if(isset($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo?></div>
<?php else:?>
<form target='hiddenwin' method='post' class='form-condensed'>
<table class='table table-fixed active-disabled table-custom'>
  <thead>
    <tr>
      <th class='w-70px'><?php echo $lang->testcase->id?></th>
      <th><?php echo $lang->testcase->title?></th>
      <th class='w-100px'><?php echo $lang->testcase->module?></th>
      <th class='w-70px'><?php echo $lang->testcase->pri?></th>
      <th class='w-100px'><?php echo $lang->testcase->type?></th>
      <th><?php echo $lang->testcase->stage?></th>
      <th class='w-80px'><?php echo $lang->testcase->keywords?></th>
      <th><?php echo $lang->testcase->precondition?></th>
      <th class='w-300px'>
        <table class='w-p100 table-borderless'>
          <tr>
            <th><?php echo $lang->testcase->stepDesc?></th>
            <th><?php echo $lang->testcase->stepExpect?></th>
          </tr>
        </table>
      </th>
    </tr>
  </thead>
  <tbody>
  <?php $insert = true;?>
  <?php foreach($caseData as $key => $case):?>
  <?php if(empty($case->title)) continue;?>
  <?php if(!empty($case->id) and !isset($cases[$case->id])) $case->id = 0;?>
  <tr valign='top' align='center'>
    <td>
      <?php
      if(!empty($case->id))
      {
          echo $case->id . html::hidden("id[$key]", $case->id);
          $insert = false;
      }
      else
      {
          echo $key . " <sub class='gray' style='vertical-align:sub;'>{$lang->testcase->new}</sub>";
      }
      echo html::hidden("lib[$key]", $libID);
      ?>
    </td>
    <td><?php echo html::input("title[$key]", $case->title, "class='form-control' style='margin-top:2px' autocomplete='off'")?></td>
    <td class='text-left' style='overflow:visible'><?php echo html::select("module[$key]", $modules, isset($case->module) ? $case->module : (!empty($case->id) ? $cases[$case->id]->module : ''), "class='form-control chosen'")?></td>
    <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : (!empty($case->id) ? $cases[$case->id]->pri : ''), "class='form-control'")?></td>
    <td><?php echo html::select("type[$key]", $lang->testcase->typeList, isset($case->type) ? $case->type : (!empty($case->id) ? $cases[$case->id]->type : ''), "class='form-control'")?></td>
    <td class='text-left' style='overflow:visible'><?php echo html::select("stage[$key][]", $lang->testcase->stageList, !empty($case->stage) ? $case->stage : (!empty($case->id) ? $cases[$case->id]->stage : ''), "multiple='multiple' class='form-control chosen'")?></td>
    <td><?php echo html::input("keywords[$key]", isset($case->keywords) ? $case->keywords : "", "class='form-control' autocomplete='off'")?></td>
    <td><?php echo html::textarea("precondition[$key]", isset($case->precondition) ? htmlspecialchars($case->precondition) : "", "class='form-control'")?></td>
    <td>
      <?php if(isset($stepData[$key]['desc'])):?>
      <table class='w-p100 bd-0'>
      <?php foreach($stepData[$key]['desc'] as $id => $desc):?>
      <?php if(empty($desc['content'])) continue;?>
        <tr class='step'>
          <td><?php echo $id . html::hidden("stepType[$key][$id]", $desc['type'])?></td>
          <td><?php echo html::textarea("desc[$key][$id]", htmlspecialchars($desc['content']), "class='form-control'")?></td>
          <td><?php if($desc['type'] != 'group') echo html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]['content']) ? htmlspecialchars($stepData[$key]['expect'][$id]['content']) : '', "class='form-control'")?></td>
        </tr>
      <?php endforeach;?>
      </table>
      <?php endif;?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='9' class='text-center'>
        <?php
        if(!$insert)
        {
          include '../../common/view/noticeimport.html.php';
          echo "<button type='button' data-toggle='myModal' class='btn btn-primary'>{$lang->save}</button>";
        }
        else
        {
            echo html::submitButton();
        }
        echo ' &nbsp; ' . html::backButton()
        ?>
      </td>
    </tr>
  </tfoot>
</table>
</form>
<?php endif;?>
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
