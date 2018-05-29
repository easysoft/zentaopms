<?php
/**
 * The link bug view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     build
 * @version     $Id: linkbug.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='queryBox' class='show'></div>
<div id='unlinkBugList'>
  <form class='main-table table-bug' data-ride='table' method='post' id='unlinkedBugsForm' target='hiddenwin' action='<?php echo $this->createLink('build', 'linkBug', "buildID={$build->id}&browseType=$browseType&param=$param");?>'>
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?>  <?php echo $lang->productplan->unlinkedBugs;?>
    </div>
    <table class='table'> 
      <thead>
        <tr class='text-center'>
          <th class='c-id text-left'>
            <?php if($allBugs):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='w-pri'>  <?php echo $lang->priAB;?></th>
          <th class='text-left'><?php echo $lang->bug->title;?></th>
          <th class='w-user'> <?php echo $lang->openedByAB;?></th>
          <th class='w-150px'><?php echo $lang->bug->resolvedBy;?></th>
          <th class='w-80px'> <?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody class='text-center'>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allBugs as $bug):?>
        <?php if(strpos(",{$build->bugs},", ",$bug->id,") !== false) continue;?>
        <?php if($build->product != $bug->product) continue; ?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('bugs', array($bug->id => sprintf('%03d', $bug->id)), ($bug->status == 'resolved' or $bug->status == 'closed') ? $bug->id : '');?>
          </td>
          <td><span class='label-pri label-pri-<?php echo $bug->pri;?>' title='<?php echo zget($lang->bug->priList, $bug->pri, $bug->pri);?>'><?php echo zget($lang->bug->priList, $bug->pri, $bug->pri)?></span></td>
          <td class='text-left nobr' title='<?php echo $bug->title?>'><?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id", '', true), $bug->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
          <td><?php echo $users[$bug->openedBy];?></td>
          <td style='overflow:visible;padding-top:1px;padding-bottom:1px;'><?php echo ($bug->status == 'resolved' or $bug->status == 'closed') ? $users[$bug->resolvedBy] : html::select("resolvedBy[{$bug->id}]", $users, $this->app->user->account, "class='form-control chosen'");?></td>
          <td>
            <span class='status-<?php echo $bug->status?>'>
              <span class='label label-dot'></span>
              <?php echo $lang->bug->statusList[$bug->status];?>
            </span>
          </td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($unlinkedCount):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar show-always">
        <?php echo html::submitButton($lang->build->linkBug, '', 'btn');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('view', "buildID={$build->id}&type=bug"), $lang->goback, '', "class='btn'");?>
      </div>
      <div class='table-statistic'></div>
    </div>
    <?php endif;?>
  </form>
</div>