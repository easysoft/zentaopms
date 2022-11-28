<?php
/**
 * The image browse view file of zahost module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <liyuchun@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('hostID', $hostID);?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php echo html::a($this->createLink('zahost', 'browseimage', "hostID=$hostID"), "<span class='text'>{$lang->zahost->image->browseImage}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
</div>
<div id='queryBox' class='cell <?php if($browseType =='bysearch') echo 'show';?>' data-module='vmTemplate'></div>
<div id='mainContent' class='main-table'>
  <?php $vars = "hostID=$hostID&browseType=all&param=0&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
  <?php if(empty($imageList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->zahost->image->imageEmpty;?></span>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head table-fixed' id='imageList'>
    <thead>
      <tr>
        <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->zahost->image->name);?></th>
        <th class=''><?php echo $lang->zahost->image->desc;?></th>
        <th class='c-number'><?php common::printOrderLink('memory', $orderBy, $vars, $lang->zahost->image->memory);?></th>
        <th class='c-number'><?php common::printOrderLink('disk', $orderBy, $vars, $lang->zahost->image->disk);?></th>
        <th><?php echo $lang->zahost->status;?></th>
        <th><?php echo $lang->zahost->image->progress;?></th>
        <th class='c-actions-3'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($imageList as $image):?>
      <tr>
        <td title="<?php echo $image->name;?>"><?php echo $image->name;?></td>
        <td><?php echo $image->desc;?></td>
        <td><?php echo $image->memory . zget($this->lang->zahost->unitList, 'GB');?></td>
        <td><?php echo $image->disk . zget($this->lang->zahost->unitList, 'GB');?></td>
        <td class='image-status-<?php echo zget($image, 'id', 0);?>'><?php echo zget($lang->zahost->image->statusList, $image->status, '');?></td>
        <td class="image-progress-<?php echo zget($image, 'id', 0);?>"><?php echo $image->status == 'completed' ? '100%' : '';?></td>
        <td class='c-actions'>
          <?php common::printIcon('zahost', 'ajaxdownloadImage', "hostID={$hostID}&imageName={$image->name}&imageID={$image->id}", $image, 'list', 'download', '', in_array($image->status, array("completed", "inprogress", "created")) ? "disabled image-download-" . zget($image, 'id', 0) : "image-download-" . zget($image, 'id', 0));?>
          <?php //if(common::hasPriv('zahost', 'downloadImage')) echo html::a($this->createLink('zahost', 'downloadImage', "id={$image->id}"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->zahost->image->downloadImage}' class='btn'");?>
          <?php //common::printIcon('zahost', 'editImage', "id={$image->id}", $image, 'list', 'edit');?>
          <?php //if(common::hasPriv('zahost', 'deleteImage')) echo html::a($this->createLink('zahost', 'deleteImage', "id={$image->id}"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->zahost->delete}' class='btn'");?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
