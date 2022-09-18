<?php
/**
 * The template browse view file of zahost module of ZenTaoPMS.
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
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <a href='#' class='btn btn-link querybox-toggle' id='bysearchTab'><i class='icon-search icon'></i> <?php echo $lang->zahost->byQuery;?></a>
  </div>
  <?php if(common::hasPriv('zahost', 'createTemplate')):?>
  <div class="btn-toolbar pull-right" id='createActionMenu'>
    <?php
    $misc = "class='btn btn-primary'";
    $link = $this->createLink('zahost', 'createTemplate', "hostID={$hostID}");
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->zahost->createTemplate, '', $misc);
    ?>
  </div>
  <?php endif;?>
</div>
<div id='queryBox' class='cell <?php if($browseType =='bysearch') echo 'show';?>' data-module='vmTemplate'></div>
<div id='mainContent' class='main-table'>
  <?php $vars = "hostID=$hostID&browseType=all&param=0&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
  <?php if(empty($templateList)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->zahost->templateEmpty;?></span>
      <?php if(common::hasPriv('zahost', 'createTemplate')) common::printLink('zahost', 'createTemplate', "hostID={$hostID}", '<i class="icon icon-plus"></i> ' . $lang->zahost->createTemplate, '', 'class="btn btn-info"');?>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head table-fixed' id='vmList'>
    <thead>
      <tr>
        <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
        <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->zahost->name);?></th>
        <th class='c-number'><?php common::printOrderLink('cpuCoreNum', $orderBy, $vars, $lang->zahost->cpuCoreNum);?></th>
        <th class='c-number'><?php common::printOrderLink('memorySize', $orderBy, $vars, $lang->zahost->memory);?></th>
        <th class='c-number'><?php common::printOrderLink('diskSize', $orderBy, $vars, $lang->zahost->diskSize);?></th>
        <th class='c-os'><?php common::printOrderLink('osCategory', $orderBy, $vars, $lang->zahost->osCategory);?></th>
        <th class='c-os'><?php common::printOrderLink('osType', $orderBy, $vars, $lang->zahost->osType);?></th>
        <th class='c-os'><?php common::printOrderLink('osVersion', $orderBy, $vars, $lang->zahost->osVersion);?></th>
        <th class='c-lang'><?php common::printOrderLink('osLang', $orderBy, $vars, $lang->zahost->osLang);?></th>
        <th class='c-actions-2 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($templateList as $template):?>
      <tr>
        <td><?php echo $template->id;?></td>
        <td title="<?php echo $template->name;?>"><?php echo $template->name;?></td>
        <td><?php echo $template->cpuCoreNum;?></td>
        <td><?php echo $template->memorySize . zget($this->lang->zahost->unitList, 'GB');?></td>
        <td><?php echo $template->diskSize . $template->unit;?></td>
        <td><?php echo zget($config->zahost->os->list, $template->osCategory);?></td>
        <td><?php echo zget($config->zahost->os->type[$template->osCategory], $template->osType);?></td>
        <td><?php echo zget($lang->zahost->versionList[$template->osType], $template->osVersion);?></td>
        <td><?php echo zget($lang->zahost->langList, $template->osLang);?></td>
        <td class='c-actions'>
          <?php common::printIcon('zahost', 'editTemplate', "id={$template->id}", $template, 'list', 'edit');?>
          <?php if(common::hasPriv('zahost', 'deleteTemplate')) echo html::a($this->createLink('zahost', 'delete', "id={$template->id}"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->zahost->delete}' class='btn'");;?>
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
