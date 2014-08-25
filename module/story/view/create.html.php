<?php
/**
 * The create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
        <td class='w-p25-f'>
          <?php echo html::select('product', $products, $productID, "onchange='loadProduct(this.value);' class='form-control chosen'");?>
        </td>
        <td class='w-p15-f'>
          <div class='input-group' id='moduleIdBox'>
          <?php 
          echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");
          if(count($moduleOptionMenu) == 1)
          {
              echo "<span class='input-group-addon'>";
              echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=story"), $lang->tree->manage, '_blank');
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
              echo html::a($this->createLink('productplan', 'create', "productID=$productID"), $lang->productplan->create, '_blank');
              echo '&nbsp; ';
              echo html::a("javascript:loadProductPlans($productID)", $lang->refresh);
              echo '</span>';
          }
          ?>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->story->source;?></th>
        <td><?php echo html::select('source', $lang->story->sourceList, $source, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->title;?></th>
        <td colspan='3'><?php echo html::input('title', $storyTitle, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->story->spec;?></th>
        <td colspan='3'><?php echo html::textarea('spec', $spec, "rows='9' class='form-control'");?><div class='help-block'><?php echo $lang->story->specTemplate;?></div></td>
      </tr>  
         <tr>
        <th><?php echo $lang->story->verify;?></th>
        <td colspan='3'><?php echo html::textarea('verify', $verify, "rows='6' class='form-control'");?></td>
      </tr> 
       <tr>
        <th><?php echo $lang->story->pri;?></th>
        <td><?php echo html::select('pri', (array)$lang->story->priList, $pri, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->story->estimate;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::input('estimate', $estimate, "class='form-control'");?>
            <span class='input-group-addon'><?php echo $lang->story->hour;?></span>
          </div>
        </td>
      </tr> 
      <tr>
        <th><?php echo $lang->story->reviewedBy;?></th>
        <td><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
        <td><?php echo html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview' {$needReview}");?></td>
      </tr>  
       <tr>
        <th><nobr><?php echo $lang->story->mailto;?></nobr></th>
        <td colspan='3'>
          <div class='input-group'>
            <?php 
            echo html::select('mailto[]', $users, str_replace(' ' , '', $mailto), "multiple"); 
            if($contactLists) echo html::select('', $contactLists, '', "class='form-control' style='width: 150px' onchange=\"setMailto('mailto', this.value)\"");
            ?>
          </div>
        </td>
      </tr>

      <tr>
        <th><nobr><?php echo $lang->story->keywords;?></nobr></th>
        <td colspan='3'><?php echo html::input('keywords', $keywords, 'class="form-control"');?></td>
      </tr>
     <tr>
        <th><?php echo $lang->story->legendAttatch;?></th>
        <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>  
      <tr><td></td><td colspan='3' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </table>
    <span id='responser'></span>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
