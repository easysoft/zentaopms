<?php
/**
 * The browse view file of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitea
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('gitea', 'browse'), "<span class='text'>{$lang->gitea->server}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gitea', 'create')) common::printLink('gitea', 'create', "", "<i class='icon icon-plus'></i> " . $lang->gitea->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($giteaList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('gitea', 'create')):?>
    <?php echo html::a($this->createLink('gitea', 'create'), "<i class='icon icon-plus'></i> " . $lang->gitea->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='giteaList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitea->id);?></th>
          <th class='c-name w-300px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitea->name);?></th>
          <th class='text'><?php common::printOrderLink('url', $orderBy, $vars, $lang->gitea->url);?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($giteaList as $id => $gitea): ?>
        <tr class='text'>
          <td class='text'><?php echo $id;?></td>
          <td class='text-c-name' title='<?php echo $gitea->name;?>'>
            <?php if(common::hasPriv('gitea', 'view')):?>
            <a class="iframe" href="<?php echo $this->createLink('gitea', 'view', "giteaID=$id", '', true); ?>"><?php echo $gitea->name;?></a>
            <?php else:?>
            <?php echo $gitea->name;?>
            <?php endif;?>
          </td>
          <td class='text' title='<?php echo $gitea->url;?>'><?php echo html::a($gitea->url, $gitea->url, '_target');?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('gitea', 'edit', "giteaID=$id", '', 'list', 'edit');
            echo common::buildIconButton('gitea', 'bindUser', "giteaID=$id", '', 'list', 'link', '', '', false, '', '', 0, $gitea->isBindUser);
            common::printIcon('gitea', 'delete', "giteaID=$id", '', 'list', 'trash', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($giteaList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
