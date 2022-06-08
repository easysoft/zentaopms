<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gitlab', 'create')) common::printLink('gitlab', 'create', "", "<i class='icon icon-plus'></i> " . $lang->gitlab->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('gitlab', 'create')):?>
    <?php echo html::a($this->createLink('gitlab', 'create'), "<i class='icon icon-plus'></i> " . $lang->gitlab->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id text-center'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitlab->id);?></th>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->name);?></th>
          <th class='text-left'><?php common::printOrderLink('url', $orderBy, $vars, $lang->gitlab->url);?></th>
          <th class='c-actions-6'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabList as $id => $gitlab): ?>
        <tr class='text' title='<?php if(!$gitlab->isAdminToken) echo $lang->gitlab->tokenLimit;?>'>
          <td class='text-center'><?php echo $id;?></td>
          <td class='text-c-name' title='<?php echo $gitlab->name;?>'>
            <?php if(common::hasPriv('gitlab', 'view')):?>
            <a class="iframe" href="<?php echo $this->createLink('gitlab', 'view', "id=$id", '', true); ?>"><?php echo $gitlab->name;?></a>
            <?php else:?>
            <?php echo $gitlab->name;?>
            <?php endif;?>
          </td>
          <td class='text' title='<?php echo $gitlab->url;?>'><?php echo html::a($gitlab->url, $gitlab->url, '_target');?></td>
          <td class='c-actions text-left'>
            <?php
            $disabled = !empty($gitlab->isAdminToken) ? '' : 'disabled';
            $notBind  = $gitlab->isBindUser ? '' : 'disabled';
            common::printLink('gitlab', 'browseProject', "gitlabID=$id", "<i class='icon icon-list-box'></i> ", '',"title='{$lang->gitlab->browseProject}' class='btn {$notBind}'");
            common::printLink('gitlab', 'browseGroup', "gitlabID=$id", "<i class='icon icon-groups'></i> ", '', "title='{$lang->gitlab->browseGroup}'  class='btn {$notBind}'");
            common::printLink('gitlab', 'edit', "gitlabID=$id", "<i class='icon icon-edit'></i> ", '',"title='{$lang->gitlab->edit}' class='btn btn-primary'");
            common::printLink('gitlab', 'browseUser', "gitlabID=$id", "<i class='icon icon-person'></i> ", '', "title='{$lang->gitlab->browseUser}'  class='btn {$disabled} {$notBind}' ,'disabled'");
            common::printLink('gitlab', 'bindUser', "id=$id", "<i class='icon icon-link'></i> ", '', "title='{$lang->gitlab->bindUser}'  class='btn {$disabled} {$notBind}' ,'disabled'");
            if(common::hasPriv('gitlab', 'delete')) echo html::a($this->createLink('gitlab', 'delete', "gitlabID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->gitlab->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($gitlabList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
