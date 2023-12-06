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
  <?php echo $this->gitlab->getGitlabMenu($gitlabID, 'user');?>
  <div class="btn-toolbar pull-left">
    <form id='gitlabForm' method='post' class="not-watch">
      <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->gitlab->placeholderSearch}' style='display: inline-block;width:auto;margin:0 10px'");?>
      <a id="gitlabSearch" class="btn btn-primary"><?php echo $lang->gitlab->search?></a>
    </form>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if($isAdmin) common::printLink('gitlab', 'createUser', "gitlabID=$gitlabID", "<i class='icon icon-plus'></i> " . $lang->gitlab->user->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabUserList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if($isAdmin) echo html::a($this->createLink('gitlab', 'createProject', "gitlabID=$gitlabID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->user->create, '', "class='btn btn-info'");?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabUserList' class='table has-sort-head table-borderless'>
      <thead>
        <tr>
          <?php $vars = "gitlabID=$gitlabID&orderBy=%s";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitlab->user->id);?></th>
          <th class='c-name text w-60px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->user->name);?></th>
          <th class='c-name text w-400px'></th>
          <th class='text'><?php echo $lang->gitlab->user->createOn;?></th>
          <th class='text'><?php echo $lang->gitlab->user->lastActivity;?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabUserList as $id => $gitlabUser): ?>
        <tr>
          <td class='text'><?php echo $gitlabUser->id;?></td>
          <td class='w-60px'><?php echo html::image($gitlabUser->avatar, "height=40");?></td>
          <td class='text-left'>
              <strong><?php echo $gitlabUser->realname;?></strong>
              <?php echo $gitlabUser->account . " &lt;" . $gitlabUser->email . "&gt;";?>
          </td>
          <td class='text' title='<?php echo substr($gitlabUser->createdAt, 0, 10);?>'><?php echo substr($gitlabUser->createdAt, 0, 10);?></td>
          <td class='text' title='<?php echo substr($gitlabUser->lastActivityOn, 0, 10);?>'><?php echo substr($gitlabUser->lastActivityOn, 0, 10);?></td>
          <td class='c-actions text-left'>
            <?php
            echo common::buildIconButton('gitlab', 'editUser', "gitlabID=$gitlabID&userID=$gitlabUser->id", '', 'list', 'edit', '', '', false, '', '', 0, $isAdmin);
            echo common::buildIconButton('gitlab', 'deleteUser', "gitlabID=$gitlabID&userID=$gitlabUser->id", '', 'list', 'trash', 'hiddenwin', '', false, '', '', 0, $isAdmin);
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
