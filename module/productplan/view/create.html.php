<?php
/**
 * The create view of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('productID', $productID);?>
<?php js::set('lastLang', $lang->productplan->last);?>
<?php js::set('parentPlanID', $parent);?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent'class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2> <?php echo $parent ? $lang->productplan->createChildren : $lang->productplan->create;?></h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tbody>
          <?php if($parent):?>
          <tr>
            <th><?php echo $lang->productplan->parent;?></th>
            <td class='muted'><?php echo $parentPlan->title;?>
            </td><td></td><td></td>
          </tr>
          <?php elseif(!$product->shadow):?>
          <tr>
            <th><?php echo $lang->productplan->product;?></th>
            <td class='muted'><?php echo $product->name;?></td><td></td><td></td>
          </tr>
          <?php endif;?>
          <?php if(!$parent):?>
          <tr>
            <th><?php echo $lang->productplan->parent;?></th>
            <td><?php echo html::select('parent', array(0 => '') + $parentPlanPairs, 0, "class='form-control chosen'");?>
          </tr>
          <?php endif;?>
          <?php if($product->type != 'normal'):?>
          <tr>
            <th><?php echo $lang->product->branch;?></th>
            <?php
            if($parent)
            {
                foreach($branches as $branchID => $branchName)
                {
                    if(strpos(",$parentPlan->branch,", ",$branchID,") === false) unset($branches[$branchID]);
                }
            }
            ?>
            <td class='required'><?php echo html::select('branch[]', $branches, '', "class='form-control chosen' multiple");?></td><td></td><td></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->productplan->title;?></th>
            <td><?php echo html::input('title', '', "class='form-control' required");?></td>
            <td colspan='2' class='muted'><?php if($lastPlan) echo '(' . $lang->productplan->last . ': ' . $lastPlan->title . ')';?></td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->begin;?></th>
            <td><?php echo html::input('begin', formatTime($begin), "class='form-control form-date'");?></td>
            <td>
              <div class='checkbox-primary'>
                <input type='checkbox' id='future' name='future' value='1' />
                <label for='future'><?php echo $lang->productplan->future;?></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->end;?></th>
            <td><?php echo html::input('end', '', "class='form-control form-date'");?></td>
            <td colspan='2'><?php echo html::radio('delta', $lang->productplan->endList , '', "onclick='computeEndDate(this.value)'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->productplan->desc;?></th>
            <td colspan='3'><?php echo html::textarea('desc', '', "rows='10' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <?php $this->printExtendFields('', 'table', 'columns=3');?>
          <tr>
            <td colspan='4' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
              <?php echo html::hidden('product', $product->id);?>
              <?php if($parent):?>
              <?php echo html::hidden('parent', $parent);?>
              <?php endif;?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
