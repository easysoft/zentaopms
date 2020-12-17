<?php
/**
 * The create story view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<tr>
  <th><?php echo $lang->issue->resolution;?></th>
  <td colspan='2'>
    <?php echo html::select('resolution', $lang->issue->resolveMethods, $resolution, 'class="form-control chosen" onchange="getSolutions()"');?>
  </td>
</tr>
<tr>
  <th class='w-110px'><?php echo $lang->story->product;?></th>
  <td colspan='2'>
    <div class='input-group'>
      <?php echo html::select('product', $products, $productID, "onchange='loadProduct(this.value);' class='form-control chosen control-product'");?>
      <?php if($this->session->currentProductType != 'normal' and isset($products[$productID])):?>
      <?php echo html::select('branch', $branches, $branch, "onchange='loadBranch();' class='form-control chosen control-branch'");?>
      <?php endif;?>
    </div>
  </td>
  <td colspan='2'>
    <div class='input-group' id='moduleIdBox'>
      <span class="input-group-addon"><?php echo $lang->story->module;?></span>
      <?php
      echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");
      if(count($moduleOptionMenu) == 1)
      {
          echo "<span class='input-group-addon'>";
          echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='90%'");
          echo '&nbsp; ';
          echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
          echo '</span>';
      }
      ?>
    </div>
  </td>
</tr>
<tr>
  <th class='planTh'><?php echo $lang->story->planAB;?></th>
  <td colspan='2'>
    <div class='input-group' id='planIdBox'>
      <?php
      echo html::select('plan', $plans, 0, "class='form-control chosen'");
      if(count($plans) == 1)
      {
          echo "<div class='input-group-btn'>";
          echo html::a($this->createLink('productplan', 'create', "productID=$productID&branch=$branch", '', true), "<i class='icon icon-plus'></i>", '', "class='btn btn-icon' data-toggle='modal' data-type='iframe' data-width='95%' title='{$lang->productplan->create}'");
          echo '</div>';
          echo "<div class='input-group-btn'>";
          echo html::a("javascript:void(0)", "<i class='icon icon-refresh'></i>", '', "class='btn btn-icon refresh' data-toggle='tooltip' title='{$lang->refresh}' onclick='loadProductPlans($productID)'");
          echo '</div>';
      }
      ?>
    </div>
  </td>
  <?php if(strpos(",$showFields,", ',source,') !== false):?>
  <td colspan="2" class='sourceTd'>
    <div class="input-group">
      <div class="input-group">
        <div class="input-group-addon"><?php echo $lang->story->source;?></div>
        <?php echo html::select('source', $lang->story->sourceList, 0, "class='form-control chosen'");?>
        <span class='input-group-addon'><?php echo $lang->story->sourceNote;?></span>
        <?php echo html::input('sourceNote', '', "class='form-control' style='width:140px;'");?>
      </div>
    </div>
  </td>
  <?php endif;?>
</tr>
<tr>
  <th><?php echo $lang->story->reviewedBy;?></th>
  <td colspan='2'><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
  <?php if(!$this->story->checkForceReview()):?>
  <td colspan='2'>
    <div class='checkbox-primary'>
      <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin'/>
      <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
    </div>
  </td>
  <?php endif;?>
</tr>
<tr>
  <th><?php echo $lang->story->title;?></th>
  <td colspan="4">
    <div class="input-group title-group">
      <div class="input-control has-icon-right required">
        <?php echo html::input('title', $issue->title, "class='form-control'");?>
      </div>
      <span class="input-group-addon fix-border br-0"><?php echo $lang->story->pri;?></span>
      <div class="input-group-btn pri-selector w-80px" data-type="pri">
        <?php echo html::select('pri', $lang->story->priList, $issue->pri, "class='form-control'");?>
      </div>
      <div class='table-col w-120px'>
        <div class="input-group">
          <span class="input-group-addon fix-border br-0"><?php echo $lang->story->estimateAB;?></span>
          <input type="text" name="estimate" id="estimate" value="" class="form-control" autocomplete="off" placeholder='<?php echo $lang->story->hour;?>' />
        </div>
      </div>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->story->spec;?></th>
  <td colspan="4">
    <?php echo html::textarea('spec', $issue->desc, "rows='9' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true' placeholder='" . htmlspecialchars($lang->story->specTemplate . "\n" . $lang->noticePasteImg) . "'");?>
  </td>
</tr>
<?php if(strpos(",$showFields,", ',verify,') !== false):?>
<tr>
  <th><?php echo $lang->story->verify;?></th>
  <td colspan="4"><?php echo html::textarea('verify', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
</tr>
<?php endif;?>
<tr>
  <th><?php echo $lang->issue->resolvedBy;?></th>
  <td colspan='2'>
    <?php echo html::select('resolvedBy', $users, $this->app->user->account, "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedDate;?></th>
  <td colspan='2'>
     <div class='input-group has-icon-right'>
       <?php echo html::input('resolvedDate', date('Y-m-d'), "class='form-control form-date'");?>
       <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
     </div>
  </td>
</tr>
<tr>
  <td></td>
  <td>
    <input type="hidden" name="type" value="story">
    <div class='form-action'><?php echo html::submitButton();?></div>
  </td>
</tr>
<?php js::set('storyModule', $lang->story->module);?>
<script>
$("#product").change();

/**
 * Load branches and modules according to the product.
 *
 * @param  int   productID
 * @access public
 * @return void
 */
function loadProduct(productID)
{
    loadProductBranches(productID)
    loadProductModules(productID);
    loadProductPlans(productID);
}

/**
 * Get modules and plans by branch.
 *
 * @access public
 * @return void
 */
function loadBranch()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    loadProductModules($('#product').val(), branch);
    loadProductPlans($('#product').val(), branch);
}

/**
 * Acquire plans based on products and product branches.
 *
 * @param  int   productID
 * @param  int   branch
 * @access public
 * @return void
 */
function loadProductPlans(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;
    var planLink  = createLink('product', 'ajaxGetPlans', 'productID=' + productID + '&branch=' + branch + '&planID=' + $('#plan').val() + '&fieldID=&needCreate=true');
    var planIdBox = $('#planIdBox');
    planIdBox.load(planLink, function()
    {
        planIdBox.find('#plan').chosen();
        planIdBox.fixInputGroup();
    });
}

/**
 * Get product branches.
 *
 * @param  int   productID
 * @access public
 * @return void
 */
function loadProductBranches(productID)
{
    $('#branch').remove();
    $('#branch_chosen').remove();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        var $product = $('#product');
        var $inputGroup = $product.closest('.input-group');
        $inputGroup.find('.input-group-addon').toggleClass('hidden', !data);
        if(data)
        {
            $inputGroup.append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '65px').chosen();
        }
        $inputGroup.fixInputGroup();
    })
}

/**
 * Get the product module.
 *
 * @param  int   productID
 * @param  int   branch
 * @access public
 * @return void
 */
function loadProductModules(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;
    var moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    var moduleIdBox = $('#moduleIdBox');
    moduleIdBox.load(moduleLink, function()
    {
        moduleIdBox.find('#module').chosen();
        if(typeof(storyModule) == 'string') moduleIdBox.prepend("<span class='input-group-addon'>" + storyModule + "</span>");
        moduleIdBox.fixInputGroup();
    });
}
</script>
