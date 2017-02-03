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
<?php include '../../common/view/form.html.php';?>
<?php js::set('holders', $lang->story->placeholder); ?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['story']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->story->create;?></strong>
    </div>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->story->product;?></th>
        <td class='w-p45-f'>
          <div class='input-group'>
            <?php echo html::select('product', $products, $productID, "onchange='loadProduct(this.value);' class='form-control chosen'");?>
            <?php if($product->type != 'normal') echo html::select('branch', $branches, $branch, "onchange='loadBranch();' class='form-control' style='width:120px'");?>
          </div>
        </td>
        <td>
          <div class='input-group' id='moduleIdBox'>
          <span class='input-group-addon'><?php echo $lang->story->module;?></span>
          <?php 
          echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");
          if(count($moduleOptionMenu) == 1)
          {
              echo "<span class='input-group-addon'>";
              echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branch"), $lang->tree->manage, '_blank');
              echo '&nbsp; ';
              echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
              echo '</span>';
          }
          ?>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->plan;?></th>
        <td>
          <div class='input-group' id='planIdBox'>
          <?php 
          echo html::select('plan', $plans, $planID, "class='form-control chosen'");
          if(count($plans) == 1) 
          {
              echo "<span class='input-group-btn'>";
              echo html::a($this->createLink('productplan', 'create', "productID=$productID&branch=$branch"), "<i class='icon icon-plus'></i>", '_blank', "class='btn' data-toggle='tooltip' title='{$lang->productplan->create}'");
              echo '&nbsp; ';
              echo html::a("javascript:loadProductPlans($productID)", "<i class='icon icon-refresh'></i>", '', "class='btn' data-toggle='tooltip' title='{$lang->refresh}'");
              echo '</span>';
          }
          ?>
          </div>
        </td>
        <?php if(strpos(",$showFields,", ',source,') !== false):?>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->story->source?></span>
            <?php echo html::select('source', $lang->story->sourceList, $source, "class='form-control'");?>
            <span class='input-group-addon fix-border'><?php echo $lang->comment?></span>
            <?php echo html::input('sourceNote', $sourceNote, "class='form-control' autocomplete='off'");?>
          </div>
        </td>
        <?php endif;?>
      </tr>
      <tr>
        <th><?php echo $lang->story->reviewedBy;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('assignedTo', $users, empty($needReview) ? $product->PO : '', "class='form-control chosen'");?>
            <?php if(!$this->story->checkForceReview()):?>
            <span class='input-group-addon'><?php echo html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview' {$needReview}");?></span>
            <?php endif;?>
          </div>
        </td>
      </tr> 
      <tr>
        <th><?php echo $lang->story->title;?></th>
        <td colspan='2'>
          <div class='row-table'>
            <div class='col-table'>
              <div class="input-group w-p100">
                <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->story->colorTag ?>' data-update-text='#title'>
                <?php echo html::input('title', $storyTitle, "class='form-control' autocomplete='off'");?>
              </div>
            </div>
            <?php
            $hiddenPri = strpos(",$showFields,", ',pri,') === false;
            $hiddenEst = strpos(",$showFields,", ',estimate,') === false;
            ?>
            <?php if(!$hiddenPri or !$hiddenEst):?>
            <?php $widthClass = ($hiddenPri or $hiddenEst) ? 'w-100px' : 'w-230px';?>
            <div class='col-table <?php echo $widthClass?>'>
              <div class="input-group">
                <?php if(!$hiddenPri):?>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->story->pri;?></span>
                <?php
                $hasCustomPri = false;
                foreach($lang->story->priList as $priKey => $priValue)
                {
                    if($priKey != $priValue)
                    {
                        $hasCustomPri = true;
                        break;
                    }
                }
                ?>
                <?php if($hasCustomPri):?>
                <?php echo html::select('pri', (array)$lang->story->priList, $pri, "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris' data-set='0,1,2,3,4'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('pri', (array)$lang->story->priList, $pri, "class='hide'");?>
                </div>
                <?php endif; ?>
                <?php endif;?>
                <?php if(!$hiddenEst):?>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->story->estimateAB;?></span>
                <?php echo html::input('estimate', $estimate, "class='form-control minw-60px' autocomplete='off'");?>
                <?php endif;?>
              </div>
            </div>
            <?php endif;?>
          </div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->story->spec;?></th>
        <td colspan='2'><?php echo html::textarea('spec', $spec, "rows='9' class='form-control disabled-ie-placeholder' placeholder='" . htmlspecialchars($lang->story->specTemplate) . "'");?></td>
      </tr>  
      <?php if(strpos(",$showFields,", ',verify,') !== false):?>
      <tr>
        <th><?php echo $lang->story->verify;?></th>
        <td colspan='2'><?php echo html::textarea('verify', $verify, "rows='6' class='form-control'");?></td>
      </tr>
      <?php endif;?>
      <?php
      $hiddenMailto   = strpos(",$showFields,", ',mailto,') === false;
      $hiddenKeywords = strpos(",$showFields,", ',keywords,') === false;
      ?>
      <?php if(!$hiddenMailto or !$hiddenKeywords):?>
      <?php $colspan = ($hiddenMailto or $hiddenKeywords) ? "colspan='2'" : '';?>
      <tr>
        <th><?php echo $hiddenMailto ? $lang->story->keywords : $lang->story->mailto;?></th>
        <?php if(!$hiddenMailto):?>
        <td>
          <div class='input-group' id='mailtoGroup'>
            <?php 
            echo html::select('mailto[]', $users, str_replace(' ' , '', $mailto), "multiple"); 
            echo $this->fetch('my', 'buildContactLists');
            ?>
          </div>
        </td>
        <?php endif;?>
        <?php if(!$hiddenKeywords):?>
        <td <?php echo $colspan?>>
          <div class='input-group'>
            <?php if(!$hiddenMailto):?>
            <span class='input-group-addon'><?php echo $lang->story->keywords;?></span>
            <?php endif;?>
            <?php echo html::input('keywords', $keywords, 'class="form-control" autocomplete="off"');?>
          </div>
        </td>
        <?php endif;?>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->story->legendAttatch;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr><td></td><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </table>
    <span id='responser'></span>
  </form>
</div>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=createFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php js::set('storyModule', $lang->story->module);?>
<?php include '../../common/view/footer.html.php';?>
