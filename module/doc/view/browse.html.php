<?php
/**
 * The browse view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div id='featurebar'>
  <div class='f-right'>
    <?php common::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&productID=$productID&projectID=$projectID&from=doc", $lang->doc->create);?>
  </div>
</div>

<table class='cont-lt3'>
  <tr valign='top'>
    <td class='side'>
      <div class='box-title'><?php echo $libName;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->tree->manage);?>
        </div>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable'>
        <thead>
          <tr class='colhead'>
            <?php $vars = "libID=$libID&module=$moduleID&productID=$productID&projectID=$projectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th class='w-id'> <?php common::printOrderLink('id',    $orderBy, $vars, $lang->idAB);?></th>
            <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->doc->title);?></th>
            <th class='w-100px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->doc->type);?></th>
            <th class='w-100px'><?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->doc->addedBy);?></th>
            <th class='w-120px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate);?></th>
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
            <td class='a-left nobr'><nobr><?php echo html::a($viewLink, $doc->title);?></nobr></td>
            <td><?php echo $lang->doc->types[$doc->type];?></td>
            <td><?php echo $users[$doc->addedBy];?></td>
            <td><?php echo date("m-d H:i", strtotime($doc->addedDate));?></td>
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
      <?php $pager->show();?>
    </td>              
  </tr>    
</table>  
<?php include './footer.html.php';?>
