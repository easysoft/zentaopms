<?php
/**
 * The build view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->build->confirmDelete)?>
<?php if($config->global->flow == 'full'):?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <a class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->product->build;?></span> <span class='label label-light label-badge'><?php echo count($builds);?></span></a>
  </div>
  <div class='btn-toolbar pull-right'><?php common::printLink('build', 'create', "product=$product->id", "<i class='icon icon-plus'></i> " . $lang->build->create, '', "class='btn btn-primary'");?></div>
</div>
<?php endif;?>
<div id='mainContent' class='main-table'>
  <?php if(empty($builds)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->build->noBuild;?></span>
      <?php if(common::hasPriv('build', 'create')):?>
      <?php echo html::a($this->createLink('build', 'create', "productID=$product->id"), "<i class='icon icon-plus'></i> " . $lang->build->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class='table' id='buildList'>
    <thead>
      <tr class='text-center'>
        <th class='w-id'><?php echo $lang->build->id;?></th>
        <th><?php echo $lang->build->name;?></th>
        <th><?php echo $lang->build->scmPath;?></th>
        <th><?php echo $lang->build->filePath;?></th>
        <th class='c-date'><?php echo $lang->build->date;?></th>
        <th class='c-user'><?php echo $lang->build->builder;?></th>
        <th class='c-actions-3'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($builds as $build):?>
      <tr class='text-center'>
        <td><?php echo $build->id;?></td>
        <td class='text-left'><?php echo html::a($this->createLink('build', 'view', "build=$build->id"), $build->name);?></td>
        <td class='text-left' title="<?php echo $build->scmPath?>"><?php  echo strpos($build->scmPath,  'http') === 0 ? html::a($build->scmPath)  : $build->scmPath;?></td>
        <td class='text-left' title="<?php echo $build->filePath?>"><?php echo strpos($build->filePath, 'http') === 0 ? html::a($build->filePath) : $build->filePath;?></td>
        <td><?php echo $build->date?></td>
        <td><?php echo zget($users, $build->builder);?></td>
        <td class='c-actions'>
          <?php
          common::printIcon('testtask', 'create', "product=$product->id&project=0&build=$build->id", '', 'list', 'bullhorn');
          common::printIcon('build', 'edit', "buildID=$build->id", '', 'list');
          if(common::hasPriv('build', 'delete'))
          {
              $deleteURL = $this->createLink('build', 'delete', "buildID=$build->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"buildList\", confirmDelete)", '<i class="icon-close"></i>', '', "class='btn' title='{$lang->build->delete}'");
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
