<?php
/**
 * The create view of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->testcase->lblProductAndModule;?></th>
        <td class='w-p25-f'>
          <?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='form-control chosen'");?>
        </td>
        <td class='w-p25-f'>
          <div class='input-group' id='moduleIdBox'>
          <?php 
          echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated();' class='form-control chosen'");
          if(count($moduleOptionMenu) == 1)
          {
              echo "<span class='input-group-addon'>";
              echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=case"), $lang->tree->manage, '_blank');
              echo '&nbsp; ';
              echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
              echo '</span>';
          }
          ?>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->type;?></th>
        <td><?php echo html::select('type', $lang->testcase->typeList, $type, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testcase->stage;?></th>
        <td><?php echo html::select('stage[]', $lang->testcase->stageList, $stage, "class='form-control chosen' multiple='multiple'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testcase->pri;?></th>
        <td><?php echo html::select('pri', $lang->testcase->priList, $pri, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testcase->lblStory;?></th>
        <td colspan='3'>
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
      <tr>
        <th><?php echo $lang->testcase->title;?></th>
        <td colspan='3'><?php echo html::input('title', $caseTitle, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testcase->precondition;?></th>
        <td colspan='3'><?php echo html::textarea('precondition', $precondition, " rows='4' class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testcase->steps;?></th>
        <td colspan='3'>
          <table class='table table-form mg-0' style='border: 1px solid #ddd'>
            <thead>
              <tr>
                <th class='w-30px'><?php echo $lang->testcase->stepID;?></th>
                <th><?php echo $lang->testcase->stepDesc;?></th>
                <th class='w-200px'><?php echo $lang->testcase->stepExpect;?></th>
                <th class='w-100px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <?php
            foreach($steps as $stepID => $step)
            {
                $stepID += 1;
                echo "<tr id='row$stepID' class='text-center'>";
                echo "<td class='stepID strong'>$stepID</td>";
                echo '<td class="w-p50">' . html::textarea('steps[]', $step->desc, "rows='3' class='form-control'") . '</td>';
                echo '<td>' . html::textarea('expects[]', $step->expect, "rows='3' class='form-control'") . '</td>';
                echo "<td class='text-left w-100px'><div class='btn-group-vertical'>";
                echo "<input type='button' tabindex='-1' class='addbutton btn btn-xs' onclick='preInsert($stepID)'  value='{$lang->testcase->insertBefore}' />";
                echo "<input type='button' tabindex='-1' class='addbutton btn btn-xs' onclick='postInsert($stepID)' value='{$lang->testcase->insertAfter}'  />";
                echo "<input type='button' tabindex='-1' class='delbutton btn btn-xs' onclick='deleteRow($stepID)'  value='{$lang->testcase->deleteStep}'   />";
                echo "</div></td>";
                echo '</tr>';
            }
            ?>
          </table>
        </td> 
      </tr>
      <tr>
        <th><?php echo $lang->testcase->keywords;?></th>
        <td colspan='3'><?php echo html::input('keywords', $keywords, "class='form-control'");?></td>
      </tr>  
       <tr>
        <th><?php echo $lang->testcase->files;?></th>
        <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr>
        <td colspan='4' class='text-center'><?php echo html::submitButton() . html::backButton();?> </td>
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
<?php include '../../common/view/footer.html.php';?>
