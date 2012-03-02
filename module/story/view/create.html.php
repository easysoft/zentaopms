<?php
/**
 * The create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
<div class='box-title'><span class='icon-add2'></span><?php echo $lang->story->create;?></div>
<div class='box-content' id='form-content'>
  <div class='form-item'>
    <p><strong class='a-14px'><?php echo $lang->story->title;?></strong> <span class='gray'><?php echo $lang->story->form->titleNote?></span></p>
    <p><?php echo html::input('title', $title, "class='w-p98'");?></p>
  </div>
  <div>
    <span class='gray'><?php echo $lang->story->form->area?></span>
    <div class='form-item'>
      <table class='table-1'>
        <tr>
          <th class='rowhead'><?php echo $lang->story->product;?></th>
          <td>
            <?php echo html::select('product', $products, $productID, "onchange=loadProduct(this.value); class='select-3'");?>
            <span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID);?></span>
          </td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->story->plan;?></th>
          <td><span id='planIdBox'><?php echo html::select('plan', $plans, $planID, 'class=select-3');?></span></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->story->source;?></th>
          <td><?php echo html::select('source', $lang->story->sourceList, $source, 'class=select-3');?></td>
        </tr>
      </table>
    </div>
  </div>
  <div>
    <span class='gray'><?php echo $lang->story->form->desc?></span>
    <div class='form-item'>
      <table class='table-1'>
        <tr>
          <th class='rowhead' valign='top'><?php echo $lang->story->spec;?></th>
          <td><?php echo html::textarea('spec', $spec, "rows='9' class='w-p99'");?><br /><?php echo $lang->story->specTemplate;?></td>
        </tr>  
           <tr>
          <th class='rowhead' valign='top'><?php echo $lang->story->verify;?></th>
          <td><?php echo html::textarea('verify', $verify, "rows='6' class='w-p99'");?></td>
        </tr> 
      </table>
    </div>
  </div>
  <div>
    <span class='gray'><?php echo $lang->story->form->resource?></span>
    <div class='form-item'>
      <table class='table-1'>
        <tr>
          <th class='rowhead'><?php echo $lang->story->pri;?></th>
          <td><?php echo html::select('pri', (array)$lang->story->priList, $pri, 'class=select-3');?></td>
        </tr>
        <tr>
          <th class='rowhead'><?php echo $lang->story->estimate;?></th>
          <td><?php echo html::input('estimate', $estimate, "class='text-3'");?></td>
        </tr> 
        <tr>
          <th class='rowhead'><?php echo $lang->story->reviewedBy;?></th>
          <td><?php echo html::select('assignedTo', $users, '', 'class=select-3') . html::checkbox('needNotReview', $lang->story->needNotReview, '', "id='needNotReview'");?></td>
        </tr>  
         <tr>
          <th class='rowhead'><nobr><?php echo $lang->story->mailto;?></nobr></th>
          <td><?php echo html::input('mailto', $mailto, 'class="w-p99"');?></td>
        </tr>
        <tr>
          <th class='rowhead'><nobr><?php echo $lang->story->keywords;?></nobr></th>
          <td><?php echo html::input('keywords', $keywords, 'class="w-p99"');?></td>
        </tr>
      </table>
    </div>
  </div>
  <div>
    <span class='gray'><?php echo $lang->story->form->file?></span>
    <div class='form-item'>
      <table class='table-1'>
        <tr>
          <th class='rowhead' valign='top'><?php echo $lang->story->legendAttatch;?></th>
          <td><?php echo $this->fetch('file', 'buildform', "fileConte=2&precent=0.99");?></td>
        </tr>  
      </table>
    </div>
  </div>
  <p><strong><?php echo $lang->story->afterSubmit;?></strong><?php echo html::checkbox('newStory', $lang->story->newStory);?> </p>
  <hr/>
  <p align='center'><?php echo html::submitButton() . html::resetButton();?></p>
</div>
</form>
<?php include '../../common/view/footer.html.php';?>
