<?php
/**
 * The view view of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='g'><div class='u-1'>
  <table align='center' class='table-1'> 
    <caption><?php echo $lang->product->view;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->product->name;?></th>
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
      <th class='rowhead'><?php echo $lang->product->desc;?></th>
      <td class='content'><?php echo $product->desc;?></td>
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
  </table>
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
  <?php include '../../common/view/action.html.php';?>
</div>  
<?php include '../../common/view/footer.html.php';?>
