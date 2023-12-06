<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('gitlab', 'browse'), "<span class='text'>{$lang->gitlab->server}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
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
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitlab->id);?></th>
          <th class='c-name w-300px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->name);?></th>
          <th class='text-left'><?php common::printOrderLink('url', $orderBy, $vars, $lang->gitlab->url);?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabList as $id => $gitlab): ?>
        <tr class='text'>
          <td class='text'><?php echo $id;?></td>
          <td class='text-c-name' title='<?php echo $gitlab->name;?>'>
            <?php if(common::hasPriv('gitlab', 'browseProject')):?>
            <a href="<?php echo $this->createLink('gitlab', 'browseProject', "gitlabID=$id"); ?>"><?php echo $gitlab->name;?></a>
            <?php else:?>
            <?php echo $gitlab->name;?>
            <?php endif;?>
          </td>
          <td class='text' title='<?php echo $gitlab->url;?>'><?php echo html::a($gitlab->url, $gitlab->url, '_target');?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('gitlab', 'edit', "gitlabID=$id", '', 'list', 'edit');
            echo common::buildIconButton('gitlab', 'bindUser', "gitlabID=$id", '', 'list', 'lock');
            common::printIcon('gitlab', 'delete', "gitlabID=$id", '', 'list', 'trash', 'hiddenwin');
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
