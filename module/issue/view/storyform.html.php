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
  <td>
    <?php echo html::select('resolution', $lang->issue->resolveMethods, $resolution, 'class="form-control chosen" onchange="getSolutions()"');?>
  </td>
</tr>
<tr>
  <th class='w-110px'><?php echo $lang->story->product;?></th>
  <td>
    <div class='input-group'>
      <?php echo html::select('product', $products, $productID, "onchange='loadProduct(this.value);' class='form-control chosen control-product'");?>
      <?php if($this->session->currentProductType != 'normal' and isset($products[$productID])):?>
      <?php echo html::select('branch', $branches, $branch, "onchange='loadBranch();' class='form-control chosen control-branch'");?>
      <?php endif;?>
    </div>
  </td>
  <td>
    <div class='input-group' id='moduleIdBox'>
    <span class="input-group-addon"><?php echo $lang->story->module?></span>
      <?php
      echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'");
      if(count($moduleOptionMenu) == 1)
      {
          echo "<span class='input-group-addon'>";
          echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
          echo '&nbsp; ';
          echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
          echo '</span>';
      }
      ?>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->story->reviewedBy;?></th>
  <td><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
  <?php if(!$this->story->checkForceReview()):?>
  <td>
    <div class='checkbox-primary'>
      <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin'/>
      <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
    </div>
  </td>
  <?php endif;?>
</tr>
<tr>
  <th><?php echo $lang->story->title;?></th>
  <td colspan="2">
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
  <td colspan="2">
    <?php echo html::textarea('spec', $issue->desc, "rows='9' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true' placeholder='" . htmlspecialchars($lang->story->specTemplate . "\n" . $lang->noticePasteImg) . "'");?>
  </td>
</tr>
<?php if(strpos(",$showFields,", ',verify,') !== false):?>
<tr>
  <th><?php echo $lang->story->verify;?></th>
  <td colspan="2"><?php echo html::textarea('verify', $verify, "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
</tr>
<?php endif;?>
<tr>
  <th><?php echo $lang->issue->resolvedBy;?></th>
  <td>
    <?php echo html::select('resolvedBy', $users, $this->app->user->account, "class='form-control chosen'");?>
  </td>
</tr>
<tr>
  <th><?php echo $lang->issue->resolvedDate;?></th>
  <td>
     <div class='input-group has-icon-right'>
       <?php echo html::input('resolvedDate', date('Y-m-d'), "class='form-control form-date'");?>
       <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
     </div>
  </td>
</tr>
<tr>
  <td></td>
  <td>
    <?php echo html::hidden('type', 'requirement');?>
    <div class='form-action'><?php echo html::submitButton();?></div>
  </td>
</tr>
<script>
function loadProduct(productID)
{
    oldProductID = $('#product').val();

    loadProductBranches(productID)
    loadProductModules(productID);
}


function loadBranch()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    loadProductModules($('#product').val(), branch);
    loadProductPlans($('#product').val(), branch);
}

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

function loadProductModules(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;
    var moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=story&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    var $moduleIDBox = $('#moduleIdBox');
    $moduleIDBox.load(moduleLink, function()
    {
        $moduleIDBox.find('#module').chosen();
        if(typeof(storyModule) == 'string') $moduleIDBox.prepend("<span class='input-group-addon'>" + storyModule + "</span>");
        $moduleIDBox.fixInputGroup();
    });
}
</script>
