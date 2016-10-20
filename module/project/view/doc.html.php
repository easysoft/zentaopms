<?php
/**
 * The doc view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<div>
  <div id='titlebar'>
    <div class='heading'>
      <?php echo html::icon($lang->icons['doc']);?> <?php echo $lang->project->doc;?>
    </div>
    <div class='actions'>
      <?php common::printIcon('doc', 'create', "libID=" . key($libs) . "&moduleID=0&productID=0&projectID=$project->id&from=project");?>
    </div>
  </div>
  <table class='table table-fixed tablesorter' align='center' id='docList'>
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
      <tr class='text-center'>
        <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $doc->id)); else printf('%03d', $doc->id);?></td>
        <td><?php if(isset($modules[$doc->module]))print($modules[$doc->module]);?></td>
        <td class='text-left nobr'><nobr><?php echo html::a($viewLink, $doc->title);?></nobr></td>
        <td><?php echo $users[$doc->addedBy];?></td>
        <td><?php echo $doc->addedDate;?></td>
        <td>
          <?php 
          common::printIcon('doc', 'edit',   "doc=$doc->id", '', 'list');
          if(common::hasPriv('doc', 'delete'))
          {
              $deleteURL = $this->createLink('doc', 'delete', "docID=$doc->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"docList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->doc->delete}'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>

<?php include '../../common/view/footer.html.php';?>
