<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<?php
$height = 7;
$width  = 6.4;
$pagerH = '30.5';
$bottom = '40';
$agent = $_SERVER["HTTP_USER_AGENT"];
if(strpos($agent, "MSIE"))
{
    $width  = 6.6;
    $height = 7.3;
    $pagerH = '30.8';
    $bottom = '80';
}
elseif(strpos($agent, "Firefox"))
{
    $height = 6.9;
    $pagerH = '29';
    $bottom = '60';
}
elseif(strpos($agent, "Opera"))
{
    $height = 7;
}
$today = date('n/d');
?>
<style type='text/css'>
body {margin: 0pt}
.pager{ border:1px solid #000; margin-bottom:<?php echo $bottom?>px; border-collapse:collapse; border-spacing:0px; width:21.6cm; height:<?php echo $pagerH?>cm}
.pager td{ border:1px dotted #000;}
.pager td{width:<?php echo $width?>cm; height:<?php echo $height?>cm;}
.pager .stage{border:0px; width:100%; height:auto; margin:0px;}
.pager .stage td{height:auto; border:0px; padding:0px; margin:0px;}
.board   {width:<?php echo $width - 0.2?>cm; height:<?php echo $height - 0.2?>cm; font-size:12px; border: 1px solid #ccc; text-align:left}
#record{margin-bottom:0px; margin-top:5px; border:1px solid #000; width:100%; border-collapse:collapse; border-spacing:0px}
#record td{width:13%; height:14px; border:1px solid #000}
#burn{margin-top:50px;}

.board-header{overflow:hidden; padding:1px 3px 1px 3px;}
.board-id{overflow:hidden; padding:1px 3px 1px 3px; width:84%; border-bottom:2px solid #000;}
.board-pri{border:1px solid #000; position:absolute; right:0px; top:0px; font-size:20px; padding:2px;}
.board-middle{overflow:hidden}
.board-title{overflow:hidden; padding:1px 3px 1px 3px; width:95%; font-size:18px; font-weight:bold; line-height:1.15}
.board-content{overflow:hidden; padding:1px 3px 1px 3px; width:95%; font-size:14px; line-height:1.2;letter-spacing:1px;}
.board-content p{margin-bottom:0px;}
.board-footer{border-top:1px dotted #000; padding-top:5px; position:absolute; left:0px; width:100%}
.board-footer.story{bottom:0px;}
.board-footer.task{bottom:0px;}
.board-footer p{ height:14px;padding:0px 3px; margin-bottom:2px;}
</style>
<?php
$i        = 0;
$dataType = '';
?>
<?php foreach($datas as $col => $data):?>
<?php foreach($data as $id => $content):?>
<?php if($col != 'story') $dataType = strpos($id, 'bug') !== false ? 'bug' : 'task';?>
<?php if($i % 12 == 0):?>
<table class='pager'>
<?php endif;?>
  <?php if($i % 3 == 0):?>
  <tr>
  <?php endif;?>
    <td valign='middle' align='center'>
      <div class="board" style='position:relative'>
        <div class="board-header">
          <div class="board-id">
            <?php
            if($col != 'story')
            {
                $story = $dataType == 'bug' ? $content->story : $content->storyID;
                echo $lang->story->common . "#<span style='font-size:20px'>" . (empty($story) ? '0' : $story) . '</span>';
                echo ' <i class="icon-angle-right"></i> ';
            }
            ?>
            <?php echo $col == 'story' ? $lang->story->common : ($dataType == 'task' ? $lang->task->common : $lang->bug->common)?>
            #<span style='font-size:20px;'><?php echo $content->id;?></span></div>
          <span class="board-pri">P<?php echo $content->pri ? $content->pri : '&nbsp;&nbsp;';?></span>
        </div>
        <div class='board-middle' style='height:<?php echo ($col == 'story' or $dataType == 'bug') ? '150px' : '100px'?>'>
          <div class='board-title <?php echo ($col == 'story' or $dataType == 'bug') ? 'h-40px' : 'h-100px'?>'>
            <?php
            $title = ($col == 'story' or strpos($id, 'bug') !== false) ? $content->title : $content->name;
            echo $col == 'story' ? mb_substr($title, 0, 22, 'utf8') : mb_substr($title, 0, 85, 'utf8')
            ?>
          </div>
          <?php if($col == 'story' or $dataType == 'bug'):?>
          <?php $desc = $col == 'story' ? $storySpecs[$content->id]->spec : $content->steps;?>
          <div class='board-content'><?php echo mb_substr(strip_tags($desc, "<p><br/>"), 0, 90, 'utf8')?></div>
          <?php endif;?>
        </div>
        <div class="board-footer <?php echo $col == 'story' ? 'story' : 'task'?>">
          <?php if($col == 'story'):?>
          <table class='stage'>
            <tr>
              <td style='padding-left:15px;'>
                <table class='table-1'>
                  <tr>
                    <td><span><input type="checkbox" name="story<?php echo $content->id?>[]" value="developing" <?php echo $content->stage == 'developing' ? "checked" : ''?> id="story3developing"><label for="story3developing"> <?php echo $lang->story->stageList['developing']?></label></span></td>
                    <td><span><input type="checkbox" name="story<?php echo $content->id?>[]" value="developed"  <?php echo $content->stage == 'developed' ? "checked" : ''?> id="story3developed"><label for="story3developed"> <?php echo $lang->story->stageList['developed']?></label></span></td>
                    <td><span><input type="checkbox" name="story<?php echo $content->id?>[]" value="testing"    <?php echo $content->stage == 'testing' ? "checked" : ''?> id="story3testing"><label for="story3testing"> <?php echo $lang->story->stageList['testing']?></label></span><br /></td>
                  </tr>
                  <tr>
                    <td><span><input type="checkbox" name="story<?php echo $content->id?>[]" value="tested"     <?php echo $content->stage == 'tested' ? "checked" : ''?> id="story3tested"><label for="story3tested"> <?php echo $lang->story->stageList['tested']?></label></span></td>
                    <td><span><input type="checkbox" name="story<?php echo $content->id?>[]" value="verified"   <?php echo $content->stage == 'verified' ? "checked" : ''?> id="story3verified"><label for="story3verified"> <?php echo $lang->story->stageList['verified']?></label></span></td>
                    <td></td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <?php elseif($dataType == 'bug'):?>
          <div><?php echo $lang->bug->assignTo . '：'; echo empty($content->assignedTo) ? '' : $realnames[$content->assignedTo]->realname;?></div>
          <div><?php echo $lang->printKanban->taskStatus . '：' . html::checkbox("bug$content->id", $lang->bug->statusList, $content->status);?></div>
          <?php else:?>
          <div><?php echo $lang->task->assign . '：'; echo empty($content->assignedTo) ? '' : $realnames[$content->assignedTo]->realname;?></div>
          <div><?php echo $lang->printKanban->taskStatus . '：' . html::checkbox("task$content->id", $lang->task->statusList,$content->status);?></div>
          <table class='table-1' id='record'>
            <tr>
              <td align='center'><?php echo $lang->task->date?></td>
              <td align='center'><?php echo $today?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td align='center'><?php echo $lang->task->leftThisTime?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </table>
          <?php endif;?>
        </div>
      </div>
    </td>
  <?php $i ++;?>
  <?php if($i % 3 == 0):?>
  </tr>
  <?php endif;?>
<?php if($i % 12 == 0):?>
</table>
<p style='page-break-before:always'></p>
<?php endif;?>
<?php endforeach;?>
<?php endforeach;?>

<?php while($i % 12 != 0):?>
<td valign='middle' align='center'>
  <div class="board" style='position:relative'>
    <div class="board-header">
        <div class="board-id"><?php echo $col == 'story' ? $lang->story->common : ($dataType == 'task' ? $lang->task->common : $lang->bug->common)?>#</div>
        <span class="board-pri">P &nbsp; </span>
    </div>
    <div class='board-middle <?php echo $col == 'story' ? 'h-150px' : 'h-100px'?>'>
      <div class='board-title <?php echo $col == 'story' ? 'h-40px' : 'h-100px'?>'>
      </div>
      <?php if($col == 'story'):?>
      <?php endif;?>
    </div>
    <div class="board-footer <?php echo $col == 'story' ? 'story' : 'task'?>">
      <?php if($col == 'story'):?>
      <table class='stage'>
        <tr>
          <td style='padding-left:15px;'>
            <table class='table-1'>
              <tr>
                <td><span><input type="checkbox" name="story<?php echo $i?>[]" value="developing" id="story3developing"><label for="story3developing"> <?php echo $lang->story->stageList['developing']?></label></span></td>
                <td><span><input type="checkbox" name="story<?php echo $i?>[]" value="developed"  id="story3developed"><label for="story3developed"> <?php echo $lang->story->stageList['developed']?></label></span></td>
                <td><span><input type="checkbox" name="story<?php echo $i?>[]" value="testing"    id="story3testing"><label for="story3testing"> <?php echo $lang->story->stageList['testing']?></label></span><br /></td>
              </tr>
              <tr>
                <td><span><input type="checkbox" name="story<?php echo $i?>[]" value="tested"     id="story3tested"><label for="story3tested"> <?php echo $lang->story->stageList['tested']?></label></span></td>
                <td><span><input type="checkbox" name="story<?php echo $i?>[]" value="verified"   id="story3verified"><label for="story3verified"> <?php echo $lang->story->stageList['verified']?></label></span></td>
                <td></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <?php elseif($dataType == 'bug'):?>
      <div><?php echo $lang->bug->assignTo . '：';?></div>
      <div><?php echo $lang->printKanban->taskStatus . '：' . html::checkbox("bug$content->id", $lang->bug->statusList);?></div>
      <?php else:?>
      <div><?php echo $lang->task->assignedTo . '：';?></div>
      <div><?php echo $lang->printKanban->taskStatus . '：' . html::checkbox("task$content->id", $lang->task->statusList);?></div>
      <table class='table-1' id='record'>
        <tr>
          <td align='center'><?php echo $lang->task->date?></td>
          <td align='center'><?php echo $today?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td align='center'><?php echo $lang->task->leftThisTime?></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table>
      <?php endif;?>
    </div>
  </div>
</td>
<?php $i++;?>
<?php if($i % 3 == 0):?>
</tr>
<?php endif;?>
<?php if($i % 12 == 0):?>
</table>
<p style='page-break-before:always'></p>
<?php endif;?>
<?php endwhile;?>
<div class='chart-canvas'>
  <img style="width: 790px; height: 400px;" id='chartImg'/>
  <canvas id='burnChart' data-bezier-curve='false' data-responsive='true'></canvas>
</div>
</body>
<script language='Javascript'>
function initBurnChar()
{
    var data = 
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "<?php echo $lang->project->baseline;?>",
            color: "#CCC",
            fillColor: "rgba(0,0,0,0)",
            showTooltips: false,
            data: <?php echo $chartData['baseLine']?>
        },
        {
            label: "<?php echo $lang->project->Left?>",
            color: "#0033CC",
            data: <?php echo $chartData['burnLine']?>
        }]
    };

    var burnChart = $("#burnChart").lineChart(data, {animation: !($.zui.browser && $.zui.browser.ie === 8)});
}
$(function()
{
    initBurnChar();
    setTimeout(function()
    {
        var chartImg  = $('#burnChart').get(0).toDataURL("image/png");
        $('#chartImg').attr('src', chartImg);
        $('#burnChart').hide();
        <?php if(strpos($agent,'Chrome') !== false) echo "window.print();\n" ?>
    }, 200);

    $('#placeholder').prev('h1').width(700);
    $('#placeholder').css('margin', 0);
    $('#placeholder .tickLabels .xAxis .tickLabel').each(function()
    {
        var text = $(this).html();
        if(text.indexOf('-') >= 0) $(this).html(text.substr(text.indexOf('-') + 1))
    });
})
</script>
</html>
