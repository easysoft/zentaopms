<?php
/**
 * The view file of zahost module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@cnezsoft.com>
 * @package     zahost
 * @version     $Id: view.html.php $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php $browseLink = $this->session->zahostList ? $this->session->zahostList : $this->createLink('zahost', 'browse', "");?>
<?php $vars = "id={$zahost->hostID}&orderBy=%s";?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, "data-app='{$app->tab}'", 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $zahost->assetID;?></span>
      <span class='text' title='<?php echo $zahost->name;?>'><?php echo $zahost->name;?></span>
      <?php if($zahost->deleted):?>
      <span class='label label-danger'><?php echo $lang->zahost->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class="col-8 main-col">
    <div class="cell">
      <div class="detail zahost-detail">
        <div class="detail-title"><?php echo $lang->zahost->view;?></div>
        <div class="detail-content article-content">
          <div class="main-row">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->zaHostType;?>:</div>
                <div class="col-8"><?php echo $lang->zahost->zaHostTypeList[$zahost->hostType];?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->address;?>:</div>
                <div class="col-8"><?php echo $zahost->address;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->memory;?>:</div>
                <div class="col-8"><?php echo $zahost->memory;?></div>
              </div>
            </div>
          </div>
          <div class="main-row">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->virtualSoftware;?>:</div>
                <div class="col-8"><?php echo $zahost->virtualSoftware;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->cpu;?>:</div>
                <div class="col-8"><?php echo $zahost->cpu;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->disk;?>:</div>
                <div class="col-8"><?php echo $zahost->disk;?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="detail zahost-detail">
        <div class="detail-title"><?php echo $lang->zahost->desc;?></div>
        <div class="detail-content article-content"><?php echo !empty($zahost->desc) ? $zahost->desc : $lang->noData;?></div>
      </div>
      <?php if(!empty($nodeList)): ?>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->zahost->browseNode;?></div>
        <div class="detail-content article-content">
          <table class='table has-sort-head table-fixed' id='nodeList'>
            <thead>
              <tr>
                <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->zahost->name);?></th>
                <th class='c-cpu'><?php common::printOrderLink('cpu', $orderBy, $vars, $lang->executionnode->cpu);?></th>
                <th class='c-number'><?php common::printOrderLink('memory', $orderBy, $vars, $lang->executionnode->memory);?></th>
                <th class='c-number'><?php common::printOrderLink('disk', $orderBy, $vars, $lang->executionnode->disk);?></th>
                <th class='c-os'><?php common::printOrderLink('os', $orderBy, $vars, $lang->executionnode->os);?></th>
                <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->executionnode->status);?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($nodeList as $node):?>
              <tr>
                <td title="<?php echo $node->name;?>"><?php echo $node->name;?></td>
                <td><?php echo zget($config->executionnode->os->cpu, $node->cpu);?></td>
                <td><?php echo $node->memory . $this->lang->zahost->unitList['GB'];?></td>
                <td><?php echo $node->disk . zget($this->lang->zahost->unitList, $node->unit);?></td>
                <td><?php echo zget($config->executionnode->os->list, $node->os);?></td>
                <td><?php echo zget($lang->executionnode->statusList, $node->status);?></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif; ?>
      <?php
      $canBeChanged = common::canBeChanged('zahost', $zahost);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=zahost&objectID=$zahost->hostID");
      ?>
    </div>
    <?php $this->printExtendFields($zahost, 'div', "position=left&inForm=0&inCell=1");?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'btn btn-secondary');?>
        <div class='divider'></div>
        <?php echo $this->zahost->buildOperateMenu($zahost, 'view');?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>

<div id='mainActions' class='main-actions'>
  <?php common::printPreAndNext($browseLink);?>
</div>
<?php include '../../common/view/footer.html.php';?>
