<?php
/**
 * The doc view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div class='yui-d0'>

  <table class='table-1 fixed colored tablesorter' align='center'>
    <caption class='caption-tr'><?php common::printLink('doc', 'create', "libID=product&moduleID=0&productID={$product->id}&projectID=0&from=product", $lang->doc->create);?></caption>
    <thead>
      <tr class='colhead'>
        <th class='w-id'><?php echo $lang->idAB;?></th>
        <th><?php echo $lang->doc->module;?></th>
        <th><?php echo $lang->doc->title;?></th>
        <th><?php echo $lang->doc->addedBy;?></th>
        <th><?php echo $lang->doc->addedDate;?></th>
        <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($docs as $key => $doc):?>
      <?php
      $viewLink = $this->createLink('doc', 'view', "docID=$doc->id");
      $canView  = common::hasPriv('doc', 'view');
      ?>
      <tr class='a-center'>
        <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $doc->id)); else printf('%03d', $doc->id);?></td>
        <td><?php echo $doc->module;?></td>
        <td class='a-left nobr'><nobr><?php echo html::a($viewLink, $doc->title);?></nobr></td>
        <td><?php echo $users[$doc->addedBy];?></td>
        <td><?php echo $doc->addedDate;?></td>
        <td>
          <?php 
          $vars = "doc={$doc->id}";
          if(!common::printLink('doc', 'edit',   $vars, $lang->edit)) echo $lang->edit;
          if(!common::printLink('doc', 'delete', $vars, $lang->delete, 'hiddenwin')) echo $lang->delete;
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>  
<?php include '../../common/view/footer.html.php';?>
