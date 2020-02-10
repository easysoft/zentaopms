<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->release->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    echo html::a($this->inlink('browse', "productID={$product->id}&branch=$branch&type=all"), "<span class='text'>{$lang->release->all}</span>" . ($type == 'all' ? ' <span class="label label-light label-badge">' . count($releases) . '</span>' : ''), '', "id='allTab' class='btn btn-link" . ('all' == $type ? ' btn-active-text' : '') . "'");
    echo html::a($this->inlink('browse', "productID={$product->id}&branch=$branch&type=normal"), "<span class='text'>{$lang->release->statusList['normal']}</span>" . ($type == 'normal' ? ' <span class="label label-light label-badge">' . count($releases) . '</span>' : ''), '', "id='normalTab' class='btn btn-link" . ('normal' == $type ? ' btn-active-text' : '') . "'");
    echo html::a($this->inlink('browse', "productID={$product->id}&branch=$branch&type=terminate"), "<span class='text'>{$lang->release->statusList['terminate']}</span>" . ($type == 'terminate' ? ' <span class="label label-light label-badge">' . count($releases) . '</span>' : ''), '', "id='terminateTab' class='btn btn-link" . ('terminate' == $type ? ' btn-active-text' : '') . "'");
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('release', 'create', "productID=$product->id&branch=$branch", "<i class='icon icon-plus'></i> {$lang->release->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($releases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->release->noRelease;?></span>
      <?php if(common::hasPriv('release', 'create')):?>
      <?php echo html::a($this->createLink('release', 'create', "productID=$product->id&branch=$branch"), "<i class='icon icon-plus'></i> " . $lang->release->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table" id='releaseList'>
    <thead>
      <tr>
        <th class='w-id'><?php echo $lang->release->id;?></th>
        <th><?php echo $lang->release->name;?></th>
        <th><?php echo $lang->release->build;?></th>
        <?php if($product->type != 'normal'):?>
        <th class='text-center w-100px'><?php echo $lang->product->branch;?></th>
        <?php endif;?>
        <th class='c-date text-center'><?php echo $lang->release->date;?></th>
        <th class='text-center w-90px'><?php echo $lang->release->status;?></th>
        <th class='c-actions-5 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($releases as $release):?>
      <tr>
        <td><?php echo html::a(helper::createLink('release', 'view', "releaseID=$release->id"), sprintf('%03d', $release->id));?></td>
        <td>
          <?php 
          $flagIcon = $release->marker ? "<icon class='icon icon-flag-alt' title='{$lang->release->marker}'></icon> " : '';
          echo html::a(inlink('view', "release=$release->id"), $release->name) . $flagIcon;
          ?>
        </td>
        <td title='<?php echo $release->buildName?>'><?php echo empty($release->project) ? $release->buildName : html::a($this->createLink('build', 'view', "buildID=$release->buildID"), $release->buildName);?></td>
        <?php if($product->type != 'normal'):?>
        <td class='text-center' title='<?php echo zget($branches, $release->branch, '');?>'><?php echo $branches[$release->branch];?></td>
        <?php endif;?>
        <td class='text-center'><?php echo $release->date;?></td>
        <?php $status = $this->processStatus('release', $release);?>
        <td class='c-status text-center' title='<?php echo $status;?>'>
          <span class="status-release status-<?php echo $release->status?>"><?php echo $status;?></span>
        </td>
        <td class='c-actions'>
          <?php
          if(common::hasPriv('release', 'linkStory')) echo html::a(inlink('view', "releaseID=$release->id&type=story&link=true"), '<i class="icon-link"></i> ', '', "class='btn' title='{$lang->release->linkStory}'");
          if(common::hasPriv('release', 'linkBug') and $config->global->flow != 'onlyStory') echo html::a(inlink('view', "releaseID=$release->id&type=bug&link=true"), '<i class="icon-bug"></i> ', '', "class='btn' title='{$lang->release->linkBug}'");
          if(common::hasPriv('release', 'changeStatus', $release))
          {
              $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';
              echo html::a(inlink('changeStatus', "releaseID=$release->id&status=$changedStatus"), '<i class="icon-' . ($release->status == 'normal' ? 'pause' : 'play') . '"></i> ', 'hiddenwin', "class='btn' title='{$lang->release->changeStatusList[$changedStatus]}'");
          }
          common::printIcon('release', 'edit',   "release=$release->id", $release, 'list');
          if(common::hasPriv('release', 'delete', $release))
          {
              $deleteURL = $this->createLink('release', 'delete', "releaseID=$release->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"releaseList\", confirmDelete)", '<i class="icon-trash"></i>', '', "class='btn' title='{$lang->release->delete}'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
