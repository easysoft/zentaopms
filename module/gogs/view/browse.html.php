<?php
/**
 * The browse view file of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gogs
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('gogs', 'browse'), "<span class='text'>{$lang->gogs->server}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gogs', 'create')) common::printLink('gogs', 'create', "", "<i class='icon icon-plus'></i> " . $lang->gogs->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gogsList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('gogs', 'create')):?>
    <?php echo html::a($this->createLink('gogs', 'create'), "<i class='icon icon-plus'></i> " . $lang->gogs->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gogsList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gogs->id);?></th>
          <th class='c-name w-300px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gogs->name);?></th>
          <th class='text'><?php common::printOrderLink('url', $orderBy, $vars, $lang->gogs->url);?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gogsList as $id => $gogs): ?>
        <tr class='text'>
          <td class='text'><?php echo $id;?></td>
          <td class='text-c-name' title='<?php echo $gogs->name;?>'>
            <?php if(common::hasPriv('gogs', 'view')):?>
            <a class="iframe" href="<?php echo $this->createLink('gogs', 'view', "gogsID=$id", '', true); ?>"><?php echo $gogs->name;?></a>
            <?php else:?>
            <?php echo $gogs->name;?>
            <?php endif;?>
          </td>
          <td class='text' title='<?php echo $gogs->url;?>'><?php echo html::a($gogs->url, $gogs->url, '_target');?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('gogs', 'edit', "gogsID=$id", '', 'list', 'edit');
            echo common::buildIconButton('gogs', 'bindUser', "gogsID=$id", '', 'list', 'link', '', '', false, '', '', 0, $gogs->isBindUser);
            common::printIcon('gogs', 'delete', "gogsID=$id", '', 'list', 'trash', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($gogsList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
