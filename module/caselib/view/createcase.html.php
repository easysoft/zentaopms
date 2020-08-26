<?php
/**
 * The create case view of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     caselib
 * @version     $Id: createcase.html.php 4904 2013-06-26 05:37:45Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('lblDelete', $lang->testcase->deleteStep);?>
<?php js::set('lblBefore', $lang->testcase->insertBefore);?>
<?php js::set('lblAfter', $lang->testcase->insertAfter);?>
<div class='main-content' id="mainContent">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testcase->create;?></h2>
      <div class="pull-right btn-toolbar">
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=caselib&section=custom&key=createFields');?>
        <?php include '../../common/view/customfield.html.php';?>
      </div>
    </div>
    <form method='post' enctype='multipart/form-data' id='dataform' target='hiddenwin'>
      <table class='table table-form'>
        <tbody>
        <tr>
          <th><?php echo $lang->testcase->lib;?></th>
          <td>
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
                echo html::a($this->createLink('tree', 'browse', "rootID=$libID&view=caselib&currentModuleID=0"), "<i class='icon icon-cog'></i>", '_blank', "data-toggle='tooltip' class='btn' title='{$lang->tree->manage}'");
                echo '</span>';
                echo "<span class='input-group-btn'>";
                echo html::a("javascript:loadLibModules($libID)", "<i class='icon icon-refresh'></i>", '', "data-toggle='tooltip' class='btn' title='{$lang->refresh}'");
                echo '</span>';
            }
            ?>
            </div>
          </td>
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
        <tr>
          <th><?php echo $lang->testcase->title;?></th>
          <td colspan='2'>
            <div class="input-group title-group">
              <div class="input-control has-icon-right">
                <?php echo html::input('title', $caseTitle, "class='form-control'");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                  <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                </div>
              </div>
              <?php if(strpos(",$showFields,", ',pri,') !== false): // begin print pri selector?>
              <span class="input-group-addon fix-border br-0"><?php echo $lang->testcase->pri;?></span>
              <?php
              $hasCustomPri = false;
              foreach($lang->testcase->priList as $priKey => $priValue)
              {
                  if(!empty($priKey) and (string)$priKey != (string)$priValue)
                  {
                      $hasCustomPri = true;
                      break;
                  }
              }
              $priList = $lang->testcase->priList;
              if(end($priList)) unset($priList[0]);
              if(!isset($priList[$pri]))
              {
                  reset($priList);
                  $pri = key($priList);
              }
              ?>
              <?php if($hasCustomPri):?>
              <?php echo html::select('pri', (array)$priList, $pri, "class='form-control'");?>
              <?php else: ?>
              <?php ksort($priList);?>
              <div class="input-group-btn pri-selector" data-type="pri">
                <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                  <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($pri) ? '0' : $pri?>" title="<?php echo $pri?>"><?php echo $pri?></span></span> &nbsp;<span class="caret"></span>
                </button>
                <div class='dropdown-menu pull-right'>
                  <?php echo html::select('pri', (array)$priList, $pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                </div>
              </div>
              <?php endif; ?>
              <?php endif; // end print pri selector ?>
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
            <table class='table table-form mg-0 table-bordered' style='border: 1px solid #ddd'>
              <thead>
                <tr>
                  <th class='w-50px text-right'><?php echo $lang->testcase->stepID;?></th>
                  <th width="45%"><?php echo $lang->testcase->stepDesc;?></th>
                  <th><?php echo $lang->testcase->stepExpect;?></th>
                  <th class='step-actions'><?php echo $lang->actions;?></th>
                </tr>
              </thead>
              <tbody id='steps' class='sortable' data-group-name='<?php echo $lang->testcase->groupName ?>'>
                <tr class='template step' id='stepTemplate'>
                  <td class='step-id'></td>
                  <td>
                    <div class='input-group'>
                      <span class='input-group-addon step-item-id'></span>
                      <textarea rows='1' class='form-control autosize step-steps' name='steps[]'></textarea>
                      <span class="input-group-addon step-type-toggle">
                        <input type='hidden' name='stepType[]' value='item' class='step-type'>
                        <div class='step-type-menu-box'>
                          <div class='step-type-current'><span></span> <i class='caret'></i></div>
                          <div class='step-type-menu'>
                            <a href='javascript:;' href='step-type-option' data-value='step'><?php echo $lang->testcase->step ?></a>
                            <a href='javascript:;' href='step-type-option' data-value='group'><?php echo $lang->testcase->group ?></a>
                            <a href='javascript:;' href='step-type-option' data-value='item'><?php echo $lang->testcase->stepChild ?></a>
                          </div>
                        </div>
                      </span>
                    </div>
                  </td>
                  <td><textarea rows='1' class='form-control autosize step-expects' name='expects[]'></textarea></td>
                  <td class='step-actions'>
                    <div class='btn-group'>
                      <button type='button' class='btn btn-step-add'><i class='icon icon-plus'></i></button>
                      <button type='button' class='btn btn-step-move'><i class='icon icon-move'></i></button>
                      <button type='button' class='btn btn-step-delete'><i class='icon icon-close'></i></button>
                    </div>
                  </td>
                </tr>
                <?php foreach($steps as $stepID => $step):?>
                <tr class='step'>
                  <td class='step-id'></td>
                  <td>
                    <div class='input-group'>
                      <span class='input-group-addon step-item-id'></span>
                      <?php echo html::textarea('steps[]', $step->desc, "rows='1' class='form-control autosize step-steps'") ?>
                      <span class='input-group-addon step-type-toggle'>
                        <?php if(!isset($step->type)) $step->type = 'step';?>
                        <input type='hidden' name='stepType[]' value='<?php echo $step->type ?>' class='step-type'>
                        <div class='step-type-menu-box'>
                          <div class='step-type-current'><span></span> <i class='caret'></i></div>
                          <div class='step-type-menu'>
                            <a href='javascript:;' href='step-type-option' data-value='step'><?php echo $lang->testcase->step ?></a>
                            <a href='javascript:;' href='step-type-option' data-value='group'><?php echo $lang->testcase->group ?></a>
                            <a href='javascript:;' href='step-type-option' data-value='item'><?php echo $lang->testcase->stepChild ?></a>
                          </div>
                        </div>
                      </span>
                    </div>
                  </td>
                  <td><?php echo html::textarea('expects[]', $step->expect, "rows='1' class='form-control autosize step-expects'") ?></td>
                  <td class='step-actions'>
                    <div class='btn-group'>
                      <button type='button' class='btn btn-step-add'><i class='icon icon-plus'></i></button>
                      <button type='button' class='btn btn-step-move'><i class='icon icon-move'></i></button>
                      <button type='button' class='btn btn-step-delete'><i class='icon icon-close'></i></button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </td>
        </tr>
        <?php if(strpos(",$showFields,", ',keywords,') !== false):?>
        <tr>
          <th><?php echo $lang->testcase->keywords;?></th>
          <td colspan='2'><?php echo html::input('keywords', '', "class='form-control'");?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->testcase->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
          </td>
        </tr>
        </tfoot>
      </table>
    </form>
  </div>
  <div class='modal fade' id='searchStories'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'><i class='icon icon-close'></i></button>
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
</div>
<?php js::set('caseModule', $lang->testcase->module)?>
<?php include '../../common/view/footer.html.php';?>
