<?php
/**
 * The browse view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language='Javascript'>
var browseType = '<?php echo $browseType;?>';
</script>
<div id='featurebar'>
  <div class='f-left'>
    <span id='bymoduleTab' onclick='browseByModule()'><a href='#'><?php echo $lang->doc->moduleDoc;?></a></span>
    <span id='bysearchTab'><a href='#'><span class='icon-search'></span><?php echo $lang->doc->searchDoc;?></a></span>
  </div>
  <div class='f-right'>
    <?php common::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&productID=$productID&projectID=$projectID&from=doc", $lang->doc->create);?>
  </div>
</div>
<div id='querybox' class='<?php if($browseType !='bysearch') echo 'hidden';?>'></div>

<table class='cont-lt3'>
  <tr valign='top'>
    <td class='side' id='treebox'>
      <div class='box-title'><?php echo $libName;?></div>
      <div class='box-content'>
        <?php echo $moduleTree;?>
        <div class='a-right'>
          <?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->doc->manageType);?>
          <?php if(is_numeric($libID)) common::printLink('tree', 'fix', "root=$libID&type=customdoc", $lang->tree->fix, 'hiddenwin');?>
        </div>
      </div>
    </td>
    <td class='divider'></td>
    <td>
      <table class='table-1 fixed colored tablesorter datatable'>
        <thead>
          <tr class='colhead'>
            <?php $vars = "libID=$libID&module=$moduleID&productID=$productID&projectID=$projectID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
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
            <td><?php isset($users[$doc->addedBy]) ? print($users[$doc->addedBy]) : print($doc->addedBy);?></td>
            <td><?php echo date("m-d H:i", strtotime($doc->addedDate));?></td>
            <td>
              <?php 
              $vars = "doc={$doc->id}";
              common::printIcon('doc', 'edit',   $vars, '', 'list');
              common::printIcon('doc', 'delete', $vars, '', 'list', '', 'hiddenwin');
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
      </table>
    </td>              
  </tr>    
</table>  
<?php include './footer.html.php';?>
