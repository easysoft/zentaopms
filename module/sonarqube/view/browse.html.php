<?php
/**
 * The browse view file of sonarqube module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('sonarqube', 'browse'), "<span class='text'>{$lang->sonarqube->serverList}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('sonarqube', 'create')) common::printLink('sonarqube', 'create', "", "<i class='icon icon-plus'></i> " . $lang->sonarqube->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($sonarqubeList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('sonarqube', 'create')):?>
    <?php echo html::a($this->createLink('sonarqube', 'create'), "<i class='icon icon-plus'></i> " . $lang->sonarqube->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='sonarqubeList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->sonarqube->id);?></th>
          <th class='c-name w-300px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->sonarqube->name);?></th>
          <th class='text'><?php common::printOrderLink('url', $orderBy, $vars, $lang->sonarqube->url);?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($sonarqubeList as $id => $sonarqube): ?>
        <tr class='text'>
          <td class='text'><?php echo $id;?></td>
          <td class='text-c-name' title='<?php echo $sonarqube->name;?>'><?php echo $sonarqube->name;?></td>
          <td class='text' title='<?php echo $sonarqube->url;?>'><?php echo html::a($sonarqube->url, $sonarqube->url, '_blank');?></td>
          <td class='c-actions'>
            <?php
            common::printLink('sonarqube', 'browseProject', "sonarqubeID=$id", "<i class='icon icon-list-box'></i> ", '',"title='{$lang->sonarqube->browseProject}' class='btn btn-primary'");
            common::printLink('sonarqube', 'edit', "sonarqubeID=$id", "<i class='icon icon-edit'></i> ", '',"title='{$lang->sonarqube->edit}' class='btn btn-primary'");
            if(common::hasPriv('sonarqube', 'delete')) echo html::a($this->createLink('sonarqube', 'delete', "sonarqubeID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->sonarqube->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($sonarqubeList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
