<?php
/**
 * The create view of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: create.html.php 4904 2013-06-26 05:37:45Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/form.html.php';?>
<?php js::set('lblDelete', $lang->testcase->deleteStep);?>
<?php js::set('lblBefore', $lang->testcase->insertBefore);?>
<?php js::set('lblAfter', $lang->testcase->insertAfter);?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->testcase->create;?></strong>
    </div>
    <div class='actions'>
      <button type='button' class='btn btn-default' data-toggle='customModal'><i class='icon icon-cog'></i></button>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->testcase->product;?></th>
        <td class='w-p45-f'>
          <div class='input-group'>
            <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen'");?>
            <?php if($this->session->currentProductType != 'normal') echo html::select('branch', $branches, $branch, "onchange='loadBranch();' class='form-control' style='width:120px'");?>
          </div>
        </td>
        <td style='padding-left:15px;'>
          <div class='input-group' id='moduleIdBox'>
          <span class="input-group-addon"><?php echo $lang->testcase->module?></span>
          <?php 
          echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated();' class='form-control chosen'");
          if(count($moduleOptionMenu) == 1)
          {
              echo "<span class='input-group-btn'>";
              echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=case&currentModuleID=0&branch=$branch"), "<i class='icon icon-cog'></i>", '_blank', "data-toggle='tooltip' class='btn' title='{$lang->tree->manage}'");
              echo html::a("javascript:loadProductModules($productID)", "<i class='icon icon-refresh'></i>", '', "data-toggle='tooltip' class='btn' title='{$lang->refresh}'");
              echo '</span>';
          }
          ?>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->type;?></th>
        <td><?php echo html::select('type', $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
        <?php if(strpos(",$showFields,", 'stage') !== false):?>
        <td style='padding-left:15px'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->testcase->stage?></span>
            <?php echo html::select('stage[]', $lang->testcase->stageList, $stage, "class='form-control chosen' multiple='multiple'");?>
          </div>
        </td>
        <?php endif;?>
      </tr>
      <?php if(strpos(",$showFields,", ',story,') !== false):?>
      <tr>
        <th><?php echo $lang->testcase->lblStory;?></th>
        <td colspan='2'>
          <div class='input-group' id='storyIdBox'>
            <?php echo html::select('story', $stories, $storyID, 'class="form-control chosen" onchange="setPreview();" data-no_results_text="' . $lang->searchMore . '"');?>
            <span class='input-group-btn' style='width: 0.01%'>
            <?php if($storyID == 0): ?>
              <a href='' id='preview' class='btn iframe hidden'><?php echo $lang->preview;?></a>
            <?php else:?>
              <?php echo html::a($this->createLink('story', 'view', "storyID=$storyID", '', true), $lang->preview, '', "class='btn iframe' id='preview'");?>
            <?php endif;?>
            </span>
          </div>
        </td>
      </tr>  
      <?php endif;?>
      <tr>
        <th><?php echo $lang->testcase->title;?></th>
        <td colspan='2'>
           <div class='row-table'>
            <div class='col-table w-p100'>
              <div class='input-group w-p100'>
                <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->testcase->colorTag ?>' data-update-text='#title'>
                <?php echo html::input('title', $caseTitle, "class='form-control'");?>
              </div>
            </div>
            <?php if(strpos(",$showFields,", ',pri,') !== false):?>
            <div class='col-table'>
              <div class='input-group'>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->testcase->pri;?></span>
                <?php
                $hasCustomPri = false;
                foreach($lang->testcase->priList as $priKey => $priValue)
                {
                    if($priKey != $priValue)
                    {
                        $hasCustomPri = true;
                        break;
                    }
                }
                ?>
                <?php if($hasCustomPri):?>
                <?php echo html::select('pri', (array)$lang->testcase->priList, $pri, "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('pri', (array)$lang->testcase->priList, $pri, "class='hide'");?>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endif;?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->precondition;?></th>
        <td colspan='2'><?php echo html::textarea('precondition', $precondition, " rows='2' class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testcase->steps;?></th>
        <td colspan='2'>
          <table class='table table-form mg-0' style='border: 1px solid #ddd'>
            <thead>
              <tr>
                <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
                <th width="45%"><?php echo $lang->testcase->stepDesc;?></th>
                <th><?php echo $lang->testcase->stepExpect;?></th>
                <th class='w-140px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <?php
            foreach($steps as $stepID => $step)
            {
                $stepID += 1;
                echo "<tr id='row$stepID' class='text-center'>";
                echo "<td class='stepID strong'>$stepID</td>";
                echo '<td>' . html::textarea('steps[]', $step->desc, "rows='1' class='form-control'") . '</td>';
                echo '<td>' . html::textarea('expects[]', $step->expect, "rows='1' class='form-control'") . '</td>';
                echo "<td class='text-left text-top'>";
                echo "<button type='button' tabindex='-1' class='addbutton btn btn-xs' onclick='preInsert($stepID)'  title='{$lang->testcase->insertBefore}'><i class=\"icon icon-double-angle-up\"></i></button>";
                echo "<button type='button' tabindex='-1' class='addbutton btn btn-xs' onclick='postInsert($stepID)' title='{$lang->testcase->insertAfter}'><i class=\"icon icon-double-angle-down\"></i></button>";
                echo "<button type='button' tabindex='-1' class='delbutton btn btn-xs' onclick='deleteRow($stepID)'  title='{$lang->testcase->deleteStep}'><i class=\"icon icon-remove\"></i></button>";
                echo "</td>";
                echo '</tr>';
            }
            ?>
          </table>
        </td> 
      </tr>
      <?php if(strpos(",$showFields,", ',keywords,') !== false):?>
      <tr>
        <th><?php echo $lang->testcase->keywords;?></th>
        <td colspan='2'><?php echo html::input('keywords', $keywords, "class='form-control'");?></td>
      </tr>  
      <?php endif;?>
       <tr>
        <th><?php echo $lang->testcase->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr>
        <th></th>
        <td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<div class='modal fade' id='searchStories'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <div class='searchInput w-p90'>
          <input id='storySearchInput' type='text' class='form-control' placeholder='<?php echo $lang->testcase->searchStories?>'>
          <i class='icon icon-search'></i>
        </div>
      </div>
      <div class='modal-body'>
        <ul id='searchResult'></ul>
      </div>
    </div>
  </div>
</div>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=testcase&section=custom&key=createFields');?>
<?php include '../../common/view/customfield.html.php';?>
<?php js::set('caseModule', $lang->testcase->module)?>
<?php include '../../common/view/footer.html.php';?>
