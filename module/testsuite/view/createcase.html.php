<?php
/**
 * The create case view of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: createcase.html.php 4904 2013-06-26 05:37:45Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
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
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' target='hiddenwin'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->testcase->lib;?></th>
        <td class='w-p45-f'>
          <div class='input-group'>
            <?php echo html::select('lib', $libraries, $libID, "onchange='loadLibModules(this.value);' class='form-control chosen'");?>
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
              echo html::a($this->createLink('tree', 'browse', "rootID=$libID&view=testlib&currentModuleID=0"), "<i class='icon icon-cog'></i>", '_blank', "data-toggle='tooltip' class='btn' title='{$lang->tree->manage}'");
              echo '</span>';
              echo "<span class='input-group-btn'>";
              echo html::a("javascript:loadLibModules($libID)", "<i class='icon icon-refresh'></i>", '', "data-toggle='tooltip' class='btn' title='{$lang->refresh}'");
              echo '</span>';
          }
          ?>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->type;?></th>
        <td><?php echo html::select('type', $lang->testcase->typeList, '', "class='form-control chosen'");?></td>
        <td style='padding-left:15px'>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->testcase->stage?></span>
            <?php echo html::select('stage[]', $lang->testcase->stageList, '', "class='form-control chosen' multiple='multiple'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->title;?></th>
        <td colspan='2'>
           <div class='row-table'>
            <div class='col-table w-p100'>
              <div class='input-group w-p100'>
                <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->testcase->colorTag ?>' data-update-text='#title'>
                <?php echo html::input('title', '', "class='form-control' autocomplete='off'");?>
              </div>
            </div>
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
                <?php echo html::select('pri', (array)$lang->testcase->priList, '', "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('pri', (array)$lang->testcase->priList, '', "class='hide'");?>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->testcase->precondition;?></th>
        <td colspan='2'><?php echo html::textarea('precondition', '', " rows='2' class='form-control'");?></td>
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
      <tr>
        <th><?php echo $lang->testcase->keywords;?></th>
        <td colspan='2'><?php echo html::input('keywords', '', "class='form-control' autocomplete='off'");?></td>
      </tr>  
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
<?php js::set('caseModule', $lang->testcase->module)?>
<?php include '../../common/view/footer.html.php';?>
