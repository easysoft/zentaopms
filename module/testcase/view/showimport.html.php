<?php include '../../common/view/header.html.php';?>
<?php js::set('productID', $productID);?>
<?php js::set('branch', $branch);?>
<?php if(isset($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo?></div>
<?php elseif(empty($maxImport) and $allCount > $this->config->file->maxImport):?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->testcase->import;?></h2>
  </div>
  <p><?php echo sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport));?></p>
  <p><?php echo html::commonButton($lang->import, "id='import'", 'btn btn-primary');?></p>
</div>
<script>
$(function()
{
    $('#maxImport').keyup(function()
    {
        if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($('#allCount').html()) / parseInt($('#maxImport').val())));
    });
    $('#import').click(function(){location.href = createLink('testcase', 'showImport', "productID=<?php echo $productID;?>&branch=<?php echo $branch?>&pageID=1&maxImport=" + $('#maxImport').val())})
});
</script>
<?php else:?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->testcase->import;?></h2>
  </div>
  <form target='hiddenwin' method='post'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->lineNumber?></th>
          <th class='w-40px'><?php echo $lang->idAB?></th>
          <th><?php echo $lang->testcase->title?></th>
          <th class='w-180px'><?php echo $lang->testcase->module?></th>
          <th class='w-120px'><?php echo $lang->testcase->story?></th>
          <th class='w-80px'><?php echo $lang->testcase->pri?></th>
          <th class='w-120px'><?php echo $lang->testcase->type?></th>
          <th class='w-160px'><?php echo $lang->testcase->stage?></th>
          <th><?php echo $lang->testcase->precondition?></th>
          <?php if(!empty($appendFields)):?>
          <?php foreach($appendFields as $appendField):?>
          <th class='w-100px'><?php echo $lang->testcase->{$appendField->field}?></th>
          <?php endforeach;?>
          <?php endif;?>
          <th class='w-300px'>
            <table class='w-p100 table-borderless'>
              <tr>
                <th class="no-padding"><?php echo $lang->testcase->stepDesc?></th>
                <th class="no-padding"><?php echo $lang->testcase->stepExpect?></th>
              </tr>
            </table>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php
        $insert = true;
        $addID  = 1;
        ?>
        <?php foreach($caseData as $key => $case):?>
        <?php if(empty($case->title)) continue;?>
        <tr valign='top' class='text-left'>
          <td><?php echo $key;?></td>
          <td>
            <?php
            if(!empty($case->id))
            {
                echo $case->id . html::hidden("id[$key]", $case->id);
                $insert = false;
            }
            else
            {
                echo "<sub class='gray' style='vertical-align:sub;'>{$lang->testcase->new}</sub>";
            }
            echo html::hidden("product[$key]", $productID);
            if(!empty($branches)) echo html::hidden("branch[$key]", (isset($case->branch) and $case->branch !== '') ? $case->branch : ((!empty($case->id) and isset($cases[$case->id]) and !empty($cases[$case->id]->branch)) ? $cases[$case->id]->branch : $branch));
            echo html::hidden("keywords[$key]", isset($case->keywords) ? $case->keywords : "");
            ?>
          </td>
          <td><?php echo html::input("title[$key]", htmlspecialchars($case->title, ENT_QUOTES), "class='form-control'")?></td>
          <td style='overflow:visible'>
            <?php echo html::select("module[$key]", $modules, isset($case->module) ? $case->module : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->module : ''), "class='form-control chosen moduleChange'")?>
          </td>
          <td style='overflow:visible'>
          <?php $storyID = isset($case->story) ? $case->story : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->story : '');?>
          <?php echo html::select("story[$key]", array($storyID => zget($stories, $storyID, '')), $storyID, "class='form-control chosen storyChange'")?></td>
          <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->pri : ''), "class='form-control chosen'")?></td>
          <td><?php echo html::select("type[$key]", $lang->testcase->typeList, $case->type, "class='form-control chosen'")?></td>
          <td style='overflow:visible'><?php echo html::select("stage[$key][]", $lang->testcase->stageList, !empty($case->stage) ? $case->stage : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->stage : ''), "multiple='multiple' class='form-control chosen'")?></td>
          <td><?php echo html::textarea("precondition[$key]", isset($case->precondition) ? htmlspecialchars($case->precondition) : "", "class='form-control'")?></td>
          <?php if(!empty($appendFields)):?>
          <?php $this->loadModel('flow');?>
          <?php foreach($appendFields as $appendField):?>
          <td><?php echo $this->flow->buildControl($appendField, zget($case, $appendField->field, ''), "{$appendField->field}[$key]");?></td>
          <?php endforeach;?>
          <?php endif;?>
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
            <?php else:?>
            <table class='w-p100 bd-0'>
              <tr class='step'>
                <td><?php echo '1' . html::hidden("stepType[$key][1]", 'step')?></td>
                <td><?php echo html::textarea("desc[$key][1]", '', "class='form-control'")?></td>
                <td><?php echo html::textarea("expect[$key][1]", '', "class='form-control'")?></td>
              </tr>
            </table>
            <?php endif;?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='10' class='text-center form-actions'>
            <?php
            $submitText = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
            if(!$insert and $dataInsert === '')
            {
                echo "<button type='button' data-toggle='modal' data-target='#importNoticeModal' class='btn btn-primary btn-wide'>{$submitText}</button>";
            }
            else
            {
                echo html::submitButton($submitText);
                if($dataInsert !== '') echo html::hidden('insert', $dataInsert);
            }
            echo html::hidden('isEndPage', $isEndPage ? 1 : 0);
            echo html::hidden('pagerID', $pagerID);
            echo ' &nbsp; ' . html::backButton();
            echo ' &nbsp; ' . sprintf($lang->file->importPager, $allCount, $pagerID, $allPager);
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php if(!$insert and $dataInsert === '') include '../../common/view/noticeimport.html.php';?>
  </form>
</div>
<?php endif;?>
<script>
$(function(){$.fixedTableHead('#showData');});
</script>
<?php include '../../common/view/footer.html.php';?>
