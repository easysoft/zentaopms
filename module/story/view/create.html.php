<?php
/**
 * The create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: create.html.php 4902 2013-06-26 05:25:58Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php js::set('holders', $lang->story->placeholder); ?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->story->create;?></h2>
      <div class="pull-right btn-toolbar">
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=createFields')?>
        <?php include '../../common/view/customfield.html.php';?>
      </div>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->story->product;?></th>
            <td colspan="2">
              <?php if($product->type != 'normal'):?>
              <div class='input-group'>
              <?php endif;?>
              <?php echo html::select('product', $products, $productID, "onchange='loadProduct(this.value);' class='form-control chosen control-product'");?>
              <?php if($product->type != 'normal'):?>
              <span class='input-group-addon fix-border fix-padding'></span>
              <?php echo html::select('branch', $branches, $branch, "onchange='loadBranch();' class='form-control chosen control-branch'");?>
              </div>
              <?php endif;?>
            </td>
            <td colspan="2">
              <div class='input-group' id='moduleIdBox'>
                <div class="input-group-addon"><?php echo $lang->story->module;?></div>
                <?php
                echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");
                if(count($moduleOptionMenu) == 1)
                {
                    echo "<div class='input-group-addon'>";
                    echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                    echo '&nbsp; ';
                    echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
                    echo '</div>';
                }
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->story->plan;?></th>
            <td colspan="2">
              <div class='input-group' id='planIdBox'>
                <?php
                echo html::select('plan', $plans, $planID, "class='form-control chosen'");
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
            <td colspan="2">
              <div class="input-group">
                <div class="input-group">
                  <div class="input-group-addon"><?php echo $lang->story->source;?></div>
                  <?php echo html::select('source', $lang->story->sourceList, $source, "class='form-control chosen'");?>
                  <span class='input-group-addon'><?php echo $lang->story->sourceNote;?></span>
                  <?php echo html::input('sourceNote', $sourceNote, "class='form-control' autocomplete='off' style='width:140px;'");?>
                </div>
              </div>
            </td>
            <?php endif;?>
          </tr>
          <tr>
            <th><?php echo $lang->story->title;?></th>
            <td colspan="4">
              <div class="input-control has-icon-right">
                <?php echo html::input('title', $storyTitle, "class='form-control input-story-title' autocomplete='off' required");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                </div>
              </div>
            </td>
          </tr>
          <?php if(strpos(",$showFields,", ',pri,') !== false):?>
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
          if(end($priList))
          {
              unset($priList[0]);
              $priList[0] = '';
          }
          ?>
          <tr>
            <th><?php echo $lang->story->pri;?></th>
            <td colspan="<?php echo $hasCustomPri ? 1 : 4;?>">
              <?php if($hasCustomPri):?>
              <?php echo html::select('pri', (array)$priList, $pri, "class='form-control chosen'");?> 
              <?php else: ?>
              <?php echo html::select('pri', (array)$priList, $pri, "class='form-control' data-provide='labelSelector'");?> 
              <?php endif; ?>
            </td>
          </tr>
          <?php endif;?>
          <?php if(strpos(",$showFields,", ',estimate,') !== false):?>
          <tr>
            <th><?php echo $lang->story->estimateAB;?></th>
            <td><input type="number" min="0" step="0.5" name="estimate" id="estimate" value="<?php echo $estimate;?>" class="form-control" autocomplete="off"></td>
            <td class="muted"><?php echo $lang->story->hour;?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->story->reviewedBy;?></th>
            <td><?php echo html::select('assignedTo', $users, empty($needReview) ? $product->PO : '', "class='form-control chosen'");?></td>
            <?php if(!$this->story->checkForceReview()):?>
            <td>
              <div class='checkbox-primary'>
                <input id='needNotReview' name='needNotReview' value='1' type='checkbox' class='no-margin' <?php echo $needReview;?>/>
                <label for='needNotReview'><?php echo $lang->story->needNotReview;?></label>
              </div>
            </td>
            <?php endif;?>
          </tr> 
          <tr>
            <th><?php echo $lang->story->spec;?></th>
            <td colspan="4"><?php echo html::textarea('spec', $spec, "rows='9' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true' placeholder='" . htmlspecialchars($lang->story->specTemplate) . "'");?></td>
          </tr>
          <?php if(strpos(",$showFields,", ',verify,') !== false):?>
          <tr>
            <th><?php echo $lang->story->verify;?></th>
            <td colspan="4"><?php echo html::textarea('verify', $verify, "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->story->legendAttatch;?></th>
            <td colspan='4'><?php echo $this->fetch('file', 'buildform');?></td>
          </tr>  
          <?php if(strpos(",$showFields,", ',mailto,') !== false):?>
          <tr>
            <th><?php echo $lang->story->mailto;?></th>
            <td colspan="4">
              <div class="input-group">
                <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $mailto), "class='form-control chosen' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
                <?php echo $this->fetch('my', 'buildContactLists');?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <?php if(strpos(",$showFields,", ',keywords,') !== false):?>
          <tr>
            <th><?php echo $lang->story->keywords;?></th>
            <td colspan="4">
              <?php echo html::input('keywords', $keywords, 'class="form-control" autocomplete="off"');?>
            </td>
          </tr>
          <?php endif;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-center form-actions">
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php js::set('storyModule', $lang->story->module);?>
<?php include '../../common/view/footer.html.php';?>
