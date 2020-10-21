<?php
/**
 * The create of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: create.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('browseType', $type);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'>
      <?php echo html::a($this->createLink('programplan', 'create', "program={$program->id}"), "<span class='text'> {$lang->programplan->create}</span>");?>
    </span>
  </div>
</div>
<?php $hideAttribute = $planID == 0 ? '' : ' hidden'?>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='planForm' enctype='multipart/form-data'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th class='required'><?php echo $lang->programplan->name;?></th>
          <th class='w-100px'><?php echo $lang->programplan->percent;?></th>
          <th class='w-110px <?php echo $hideAttribute?>'><?php echo $lang->programplan->attribute;?></th>
          <th class='w-110px'><?php echo $lang->project->acl;?></th>
          <th class='w-110px'><?php echo $lang->programplan->milestone;?></th>
          <th class='w-110px required'><?php echo $lang->programplan->begin;?></th>
          <th class='w-110px required'><?php echo $lang->programplan->end;?></th>
          <th class='w-110px'><?php echo $lang->programplan->realBegan;?></th>
          <th class='w-110px'><?php echo $lang->programplan->realEnd;?></th>
          <th class="w-90px"> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php if(empty($plans) and $planID == 0):?>
          <?php foreach($stages as $stage):?>
          <tr>
            <td><input type='text' name='names[]' id='names<?php echo $i;?>' value='<?php echo $stage->name;?>' class='form-control' /></td>
            <td>
              <div class='input-group'>
                <input type='text' name='percents[]' id='percent<?php echo $i;?>' value='<?php echo $stage->percent;?>' class='form-control'/>
                <span class='input-group-addon'>%</span>
              </div>
            </td>
            <td class='<?php echo $hideAttribute?>'><?php echo html::select("attributes[$i]", $lang->stage->typeList, $stage->type, "class='form-control'");?></td>
            <td><?php echo html::select("acl[]", $lang->project->aclList, 'open', 'class="form-control"');?></td>
            <td><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, 0);?></td>
            <td><input type='text' name='begin[]' id='begin<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td><input type='text' name='end[]' id='end<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td><input type='text' name='realBegan[]' id='realBegan<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td><input type='text' name='realEnd[]' id='realEnd<?php echo $i;?>' value='' class='form-control form-date' /></td>
            <td class='c-actions text-center'>
              <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
        <?php endif;?>

        <?php if(!empty($plans)):?>
          <?php foreach($plans as $plan):?>
          <?php $disabled = $plan->setMilestone ? '' : "disabled='disabled'"?>
          <?php echo html::hidden('planIDList[]', $plan->id);?>
          <tr>
            <td><input type='text' name='names[]' id='names<?php echo $i;?>' value='<?php echo $plan->name;?>' class='form-control' /></td>
            <td>
              <div class='input-group'>
                <input type='text' name='percents[]' id='percent<?php echo $i;?>' value='<?php echo $plan->percent;?>' class='form-control' />
                <span class='input-group-addon'>%</span>
              </div>
            </td>
            <td class='<?php echo $hideAttribute?>'><?php echo html::select("attributes[$i]", $lang->stage->typeList, $plan->attribute, "class='form-control'");?></td>
            <td><?php echo html::select("acl[]", $lang->project->aclList, $plan->acl, 'class="form-control"');?></td>
            <td><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, $plan->milestone, $disabled);?></td>
            <td><input type='text' name='begin[] ' id='begin<?php echo $i;?>' value='<?php echo $plan->begin;?>' class='form-control form-date' /></td>
            <td><input type='text' name='end[]' id='end<?php echo $i;?>' value='<?php echo $plan->end;?>' class='form-control form-date' /></td>
            <td><input type='text' name='realBegan[] ' id='realBegan<?php echo $i;?>' value='<?php echo $plan->realBegan;?>' class='form-control form-date' /></td>
            <td><input type='text' name='realEnd[]' id='realEnd<?php echo $i;?>' value='<?php echo $plan->realEnd;?>' class='form-control form-date' /></td>
            <?php $option = empty($plan->output) ? 0 : explode(',', $plan->output);?>
            <td class='c-actions text-center'>
              <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
        <?php endif;?>

        <?php for($j = 0; $j < 5; $j ++):?>
        <tr class='addedItem'>
          <td><input type='text' name='names[]' id='names<?php echo $i;?>' value='' class='form-control' /></td>
          <td>
            <div class='input-group'>
              <input type='text' name='percents[]' id='percent<?php echo $i;?>' value='' class='form-control' />
              <span class='input-group-addon'>%</span>
            </div>
          </td>
          <td class='<?php echo $hideAttribute?>'><?php echo html::select("attributes[$i]", $lang->stage->typeList, '', "class='form-control'");?></td>
          <td><?php echo html::select("acl[]", $lang->project->aclList, 'open', 'class="form-control"');?></td>
          <td><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, 0);?></td>
          <td><input type='text' name='begin[] ' id='begin<?php echo $i;?>' value='' class='form-control form-date' /></td>
          <td><input type='text' name='end[]' id='end<?php echo $i;?>' value='' class='form-control form-date' /></td>
          <td><input type='text' name='realBegan[] ' id='realBegan<?php echo $i;?>' value='' class='form-control form-date' /></td>
          <td><input type='text' name='realEnd[]' id='realEnd<?php echo $i;?>' value='' class='form-control form-date' /></td>
          <td class='c-actions text-center'>
            <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
            <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <?php $colspan = $planID == 0 ? 9 : 8;?>
          <td colspan='<?php echo $colspan?>' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton(); ?></td>
        </tr>
      </tfoot>
    </table>
    <?php js::set('i', $i);?>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addItem' class='hidden'>
      <td><input type='text' name='names[]' id='names<?php echo $i;?>' class='form-control' /></td>
      <td>
        <div class='input-group'>
          <input type='text' name='percents[]' id='percent<?php echo $i;?>' class='form-control' />
          <span class='input-group-addon'>%</span>
        </div>
      </td>
      <td class='<?php echo $hideAttribute?>'><?php echo html::select("attributes[$i]", $lang->stage->typeList, '', "class='form-control'");?></td>
      <td><?php echo html::select("acl[]", $lang->project->aclList, 'open', 'class="form-control"');?></td>
      <td><?php echo html::radio("milestone[$i]", $lang->programplan->milestoneList, 0);?></td>
      <td><input type='text' name='begin[] ' id='begin<?php echo $i;?>' class='form-control form-date' /></td>
      <td><input type='text' name='end[]' id='end<?php echo $i;?>' class='form-control form-date' /></td>
      <td><input type='text' name='realBegan[] ' id='realBegan<?php echo $i;?>' class='form-control form-date' /></td>
      <td><input type='text' name='realEnd[]' id='realEnd<?php echo $i;?>' class='form-control form-date' /></td>
      <td class='c-actions text-center'>
        <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
