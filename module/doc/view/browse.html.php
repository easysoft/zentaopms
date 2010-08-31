<?php
/**
 * The browse view file of doc module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        http://www.zentaoms.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treeview.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div class='yui-d0'>
  <div id='featurebar'>
    <div class='f-right'>
      <?php common::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&productID=$productID&projectID=$projectID&from=doc", $lang->doc->create);?>
    </div>
  </div>
</div>

<div class='yui-d0 yui-t1' id='mainbox'>

  <div class="yui-main">
    <div class="yui-b">
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
            <td><?php echo $doc->type;?></td>
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
    </div>
  </div>

  <div class='yui-b' id='treebox'>
    <div class='box-title'><?php echo $libName;?></div>
    <div class='box-content'>
      <?php echo $moduleTree;?>
      <div class='a-right'>
        <?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->tree->manage);?>
      </div>
    </div>
  </div>

</div>  
<?php include './footer.html.php';?>
