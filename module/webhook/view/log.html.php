<?php
/**
 * The log view file of log module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     log
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="main-header">
    <h2>
      <?php echo html::a(inlink('browse'), $lang->webhook->common);?>
      <small class="text-muted"> <?php echo $webhook->name;?></small>
      <small class="text-muted"> <?php echo $lang->webhook->log;?></small>
    </h2>
    <div class='btn-toolbar pull-right'>
      <div class='btn-group'>
        <div class='btn-group' id='createActionMenu'>
          <?php common::printLink('admin', 'log', '', "<i class='icon icon-cog'> </i> " . $lang->webhook->setting, '', "class='btn btn-primary iframe'", '', true);?>
        </div>
      </div>
    </div>
  </div>
  <table id='logList' class='table main-table table-fixed'>
    <thead>
      <tr>
        <?php $vars = "id={$webhook->id}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='c-id text-center'><?php common::printOrderLink('id', $orderBy, $vars, $lang->webhook->id);?></th>
        <th class='c-full-date'><?php common::printOrderLink('date', $orderBy, $vars, $lang->webhook->date);?></th>
        <th><?php common::printOrderLink('url', $orderBy, $vars, $lang->webhook->url);?></th>
        <th class='c-webhook-action'><?php common::printOrderLink('action', $orderBy, $vars, $lang->webhook->action);?></th>
        <th class='c-type'><?php common::printOrderLink('contentType', $orderBy, $vars, $lang->webhook->contentType);?></th>
        <th class='c-result'><?php common::printOrderLink('result', $orderBy, $vars, $lang->webhook->result);?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($logs as $id => $log):?>
      <tr>
        <td class='text-center'><?php echo $id;?></td>
        <td><?php echo $log->date;?></td>
        <td title='<?php echo $log->url;?>'><?php echo $log->url;?></td>
        <?php $iframe = zget($log, 'dialog', 0) == 1 ? 'data-toggle="modal" data-type="iframe"' : '';?>
        <?php if(zget($log, 'dialog', 0) == 1) $log->actionURL = $this->createLink($log->module, 'view', "id=$log->moduleID", '' , true)?>
        <?php if($log->action):?>
        <td title='<?php echo $log->action;?>'><?php echo html::a($log->actionURL, $log->action, '', $iframe);?></td>
        <?php else:?>
        <td title='<?php echo $lang->webhook->approval;?>'><?php echo $lang->webhook->approval;?></td>
        <?php endif;?>
        <td title='<?php echo $log->contentType;?>'><?php echo $log->contentType;?></td>
        <td title='<?php echo $log->result;?>'><?php echo $log->result;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if($logs):?>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
