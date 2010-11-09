<?php
/**
 * The view file of release module's view method of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <table class='table-1'> 
    <caption><?php echo $lang->release->view;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->release->product;?></th>
      <td><?php echo $release->productName;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->name;?></th>
      <td class='<?php if($release->deleted) echo 'deleted';?>'><?php echo $release->name;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->build;?></th>
      <td><?php echo $release->buildName;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->date;?></th>
      <td><?php echo $release->date;?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->desc;?></th>
      <td><?php echo nl2br($release->desc);?></td>
    </tr>  
  </table>
  <div class='a-center f-16px strong'>
    <?php
    $browseLink = $this->session->releaseList ? $this->session->releaseList : inlink('browse', "productID=$release->product");
    if(!$release->deleted)
    {
        common::printLink('release', 'edit',   "releaseID=$release->id", $lang->edit);
        common::printLink('release', 'delete', "releaseID=$release->id", $lang->delete, 'hiddenwin');
    }
    echo html::a($browseLink, $lang->goback);
    ?>
  </div>
  <?php include '../../common/view/action.html.php';?>
</div>  
<?php include '../../common/view/footer.html.php';?>
