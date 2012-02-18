<?php
/**
 * The view view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<table class='cont-rt5'> 
  <caption>
    <?php echo $lang->product->view;?>
    <div class='f-right f-16px strong'>
      <?php
      $browseLink = $this->session->productList ? $this->session->productList : inlink('browse', "productID=$product->id");
      if(!$product->deleted)
      {
          common::printLink('product', 'edit',   "productID=$product->id", $lang->edit);
          common::printLink('product', 'delete', "productID=$product->id", $lang->delete, 'hiddenwin');
      }
      ?>
    </div>
  </caption>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->product->desc;?></legend>
        <div class='content'><?php echo $product->desc;?></div>
      </fieldset>
      <?php include '../../common/view/action.html.php';?>
      <div class='a-center f-16px strong'>
      <?php
      $browseLink = $this->session->productList ? $this->session->productList : inlink('browse', "productID=$product->id");
      if(!$product->deleted)
      {
          common::printLink('product', 'edit',   "productID=$product->id", $lang->edit);
          common::printLink('product', 'delete', "productID=$product->id", $lang->delete, 'hiddenwin');
      }
      echo html::a($browseLink, $lang->goback);
      ?>
      </div>
    </td>
    <td class="divider"></td>
    <td class="side">
      <fieldset>
        <legend><?php echo $lang->product->basicInfo?></legend>
        <table class='table-1 a-left'>
          <tr>
            <th width='25%' class='a-right'><?php echo $lang->product->name;?></th>
            <td <?php if($product->deleted) echo "class='deleted'";?>><?php echo $product->name;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->code;?></th>
            <td><?php echo $product->code;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->PO;?></th>
            <td><?php echo $users[$product->PO];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->QM;?></th>
            <td><?php echo $users[$product->QM];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->RM;?></th>
            <td><?php echo $users[$product->RM];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->status;?></th>
            <td><?php echo $lang->product->statusList[$product->status];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->acl;?></th>
            <td><?php echo $lang->product->aclList[$product->acl];?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->product->whitelist;?></th>
            <td>
              <?php
              $whitelist = explode(',', $product->whitelist);
              foreach($whitelist as $groupID) if(isset($groups[$groupID])) echo $groups[$groupID] . '&nbsp;';
              ?>
            </td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->story->openedBy?></th>
            <td><?php echo $product->createdBy;?></td>
          </tr>  
          <tr>
            <th class='rowhead'><?php echo $lang->story->openedDate?></th>
            <td><?php echo $product->createdDate;?></td>
          </tr>  
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->product->otherInfo?></legend>
        <table class='table-1 a-left'>
          <tr>
            <th width='25%' class='a-right'><?php echo $lang->story->statusList['active']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['active']?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->story->statusList['changed']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['changed']?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->story->statusList['draft']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['draft']?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->story->statusList['closed']  . $lang->story->common;?></th>
            <td><?php echo $product->stories['closed']?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->plans?></th>
            <td><?php echo $product->plans?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->projects?></th>
            <td><?php echo $product->projects?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->bugs?></th>
            <td><?php echo $product->bugs?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->docs?></th>
            <td><?php echo $product->docs?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->cases?></th>
            <td><?php echo $product->cases?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->bulids?></th>
            <td><?php echo $product->bulids?></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->product->releases?></th>
            <td><?php echo $product->releases?></td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<?php include '../../common/view/footer.html.php';?>
