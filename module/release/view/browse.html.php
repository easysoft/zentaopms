<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
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
    <div class='heading'><i class='icon-tags'></i> <?php echo $lang->release->browse;?>  </div>
    <div class='actions'>
      <?php common::printIcon('release', 'create', "product=$product->id");?>
    </div>
  </div>
  <table class='table tablesorter' id='releaseList'>
    <thead>
    <tr class='colhead'>
      <th class='w-id'><?php echo $lang->release->id;?></th>
      <th><?php echo $lang->release->name;?></th>
      <th><?php echo $lang->release->build;?></th>
      <th class='w-100px'><?php echo $lang->release->date;?></th>
      <th class='w-100px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($releases as $release):?>
    <tr>
      <td class='text-center'><?php echo $release->id;?></td>
      <td><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></td>
      <td><?php echo $release->buildName;?></td>
      <td class='text-center'><?php echo $release->date;?></td>
      <td class='text-center'>
        <?php
        common::printIcon('release', 'linkStory', "releaseID=$release->id", '', 'list', 'link');
        common::printIcon('release', 'linkBug', "releaseID=$release->id", '', 'list', 'bug');
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
