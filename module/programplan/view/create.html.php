<?php
/**
 * The create of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: create.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php js::set('browseType', $type);?>
<style>.icon-help{margin-left: 3px;}</style>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php
      $title = $lang->programplan->create;
      if($planID) $title = $programPlan->name . $lang->project->stage . '（' . $programPlan->begin . $lang->project->to . $programPlan->end . '）';
      echo "<span class='text'>{$title}</span>";
      ?>
    </span>
    <?php if($productList and $project->stageBy == 'product'):?>
    <div class='btn-group'>
      <?php $viewName = $productID != 0 ? zget($productList,$productID) : current($productList);?>
      <a href='javascript:;' class='btn btn-link btn-limit' data-toggle='dropdown'><span class='text' title='<?php echo $viewName;?>'><?php echo $viewName;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
        $class = '';
        foreach($productList as $key => $product)
        {
            $class = $productID == $key ? 'class="active"' : '';
            echo "<li $class>" . html::a($this->createLink('programplan', 'create', "projectID=$project->id&productID=$key"), $product) . "</li>";
        }
        ?>
      </ul>
    </div>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <?php if(!empty($planID) and $project->model == 'waterfallplus'):?>
    <div class="pull-left">
      <div class='methodTitle'><strong><?php echo $lang->programplan->subPlanManage . ':'?></strong></div>
      <div class='type-list-radio'>
      <?php
      if(count($lang->programplan->typeList) > 1)
      {
          foreach($lang->programplan->typeList as $key => $value)
          {
              $label = "<label class='radio-inline'><input type='radio' name='executionType' value='{$key}'" . ($key == $executionType ? " checked='checked'" : '') . ">{$value}</label>";
              echo html::a($this->createLink('programplan', 'create', "projectID=$project->id&productID=$productID&planID=$planID&type=$key"), $label);
          }
      }
      else
      {
          echo zget($lang->programplan->typeList, $executionType);
      }
      ?>
      </div>
      <div class='methodTip'><icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->programplan->methodTip;?>"></icon></div>
    </div>
    <?php endif;?>
    <div class="btn-toolbar pull-right">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', "module=programplan&section={$custom}&key=createFields")?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>
  <?php $hideAttribute = isset($visibleFields['attribute']) ? '' : ' hidden'?>
  <?php $typeClass     = ($planID != 0 and $executionType == 'agileplus') ? '' : ' hidden'?>
  <?php $attrAlign     = $enableOptionalAttr ? '' : 'text-center';?>
  <?php $class         = $planID == 0 ? '' : "disabled='disabled'"?>
  <?php $name          = $planID == 0 ? $lang->programplan->name : $lang->programplan->subStageName;?>
  <form class='main-form form-ajax' method='post' id='planForm' enctype='multipart/form-data'>
    <div class='table-responsive'>
      <table class='table table-form'>
        <thead>
          <tr class='text-center'>
            <th class='c-type<?php echo $typeClass;?> required'><?php echo $lang->execution->method;?></th>
            <th class='c-name required'><?php echo $executionType == 'stage' ? $name : $lang->nameAB;?></th>
            <?php if(isset($config->setCode) and $config->setCode == 1):?>
            <th class='c-code required'><?php echo $executionType == 'stage' ? $lang->execution->code : $lang->code;?></th>
            <?php endif;?>
            <th class='c-pm <?php echo zget($visibleFields, 'PM', ' hidden') . zget($requiredFields, 'PM', '', ' required');?>'><?php echo $executionType == 'stage' ? $lang->programplan->PM : $lang->programplan->PMAB;?></th>
            <th class='c-percent <?php echo zget($visibleFields, 'percent', ' hidden') . zget($requiredFields, 'percent', '', ' required');?>'>
              <?php echo $lang->programplan->percent;?>
              <?php if($planID):?>
              <i class='icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-container="body" data-content="<?php echo $lang->programplan->workloadTips;?>"></i>
              <?php endif;?>
            </th>
            <th class='c-attribute <?php echo $hideAttribute . zget($requiredFields, 'attribute', '', ' required');?>'><?php echo $lang->programplan->attribute;?></th>
            <th class='c-acl <?php echo zget($visibleFields, 'acl', ' hidden') . zget($requiredFields, 'acl', '', ' required');?>'><?php echo $lang->programplan->acl;?></th>
            <th class='w-110px <?php echo zget($visibleFields, 'milestone', ' hidden') . zget($requiredFields, 'milestone', '', ' required');?>'><?php echo $lang->programplan->milestone;?></th>
            <th class='c-date required'><?php echo $lang->programplan->begin;?></th>
            <th class='c-date required'><?php echo $lang->programplan->end;?></th>
            <th class='c-date <?php echo zget($visibleFields, 'realBegan', ' hidden') . zget($requiredFields, 'realBegan', '', ' required');?>'><?php echo $lang->programplan->realBegan;?></th>
            <th class='c-date <?php echo zget($visibleFields, 'realEnd', ' hidden') . zget($requiredFields, 'realEnd', '', ' required');?>'><?php echo $lang->programplan->realEnd;?></th>
            <th class='c-desc <?php echo zget($visibleFields, 'desc', ' hidden') . zget($requiredFields, 'desc', '', ' required');?>'><?php echo $lang->programplan->desc;?></th>
            <?php if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $executionType == 'stage'):?>
            <th class='w-110px'><?php echo $lang->programplan->output;?></th>
            <?php endif;?>
            <th class="c-action text-center w-110px"> <?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody class='sortable'>
          <?php $i = 0;?>
          <?php if(empty($plans) and $planID == 0):?>
            <?php foreach($stages as $stage):?>
            <tr>
              <td><input type='text' name='names[<?php echo $i;?>]' id='names<?php echo $i;?>' value='<?php echo $stage->name;?>' class='form-control' /></td>
              <?php if(isset($config->setCode) and $config->setCode == 1):?>
              <td><?php echo html::input("codes[$i]", isset($stage->code) ? $stage->code : '', "class='form-control'");?></td>
              <?php endif;?>
              <td <?php echo zget($visibleFields, 'PM', ' hidden') . zget($requiredFields, 'PM', '', ' required');?>><?php echo html::select("PM[$i]", $PMUsers, '', "class='form-control picker-select'");?></td>
              <td <?php echo zget($visibleFields, 'percent', ' hidden') . zget($requiredFields, 'percent', '', ' required');?>>
                <div class='input-group'>
                  <input type='text' name='percents[<?php echo $i;?>]' id='percent<?php echo $i;?>' value='<?php echo $stage->percent;?>' class='form-control'/>
                  <span class='input-group-addon'>%</span>
                </div>
              </td>
              <td class='<?php echo $hideAttribute . zget($requiredFields, 'attribute', '', ' required');?> <?php echo $attrAlign;?>'><?php echo $enableOptionalAttr ? html::select("attributes[$i]", $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList, $stage->type, "class='form-control'") : zget($lang->stage->typeList, $programPlan->attribute);?></td>
              <td class='<?php echo zget($visibleFields, 'acl', ' hidden') . zget($requiredFields, 'acl', '', ' required');?>'><?php echo html::select("acl[$i]", $lang->execution->aclList, 'open', "class='form-control' $class");?></td>
              <td class='text-center' <?php echo zget($visibleFields, 'milestone', ' hidden') . zget($requiredFields, 'milestone', '', ' required');?>><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, 0);?></td>
              <td><input type='text' name='begin[<?php echo $i;?>]' id='begin<?php echo $i;?>' value='' class='form-control form-date' /></td>
              <td><input type='text' name='end[<?php echo $i;?>]' id='end<?php echo $i;?>' value='' class='form-control form-date' /></td>
              <td <?php echo zget($visibleFields, 'realBegan', ' hidden') . zget($requiredFields, 'realBegan', '', ' required');?>><input type='text' name='realBegan[<?php echo $i;?>]' id='realBegan<?php echo $i;?>' value='' class='form-control form-date' /></td>
              <td <?php echo zget($visibleFields, 'realEnd', ' hidden') . zget($requiredFields, 'realEnd', '', ' required');?>><input type='text' name='realEnd[<?php echo $i;?>]' id='realEnd<?php echo $i;?>' value='' class='form-control form-date' /></td>
              <?php if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $executionType == 'stage'):?>
              <td><?php echo html::select("output[$i][]", $documentList, '', "class='form-control picker-select' data-drop-width='auto' multiple");?></td>
              <?php endif;?>
              <td class='c-actions text-center'>
                <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
                <button type="button" class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
                <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
              </td>
            </tr>
            <?php $i ++;?>
            <?php endforeach;?>
          <?php endif;?>
          <?php if(!empty($plans)):?>
            <?php foreach($plans as $plan):?>
            <?php $disabled = isset($plan->setMilestone) ? '' : "disabled='disabled'"?>
            <?php echo html::hidden("planIDList[$i]", $plan->id);?>
            <tr>
              <td class='<?php echo $typeClass . ' text-center ' .zget($lang->execution->typeList, $plan->type);?>'><?php echo zget($lang->execution->typeList, $plan->type);?></td>
              <td><input type='text' name="names[<?php echo $i;?>]" id='names<?php echo $i;?>' value='<?php echo $plan->name;?>' class='form-control' /></td>
              <?php if(isset($config->setCode) and $config->setCode == 1):?>
              <td><?php echo html::input("codes[$i]", $plan->code, "class='form-control'");?></td>
              <?php endif;?>
              <td <?php echo zget($visibleFields, 'PM', ' hidden') . zget($requiredFields, 'PM', '', ' required');?>><?php echo html::select("PM[$i]", $PMUsers, $plan->PM, "class='form-control picker-select'");?></td>
              <td <?php echo zget($visibleFields, 'percent', ' hidden') . zget($requiredFields, 'percent', '', ' required');?>>
                <div class='input-group'>
                  <input type='text' name='percents[<?php echo $i;?>]' id='percent<?php echo $i;?>' value='<?php echo $plan->percent;?>' class='form-control' />
                  <span class='input-group-addon'>%</span>
                </div>
              </td>
              <td class='<?php echo $hideAttribute . zget($requiredFields, 'attribute', '', ' required');?> <?php echo $attrAlign;?>'><?php echo $enableOptionalAttr ? html::select("attributes[$i]", $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList, $plan->attribute, "class='form-control'") : zget($lang->stage->typeList, $programPlan->attribute);?></td>
              <td <?php echo zget($visibleFields, 'acl', ' hidden') . zget($requiredFields, 'acl', '', ' required');?>><?php echo html::select("acl[$i]", $lang->execution->aclList, $plan->acl, "class='form-control' $class");?></td>
              <td class='text-center' <?php echo zget($visibleFields, 'milestone', ' hidden') . zget($requiredFields, 'milestone', '', ' required');?>><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, $plan->milestone, $disabled);?></td>
              <td><input type='text' name='begin[<?php echo $i;?>] ' id='begin<?php echo $i;?>' value='<?php echo $plan->begin;?>' class='form-control form-date' /></td>
              <td><input type='text' name='end[<?php echo $i;?>]' id='end<?php echo $i;?>' value='<?php echo $plan->end;?>' class='form-control form-date' /></td>
              <td <?php echo zget($visibleFields, 'realBegan', ' hidden') . zget($requiredFields, 'realBegan', '', ' required');?>><input type='text' name='realBegan[<?php echo $i;?>] ' id='realBegan<?php echo $i;?>' value='<?php echo $plan->realBegan;?>' class='form-control form-date' /></td>
              <td <?php echo zget($visibleFields, 'realEnd', ' hidden') . zget($requiredFields, 'realEnd', '', ' required');?>><input type='text' name='realEnd[<?php echo $i;?>]' id='realEnd<?php echo $i;?>' value='<?php echo $plan->realEnd;?>' class='form-control form-date' /></td>
              <td class='<?php echo zget($visibleFields, 'desc', 'hidden')?>'><?php echo html::textarea("desc[$i]", $plan->desc, "rows='1' class='form-control autosize'");?></td>
              <?php if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $executionType == 'stage'):?>
              <?php $option = empty($plan->output) ? 0 : explode(',', $plan->output);?>
              <td><?php echo html::select("output[$i][]", $documentList, $option, "class='form-control picker-select' data-drop-width='auto' multiple");?></td>
              <?php endif;?>
              <td class='c-actions text-center'>
                <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
                <button type="button" class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
                <a href='javascript:;' onclick='deleteItem(this)' class='invisible btn btn-link'><i class='icon icon-close'></i></a>
                <?php echo html::hidden('orders[]', $plan->order);?>
              </td>
            </tr>
            <?php $i ++;?>
            <?php endforeach;?>
          <?php endif;?>
          <?php for($j = 0; $j < 5; $j ++):?>
          <tr class='addedItem'>
            <td class='<?php echo $typeClass;?>'><?php echo html::select("type[$i]", $lang->execution->typeList, '', "class='form-control chosen'");?></td>
            <td><input type='text' name='names[<?php echo $i;?>]' id='names<?php echo $i;?>' value='' class='form-control' /></td>
            <?php if(isset($config->setCode) and $config->setCode == 1):?>
            <td><?php echo html::input("codes[$i]", '', "class='form-control'");?></td>
            <?php endif;?>
            <td <?php echo zget($visibleFields, 'PM', ' hidden') . zget($requiredFields, 'PM', '', ' required');?>><?php echo html::select("PM[$i]", $PMUsers, '', "class='form-control picker-select'");?></td>
            <td <?php echo zget($visibleFields, 'percent', ' hidden') . zget($requiredFields, 'percent', '', ' required');?>>
              <div class='input-group'>
                <input type='text' name='percents[<?php echo $i;?>]' id='percent<?php echo $i;?>' value='' class='form-control' />
                <span class='input-group-addon'>%</span>
              </div>
            </td>
            <td class='<?php echo $hideAttribute . zget($requiredFields, 'attribute', '', ' required');?> <?php echo $attrAlign;?>'><?php echo $enableOptionalAttr ? html::select("attributes[$i]", $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList, '', "class='form-control'") : zget($lang->stage->typeList, $programPlan->attribute);?></td>
            <td <?php echo zget($visibleFields, 'acl', ' hidden') . zget($requiredFields, 'acl', '', ' required');?>><?php echo html::select("acl[$i]", $lang->execution->aclList, empty($programPlan) ? 'open' : $programPlan->acl, "class='form-control' $class");?></td>
            <td class='text-center' <?php echo zget($visibleFields, 'milestone', ' hidden') . zget($requiredFields, 'milestone', '', ' required');?>><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, 0);?></td>
            <td><input type='text' name='begin[<?php echo $i;?>] ' id='begin<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td><input type='text' name='end[<?php echo $i;?>]' id='end<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td <?php echo zget($visibleFields, 'realBegan', ' hidden') . zget($requiredFields, 'realBegan', '', ' required');?>><input type='text' name='realBegan[<?php echo $i;?>] ' id='realBegan<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td <?php echo zget($visibleFields, 'realEnd', ' hidden') . zget($requiredFields, 'realEnd', '', ' required');?>><input type='text' name='realEnd[<?php echo $i;?>]' id='realEnd<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td class='<?php echo zget($visibleFields, 'desc', 'hidden')?>'><?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control autosize'");?></td>
            <?php if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $executionType == 'stage'):?>
            <td><?php echo html::select("output[$i][]", $documentList, '', "class='form-control picker-select' data-drop-width='auto' multiple");?></td>
            <?php endif;?>
            <td class='c-actions text-center'>
              <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <button type="button" class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
              <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endfor;?>
        </tbody>
        <tfoot>
          <tr>
            <?php $colspan = $planID == 0 ? $colspan : $colspan - 1;?>
            <td colspan='<?php echo $colspan?>' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton(); ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <?php js::set('itemIndex', $i);?>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td class='<?php echo $typeClass;?>'><?php echo html::select("type[$i]", $lang->execution->typeList, '', "class='form-control chosen'");?></td>
      <td><input type='text' name='<?php echo "names[$i]";?>' id='names<?php echo $i;?>' class='form-control' /></td>
      <?php if(isset($config->setCode) and $config->setCode == 1):?>
      <td><?php echo html::input("codes[$i]", '', "class='form-control'");?></td>
      <?php endif;?>
      <td <?php echo zget($visibleFields, 'PM', ' hidden') . zget($requiredFields, 'PM', '', ' required');?>><?php echo html::select("PM[$i]", $PMUsers, '', "class='form-control' id='PM$i'");?></td>
      <?php echo html::hidden("planIDList[$i]", 0);?>
      <td <?php echo zget($visibleFields, 'percent', ' hidden') . zget($requiredFields, 'percent', '', ' required');?>>
        <div class='input-group'>
          <input type='text' name='<?php echo "percents[$i]";?>' id='percent<?php echo $i;?>' class='form-control' />
          <span class='input-group-addon'>%</span>
        </div>
      </td>
      <td class='<?php echo $hideAttribute . zget($requiredFields, 'attribute', '', ' required');?> <?php echo $attrAlign;?>'><?php echo $enableOptionalAttr ? html::select("attributes[$i]", $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList, '', "class='form-control'") : zget($lang->stage->typeList, $programPlan->attribute);?></td>
      <td <?php echo zget($visibleFields, 'acl', ' hidden') . zget($requiredFields, 'acl', '', ' required');?>><?php echo html::select("acl[$i]", $lang->execution->aclList, empty($programPlan) ? 'open' : $programPlan->acl, "class='form-control' $class");?></td>
      <td class='text-center' <?php echo zget($visibleFields, 'milestone', ' hidden') . zget($requiredFields, 'milestone', '', ' required');?>><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, 0);?></td>
      <td><input type='text' name='<?php echo "begin[$i]";?>' id='begin<?php echo $i;?>' class='form-control form-date' /></td>
      <td><input type='text' name='<?php echo "end[$i]";?>' id='end<?php echo $i;?>' class='form-control form-date' /></td>
      <td <?php echo zget($visibleFields, 'realBegan', ' hidden') . zget($requiredFields, 'realBegan', '', ' required');?>><input type='text' name='<?php echo "realBegan[$i]";?>' id='realBegan<?php echo $i;?>' class='form-control form-date' /></td>
      <td <?php echo zget($visibleFields, 'realEnd', ' hidden') . zget($requiredFields, 'realEnd', '', ' required');?>><input type='text' name='<?php echo "realEnd[$i]";?>' id='realEnd<?php echo $i;?>' class='form-control form-date' /></td>
      <td class='<?php echo zget($visibleFields, 'desc', 'hidden')?>'><?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control autosize'");?></td>
      <?php if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $executionType == 'stage'):?>
      <td><?php echo html::select("output[$i][]", $documentList, '', "class='form-control' data-drop-width='auto' multiple");?></td>
      <?php endif;?>
      <td class='c-actions text-center'>
        <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <button type="button" class='btn btn-link btn-sm btn-icon btn-move'><i class='icon-move'></i></button>
        <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php
js::set('emptyBegin', $lang->programplan->emptyBegin);
js::set('emptyEnd', $lang->programplan->emptyEnd);
js::set('planFinishSmall', $lang->programplan->error->planFinishSmall);
js::set('errorBegin', $lang->programplan->errorBegin);
js::set('errorEnd', $lang->programplan->errorEnd);
js::set('projectBeginDate', $project->begin);
js::set('projectEndDate', $project->end);
?>
<script>
$('[data-toggle="popover"]').popover();

var options = {
    selector: 'tr',
    trigger: '.icon-move',
    dragCssClass: 'drag-row',
    reverse: true,
}
$('#planForm tbody.sortable').sortable(options);
</script>
<?php include '../../common/view/footer.html.php';?>
