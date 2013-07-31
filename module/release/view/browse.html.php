<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->release->confirmDelete)?>
<table align='center' class='table-6 tablesorter' id='releaseList'>
  <caption class='caption-tl'>
    <div class='f-left'><?php echo $lang->release->browse;?></div>
    <div class='f-right'><?php common::printIcon('release', 'create', "product=$product->id");?></div>
  </caption>
  <thead>
  <tr class='colhead'>
    <th class='w-id'><?php echo $lang->release->id;?></th>
    <th><?php echo $lang->release->name;?></th>
    <th><?php echo $lang->release->build;?></th>
    <th class='w-100px'><?php echo $lang->release->date;?></th>
    <th class='w-60px'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($releases as $release):?>
  <tr>
    <td class='a-center'><?php echo $release->id;?></td>
    <td><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></td>
    <td><?php echo $release->buildName;?></td>
    <td class='a-center'><?php echo $release->date;?></td>
    <td class='a-center'>
      <?php
      common::printIcon('release', 'edit',   "release=$release->id", '', 'list');
      $deleteURL = $this->createLink('release', 'delete', "releaseID=$release->id&confirm=yes");
      echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"releaseList\",confirmDelete)", '&nbsp;', '', "class='icon-green-common-delete' title='{$lang->release->delete}'");
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
