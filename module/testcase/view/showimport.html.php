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
  <form class='main-form' target='hiddenwin' method='post'>
    <div class="table-responsive">
      <table class='table table-form' id='showData'>
        <thead>
          <tr>
            <?php $requiredFields = $config->testcase->create->requiredFields;?>
            <th class='c-line-number'><?php echo $lang->lineNumber?></th>
            <th class='c-id'><?php echo $lang->idAB?></th>
            <?php if($product->type != 'normal'):?>
            <th class='c-branch'><?php echo $lang->testcase->branch?></th>
            <?php endif;?>
            <th class='c-name      <?php if(strpos(",$requiredFields,", ',title,')        !== false) echo 'required';?>'><?php echo $lang->testcase->title?></th>
            <th class='c-module    <?php if(strpos(",$requiredFields,", ',module,')       !== false) echo 'required';?>'><?php echo $lang->testcase->module?></th>
            <th class='c-story     <?php if(strpos(",$requiredFields,", ',story,')        !== false) echo 'required';?>'><?php echo $lang->testcase->story?></th>
            <th class='c-pri-box   <?php if(strpos(",$requiredFields,", ',pri,')          !== false) echo 'required';?>'><?php echo $lang->testcase->pri?></th>
            <th class='c-type      <?php if(strpos(",$requiredFields,", ',type,')         !== false) echo 'required';?>'><?php echo $lang->testcase->type?></th>
            <th class='c-stage     <?php if(strpos(",$requiredFields,", ',stage,')        !== false) echo 'required';?>'><?php echo $lang->testcase->stage?></th>
            <th class='c-condition <?php if(strpos(",$requiredFields,", ',precondition,') !== false) echo 'required';?>'><?php echo $lang->testcase->precondition?></th>
            <th class='c-keywords  <?php if(strpos(",$requiredFields,", ',keywords,')     !== false) echo 'required';?>'><?php echo $lang->testcase->keywords?></th>
            <?php
            if(!empty($appendFields))
            {
                foreach($appendFields as $field)
                {
                    if(!$field->show) continue;

                    $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
                    $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
                    echo "<th class='$required c-extend' style='width: $width'>$field->name</th>";
                }
            }
            ?>
            <th class='c-step'>
              <table class='w-p100 table-borderless'>
                <tr>
                  <th class="no-padding"><?php echo $lang->testcase->stepDesc?></th>
                  <th class="c-expect no-padding"><?php echo $lang->testcase->stepExpect?></th>
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
            <td><?php echo $addID;?></td>
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
              ?>
            </td>
            <?php $branchID = $branch;?>
            <?php if($product->type != 'normal'):?>
            <?php
            if(isset($case->branch)) $branchID = $case->branch;
            if(!isset($branches[$branchID])) $branchID = $branch;
            ?>
            <td><?php echo html::select("branch[$key]", $branches, $branchID, "class='form-control chosen' onchange='setModules($productID, this.value, $key)'");?></td>
            <?php endif;?>
            <td><?php echo html::input("title[$key]", htmlSpecialString($case->title, ENT_QUOTES), "class='form-control'")?></td>
            <td style='overflow:visible'>
              <?php
              $caseModules = ($branchID and isset($modules[$branchID])) ? $modules[BRANCH_MAIN] + $modules[$branchID] : $modules[BRANCH_MAIN];
              ?>
              <?php echo html::select("module[$key]", $caseModules, isset($case->module) ? $case->module : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->module : ''), "class='form-control chosen moduleChange' onchange='loadStories($productID, this.value, $key)'")?>
            </td>
            <td style='overflow:visible'>
            <?php $storyID = isset($case->story) ? $case->story : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->story : '');?>
            <?php echo html::select("story[$key]", array($storyID => zget($stories, $storyID, '')), $storyID, "class='form-control chosen storyChange'")?></td>
            <td><?php echo html::select("pri[$key]", $lang->testcase->priList, isset($case->pri) ? $case->pri : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->pri : ''), "class='form-control chosen'")?></td>
            <td><?php echo html::select("type[$key]", $lang->testcase->typeList, isset($case->type) ? $case->type : '', "class='form-control chosen'")?></td>
            <td style='overflow:visible'><?php echo html::select("stage[$key][]", $lang->testcase->stageList, !empty($case->stage) ? $case->stage : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->stage : ''), "multiple='multiple' class='form-control chosen'")?></td>
            <td><?php echo html::textarea("precondition[$key]", isset($case->precondition) ? htmlSpecialString($case->precondition) : "", "class='form-control'")?></td>
            <td><?php echo html::input("keywords[$key]", isset($case->keywords) ? $case->keywords : '', "class='form-control'")?></td>
            <?php
            if(!empty($appendFields))
            {
                $this->loadModel('flow');
                foreach($appendFields as $field)
                {
                    if(!$field->show) continue;
                    $value = $field->defaultValue ? $field->defaultValue : zget($case, $field->field, '');
                    echo '<td>' . $this->flow->buildControl($field, $value, "$field->field[$key]", true) . '</td>';
                }
            }
            ?>
            <td>
              <?php if(isset($stepData[$key]['desc'])):?>
              <table class='w-p100 bd-0'>
              <?php $hasStep = false;?>
              <?php foreach($stepData[$key]['desc'] as $id => $desc):?>
              <?php if(empty($desc['content'])) continue;?>
                <tr class='step'>
                  <?php $hasStep = true;?>
                  <td><?php echo $id . html::hidden("stepType[$key][$id]", $desc['type'])?></td>
                  <td><?php echo html::textarea("desc[$key][$id]", htmlSpecialString($desc['content']), "class='form-control'")?></td>
                  <td><?php if($desc['type'] != 'group') echo html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]['content']) ? htmlSpecialString($stepData[$key]['expect'][$id]['content']) : '', "class='form-control'")?></td>
                </tr>
              <?php endforeach;?>
              <?php if(!$hasStep):?>
                <tr class='step'>
                  <td><?php echo '1' . html::hidden("stepType[$key][1]", 'step')?></td>
                  <td><?php echo html::textarea("desc[$key][1]", '', "class='form-control'")?></td>
                  <td><?php echo html::textarea("expect[$key][1]", '', "class='form-control'")?></td>
                </tr>
              <?php endif;?>
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
          <?php $addID ++;?>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='11' class='text-center form-actions'>
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
              echo html::linkButton($lang->goback, $this->inlink('browse', "productID=$productID"), 'self', '', 'btn btn-wide');
              echo sprintf($lang->file->importPager, $allCount, $pagerID, $allPager);
              ?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php if(!$insert and $dataInsert === '') include '../../common/view/noticeimport.html.php';?>
  </form>
</div>
<?php endif;?>
<script>
$(function(){$.fixedTableHead('#showData');});
</script>
<?php include '../../common/view/footer.html.php';?>
