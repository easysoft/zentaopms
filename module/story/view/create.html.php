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
              echo "<span class='input-group-addon'>";
              echo html::a($this->createLink('productplan', 'create', "productID=$productID&branch=$branch"), $lang->productplan->create, '_blank');
              echo '&nbsp; ';
              echo html::a("javascript:loadProductPlans($productID)", $lang->refresh);
              echo '</span>';
          }
          ?>
          </div>
        </td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->story->source?></span>
            <?php echo html::select('source', $lang->story->sourceList, $source, "class='form-control'");?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->reviewedBy;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::select('assignedTo', $users, empty($needReview) ? $product->PO : '', "class='form-control chosen'");?>
            <span class='input-group-addon'><?php echo html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview' {$needReview}");?></span>
          </div>
        </td>
      </tr> 
      <tr>
        <th><?php echo $lang->story->title;?></th>
        <td colspan='2'>
          <div class='row'>
            <div class='col-sm-8'>
              <?php echo html::input('title', $storyTitle, "class='form-control'");?>
            </div>
            <div class='col-sm-2'>
              <div class='input-group'>
                <span class='input-group-addon fix-border'><?php echo $lang->story->pri?></span>
                <?php echo html::select('pri', (array)$lang->story->priList, $pri, "class='form-control'");?>
              </div>
            </div>
            <div class='col-sm-2'>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->story->estimateAB;?></span>
                <?php echo html::input('estimate', $estimate, "class='form-control'");?>
              </div>
            </div>
          </div>
        </td>
      </tr>  
      <tr>
        <th><?php echo $lang->story->spec;?></th>
        <td colspan='2'><?php echo html::textarea('spec', $spec, "rows='9' class='form-control'");?><div class='help-block'><?php echo $lang->story->specTemplate;?></div></td>
      </tr>  
         <tr>
        <th><?php echo $lang->story->verify;?></th>
        <td colspan='2'><?php echo html::textarea('verify', $verify, "rows='6' class='form-control'");?></td>
      </tr> 
      <tr>
        <th><?php echo $lang->story->mailto;?></th>
        <td>
          <div class='input-group'>
            <?php 
            echo html::select('mailto[]', $users, str_replace(' ' , '', $mailto), "multiple"); 
            if($contactLists) echo html::select('', $contactLists, '', "class='form-control chosen' onchange=\"setMailto('mailto', this.value)\"");
            if(empty($contactLists))
            {
                echo '<span class="input-group-addon">';
                echo '<a href="' . $this->createLink('company', 'browse') . '" target="_blank">' . $lang->user->contacts->manage . '</a>';
                echo '</span>';
                echo '<span class="input-group-addon">';
                echo '<a href="###" onclick="ajaxGetContacts(this)">' . $lang->refresh . '</a>';
                echo '</span>';
            }
            ?>
          </div>
        </td>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->story->keywords;?></span>
            <?php echo html::input('keywords', $keywords, 'class="form-control"');?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->legendAttatch;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr><td></td><td colspan='2' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </table>
    <span id='responser'></span>
  </form>
</div>
<?php js::set('storyModule', $lang->story->module);?>
<?php include '../../common/view/footer.html.php';?>
