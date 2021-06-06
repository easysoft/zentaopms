<?php
/**
 * The edit view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix label-id'><strong><?php echo $execution->id;?></strong></span>
        <?php echo html::a($this->createLink('execution', 'view', 'execution=' . $execution->id), $execution->name, '_blank');?>
        <small><?php echo $lang->arrow . ' ' . $lang->execution->edit;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->execution->name;?></th>
          <td><?php echo html::input('name', $execution->name, "class='form-control' required");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->code;?></th>
          <td><?php echo html::input('code', $execution->code, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $execution->begin, "class='form-control form-date' onchange='computeWorkDays()' required placeholder='" . $lang->execution->begin . "'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->execution->to;?></span>
              <?php echo html::input('end', $execution->end, "class='form-control form-date' onchange='computeWorkDays()' required placeholder='" . $lang->execution->end . "'");?>
              <div class='input-group-btn'>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><?php echo $lang->execution->byPeriod;?> <span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php foreach ($lang->execution->endList as $key => $name):?>
                  <li><a href='javascript:computeEndDate("<?php echo $key;?>")'><?php echo $name;?></a></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', $execution->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->type;?></th>
          <td>
          <?php echo html::select('lifetime', $lang->execution->lifeTimeList, $execution->lifetime, "class='form-control' onchange='showLifeTimeTips()'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->teamname;?></th>
          <td><?php echo html::input('team', $execution->team, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->status;?></th>
          <td><?php echo html::select('status', $lang->execution->statusList, $execution->status, "class='form-control'");?></td>
        </tr>
        <tr>
          <th rowspan='2'><?php echo $lang->execution->owner;?></th>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->PO;?></span>
              <?php echo html::select('PO', $poUsers, $execution->PO, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->QD;?></span>
              <?php echo html::select('QD', $qdUsers, $execution->QD, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->PM;?></span>
              <?php echo html::select('PM', $pmUsers, $execution->PM, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->RD;?></span>
              <?php echo html::select('RD', $rdUsers, $execution->RD, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="2">
          <?php $class = $execution->grade == 2 ? "disabled='disabled'" : '';?>
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
              <div class='col-sm-4'>
                <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' $class onchange='loadBranches(this)' data-last='" . $product->id . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $product->branch, "class='form-control chosen' $class onchange=\"loadPlans('#products{$i}', this.value)\"");?>
                </div>
              </div>
              <?php if(in_array($product->id, $unmodifiableProducts)) echo html::hidden("products[$i]", $product->id);?>
              <?php $i++;?>
              <?php endforeach;?>
              <div class='col-sm-4'>
                <div class="input-group">
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon fix-border'></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->linkPlan;?></th>
          <td id="plansBox" colspan="2">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
              <?php $plans = zget($productPlans, $product->id, array(0 => ''));?>
              <div class="col-sm-4" id="plan<?php echo $i;?>"><?php echo html::select("plans[" . $product->id . "]", $plans, $product->plan, "class='form-control chosen'");?></div>
              <?php $i++;?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($execution->desc), "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <?php $this->printExtendFields($execution, 'table');?>
        <tr>
          <th><?php echo $lang->execution->acl;?></th>
          <?php $class = $execution->grade == 2 ? "disabled='disabled'" : '';?>
          <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->execution->aclList, $execution->acl, "onclick='setWhite(this.value);' $class", 'block'));?></td>
        </tr>
        <tr class="<?php if($execution->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, $execution->whitelist, 'class="form-control chosen" multiple');?></td>
          <td></td>
        </tr>
        <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton();?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('errorSameProducts', $lang->execution->errorSameProducts);?>
<?php js::set('unmodifiableProducts',$unmodifiableProducts);?>
<?php js::set('tip', $lang->execution->notAllowRemoveProducts);?>
<?php include '../../common/view/footer.html.php';?>
