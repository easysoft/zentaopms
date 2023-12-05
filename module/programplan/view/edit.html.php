<?php
/**
 * The edit of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: edit.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('plan', $plan);?>
<?php js::set('stageTypeList', $lang->stage->typeList);?>
<?php js::set('changeAttrLang', $lang->programplan->confirmChangeAttr);?>
<?php js::set('isTopStage', $isTopStage);?>
<?php js::set('isLeafStage', $isLeafStage);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <span class='prefix label-id'><strong><?php echo $plan->id;?></strong></span>
        <?php echo "<span title={$plan->name}>{$plan->name}</span>";?>
      </h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class="w-100px"><?php echo $lang->programplan->parent;?></th>
            <td colspan='2'><?php echo html::select('parent', $parentStageList, $plan->parent, "class='form-control chosen '");?></td>
          </tr>
          <tr>
            <th class='w-100px'><?php echo $lang->programplan->name;?> </th>
            <td colspan='2'><?php echo html::input('name', $plan->name, "class='form-control'");?></td>
          </tr>
          <?php if(isset($config->setCode) and $config->setCode == 1):?>
          <tr>
            <th class='w-100px'><?php echo $lang->execution->code;?> </th>
            <td class='required' colspan='2'><?php echo html::input('code', $plan->code, "class='form-control'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->programplan->PM;?> </th>
            <td colspan='2'><?php echo html::select('PM', $PMUsers, $plan->PM, "class='form-control picker-select'");?></td>
          </tr>
          <?php if(isset($config->setPercent) and $config->setPercent == 1):?>
          <tr>
            <th><?php echo $lang->programplan->percent;?> </th>
            <td colspan='2'>
              <div class='input-group'>
                <?php echo html::input('percent', $plan->percent, "class='form-control'");?>
                <div class='input-group-addon'>%</span>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr id="attributeType">
            <th><?php echo $lang->programplan->attribute;?></th>
            <td colspan='2'>
              <?php echo $enableOptionalAttr ? html::select('attribute', $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList, $plan->attribute, "class='form-control'") : zget($lang->stage->typeList, $plan->attribute);?>
            </td>
            <td>
              <?php if($project->model != 'ipd'):?>
              <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->execution->typeTip;?>"></icon>
              <?php endif;?>
            </td>
          </tr>
          <?php if($plan->setMilestone):?>
          <tr>
            <th><?php echo $lang->programplan->milestone;?> </th>
            <td colspan='2'><?php echo html::radio('milestone', $lang->programplan->milestoneList, $plan->milestone);?></td>
          </tr>
          <?php else:?>
            <?php echo html::hidden('milestone', $plan->milestone);?>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->project->acl;?> </th>
            <?php $class = $plan->grade == 2 ? "disabled='disabled'" : '';?>
            <td colspan='2'><?php echo html::select('acl', $lang->execution->aclList, $plan->acl, "class='form-control' $class");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->programplan->planDateRange;?> </th>
            <td colspan='2'>
              <div class="input-group title-group">
                <?php echo html::input('begin', $plan->begin, "class='form-control form-date'");?>
                <span class="input-group-addon fix-border br-0"><?php echo $lang->project->to;?></span>
                <?php echo html::input('end', $plan->end, "class='form-control form-date'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->programplan->realDateRange;?> </th>
            <td colspan='2'>
              <div class="input-group title-group">
                <?php echo html::input('realBegan', $plan->realBegan, "class='form-control form-date'");?>
                <span class="input-group-addon fix-border br-0"><?php echo $lang->project->to;?></span>
                <?php echo html::input('realEnd', $plan->realEnd, "class='form-control form-date'");?>
              </div>
            </td>
          </tr>
          <?php if(isset($this->config->qcVersion)):?>
          <tr>
            <th><?php echo $lang->programplan->output;?> </th>
            <td colspan='4'><?php echo html::select('output[]', $documentList, $plan->output, "class='form-control chosen ' multiple");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <td colspan='3' class='form-actions text-center'><?php echo html::submitButton() . html::backButton()?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
