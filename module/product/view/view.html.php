<?php
/**
 * The view view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: view.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['product']);?> <strong><?php echo $product->id;?></strong></span>
    <strong><?php echo $product->name;?></strong>
    <?php if($product->deleted):?>
    <span class='label label-danger'><?php echo $lang->product->deleted;?></span>
    <?php endif; ?>
  </div>
  <div class='actions'>
    <?php
    $params = "product=$product->id";
    $browseLink = $this->session->productList ? $this->session->productList : inlink('browse', "productID=$product->id");
    if(!$product->deleted)
    {
        ob_start();
        common::printIcon('product', 'close', "productID=$product->id", $product, 'button', '', '', 'iframe text-danger', true);

        echo "<div class='btn-group'>";
        common::printIcon('product', 'edit', $params);
        common::printIcon('product', 'delete', $params, '', 'button', '', 'hiddenwin');
        echo '</div>';
        common::printRPN($browseLink);

        $actionLinks = ob_get_contents();
        ob_end_clean();
        echo $actionLinks;
    }
    else
    {
        common::printRPN($browseLink);
    }
    ?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->product->desc;?></legend>
        <div class='article-content'><?php echo $product->desc;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='actions'><?php if(!$product->deleted) echo $actionLinks;?></div>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->product->basicInfo?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='strong w-80px'><?php echo $lang->product->name;?></th>
            <td <?php if($product->deleted) echo "class='deleted text-danger'";?>><strong><?php echo $product->name;?></strong></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->code;?></th>
            <td><?php echo $product->code;?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->PO;?></th>
            <td><?php echo $users[$product->PO];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->QD;?></th>
            <td><?php echo $users[$product->QD];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->RD;?></th>
            <td><?php echo $users[$product->RD];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->status;?></th>
            <td class='product-<?php echo $product->status?>'><?php echo $lang->product->statusList[$product->status];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->acl;?></th>
            <td><?php echo $lang->product->aclList[$product->acl];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->product->whitelist;?></th>
            <td>
              <?php
              $whitelist = explode(',', $product->whitelist);
              foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
              ?>
            </td>
          </tr>  
          <tr>
            <th><?php echo $lang->story->openedBy?></th>
            <td><?php echo $users[$product->createdBy];?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->story->openedDate?></th>
            <td><?php echo $product->createdDate;?></td>
          </tr>  
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->product->otherInfo?></legend>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='strong w-80px'><?php echo $lang->story->statusList['active']  . $lang->story->common;?></th>
            <td class='strong'><?php echo $product->stories['active']?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->statusList['changed']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['changed']?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->statusList['draft']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['draft']?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->statusList['closed']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['closed']?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->plans?></th>
            <td><?php echo $product->plans?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->projects?></th>
            <td><?php echo $product->projects?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->bugs?></th>
            <td><?php echo $product->bugs?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->docs?></th>
            <td><?php echo $product->docs?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->cases?></th>
            <td><?php echo $product->cases?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->builds?></th>
            <td><?php echo $product->builds?></td>
          </tr>
          <tr>
            <th><?php echo $lang->product->releases?></th>
            <td><?php echo $product->releases?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
