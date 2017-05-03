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
<div>
  <div id='titlebar'>
    <div class='heading'>
      <i class='icon-tags'></i>
      <?php echo $lang->release->browse;?>
      <?php if($product->type !== 'normal') echo '<span class="label label-info">' . $branches[$branch] . '</span>';?>
    </div>
    <div class='actions'>
      <?php common::printIcon('release', 'create', "product=$product->id&branch=$branch");?>
    </div>
  </div>
  <table class='table tablesorter' id='releaseList'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->release->id;?></th>
      <th><?php echo $lang->release->name;?></th>
      <th><?php echo $lang->release->build;?></th>
      <?php if($product->type != 'normal'):?>
      <th class='w-100px'><?php echo $lang->product->branch;?></th>
      <?php endif;?>
      <th class='w-100px'><?php echo $lang->release->date;?></th>
      <th class='w-100px'><?php echo $lang->release->status;?></th>
      <th class='w-150px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($releases as $release):?>
    <tr>
      <td class='text-center'><?php echo $release->id;?></td>
      <td><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></td>
      <td title='<?php echo $release->buildName?>'><?php echo html::a($this->createLink('build', 'view', "buildID=$release->buildID"), $release->buildName, '_blank');?></td>
      <?php if($product->type != 'normal'):?>
      <td class='text-center'><?php echo $branches[$release->branch];?></td>
      <?php endif;?>
      <td class='text-center'><?php echo $release->date;?></td>
      <td class='text-center'><?php echo $lang->release->statusList[$release->status];?></td>
      <td class='text-center'>
        <?php
        if(common::hasPriv('release', 'linkStory')) echo html::a(inlink('view', "releaseID=$release->id&type=story&link=true"), '<i class="icon-link"></i> ', '', "class='btn-icon' title='{$lang->release->linkStory}'");
        if(common::hasPriv('release', 'linkBug') and $this->config->global->flow != 'onlyStory') echo html::a(inlink('view', "releaseID=$release->id&type=bug&link=true"),   '<i class="icon-bug"></i> ',  '', "class='btn-icon' title='{$lang->release->linkBug}'");
        if(common::hasPriv('release', 'changeStatus'))
        {
            $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';
            echo html::a(inlink('changeStatus', "releaseID=$release->id&status=$changedStatus"),   '<i class="icon-' . ($release->status == 'normal' ? 'pause' : 'play') . '"></i> ',  'hiddenwin', "class='btn-icon' title='{$lang->release->changeStatusList[$changedStatus]}'");
        }
        common::printIcon('release', 'edit',   "release=$release->id", '', 'list');
        if(common::hasPriv('release', 'delete'))
        {
            $deleteURL = $this->createLink('release', 'delete', "releaseID=$release->id&confirm=yes");
            echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"releaseList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->release->delete}'");
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>

<?php include '../../common/view/footer.html.php';?>
