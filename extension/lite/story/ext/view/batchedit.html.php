<?php
/**
 * The batch edit view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('dittoNotice', $this->lang->story->dittoNotice);?>
<?php js::set('storyType', $storyType);?>
<?php js::set('app', $this->app->tab);?>
<?php if(isset($resetActive)) js::set('resetActive', true);?>
<div class='main-content' id='mainContent'>
<div class='main-header'>
  <h2>
    <?php echo $lang->story->common . $lang->colon . $lang->story->batchEdit;?>
  </h2>
</div>
<?php if(isset($suhosinInfo)):?>
<div id='suhosinInfo' class='alert alert-info'><?php echo $suhosinInfo;?></div>
<?php else:?>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID&executionID=$executionID")?>" id="batchEditForm">
  <div class="table-responsive">
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='c-id'> <?php echo $lang->idAB;?></th>
          <th class='c-module'><?php echo $lang->story->module;?></th>
          <th class='c-title required'><?php echo $lang->story->title;?></th>
          <th class='c-estimate'> <?php echo $lang->story->estimateAB;?></th>
          <th class='c-pri'> <?php echo $lang->priAB;?></th>
          <th class='c-user'> <?php echo $lang->story->assignedTo;?></th>
          <th class='c-status'><?php echo $lang->story->status;?></th>
          <th class='c-user-box'><?php echo $lang->story->closedBy;?></th>
          <th class='c-reason'> <?php echo $lang->story->closedReason;?></th>
          <th class='c-keywords'><?php echo $lang->story->keywords;?></th>
          <?php
          $extendFields = $this->story->getFlowExtendFields();
          foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
          ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $storyID => $story):?>
        <tr>
          <td><?php echo $storyID . html::hidden("storyIdList[$storyID]", $storyID);?></td>
          <td class='text-left'>
            <?php echo html::select("modules[$storyID]", isset($moduleList[$story->id]) ? $moduleList[$story->id] : array('0' => '/'), $story->module, "class='form-control chosen'");?>
          </td>
          <td title='<?php echo $story->title?>'>
            <div class="input-group">
              <div class="input-control has-icon-right">
                <?php echo html::input("", $story->title, "class='form-control input-story-title' disabled"); ?>
                <?php echo html::hidden("titles[$storyID]", $story->title); ?>

                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("colors[$storyID]", $story->color, "class='colorpicker' data-wrapper='input-control-icon-right' data-icon='color' data-btn-tip='{$lang->story->colorTag}' data-update-color='#titles\\[{$storyID}\\]'");?>
                </div>
              </div>
            </div>
          </td>

          <td><?php echo html::input("estimates[$storyID]", $story->estimate, "class='form-control'"); ?></td>
          <td><?php echo html::select("pris[$storyID]",     $priList, $story->pri, 'class=form-control');?></td>
          <td class='text-left'><?php echo html::select("assignedTo[$storyID]",     $users, $story->assignedTo, "class='form-control chosen'");?></td>
          <td class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></td>
          <td class='text-left'><?php echo html::select("closedBys[$storyID]",     $users, $story->closedBy, "class='form-control" . ($story->status == 'closed' ? " chosen'" : "' disabled='disabled'"));?></td>

          <?php if($story->status == 'closed'):?>
          <td>
            <table class='w-p100'>
              <tr>
                <td class='pd-0'>
                  <?php echo html::select("closedReasons[$storyID]", $reasonList, $story->closedReason, "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 70px'");?>
                </td>
                <td class='pd-0' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($story->closedReason != 'duplicate') echo "style='display: none'";?>>
                <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}'");?>
                </td>
                <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($story->closedReason != 'subdivided') echo "style='display: none'";?>>
                <?php echo html::input("childStoriesIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}'");?>
                </td>
              </tr>
            </table>
          </td>
          <?php else:?>
          <td><?php echo html::select("closedReasons[$storyID]", $reasonList, $story->closedReason, 'class="form-control" disabled="disabled"');?></td>
          <?php endif;?>
          <td><?php echo html::input("keywords[$storyID]", $story->keywords, 'class="form-control"');?></td>
          <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow: visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $story, $extendField->field . "[{$storyID}]") . "</td>";?>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='10' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo $this->app->tab == 'product' ? html::a($this->session->storyList, $lang->goback, '', "class='btn btn-back btn-wide'") : html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</form>
<?php endif;?>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
