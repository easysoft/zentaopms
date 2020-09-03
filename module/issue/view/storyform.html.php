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
      <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen control-product'");?>
      <?php if($this->session->currentProductType != 'normal' and isset($products[$productID])):?>
      <?php echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control chosen control-branch'");?>
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
    <div class='table-row'>
      <div class='table-col'>
        <div class="input-control has-icon-right">
          <?php echo html::input('title', $issue->title, "class='form-control'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix">
              <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
          </div>
        </div>
      </div>
      <?php if(strpos(",$showFields,", ',pri,') !== false): // begin print pri selector?>
      <div class='table-col w-150px'>
        <div class="input-group">
          <span class="input-group-addon fix-border br-0"><?php echo $lang->story->pri;?></span>
          <?php
          $hasCustomPri = false;
          foreach($lang->story->priList as $priKey => $priValue)
          {
              if(!empty($priKey) and (string)$priKey != (string)$priValue)
              {
                  $hasCustomPri = true;
                  break;
              }
          }

          $priList = $lang->story->priList;
          if(end($priList)) unset($priList[0]);
          ?>
          <?php if($hasCustomPri):?>
          <?php echo html::select('pri', (array)$priList, $issue->pri, "class='form-control'");?>
          <?php else:?>
          <div class="input-group-btn pri-selector" data-type="pri">
            <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
              <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($issue->pri) ? '0' : $issue->pri?>" title="<?php echo $issue->pri?>"><?php echo $issue->pri?></span></span> &nbsp;<span class="caret"></span>
            </button>
            <div class='dropdown-menu pull-right'>
              <?php echo html::select('pri', (array)$priList, $issue->pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
            </div>
          </div>
          <?php endif;?>
        </div>
      </div>
      <?php endif; ?>
      <?php if(strpos(",$showFields,", ',estimate,') !== false):?>
      <div class='table-col w-120px'>
        <div class="input-group">
          <span class="input-group-addon fix-border br-0"><?php echo $lang->story->estimateAB;?></span>
          <input type="text" name="estimate" id="estimate" value="" class="form-control" autocomplete="off" placeholder='<?php echo $lang->story->hour;?>' />
        </div>
      </div>
      <?php endif;?>
    </div>
  </td>
</tr>
<tr>
  <th><?php echo $lang->story->spec;?></th>
  <td colspan="2">
    <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=story&link=spec');?>
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
    <div class='form-action'><?php echo html::submitButton();?></div>
  </td>
</tr>
