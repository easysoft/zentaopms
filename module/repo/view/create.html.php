<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/base64.js');?>
<?php if(common::checkNotCN()):?>
<style>.user-addon {padding-right: 16px; padding-left: 16px;}</style>
<?php endif;?>
<?php js::set('scm',  'Git')?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->repo->createAction;?></h2>
      </div>
      <form id='repoForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <?php if($this->app->tab == 'project' or $this->app->tab == 'execution'):?>
          <?php echo html::hidden('product', implode(',', array_keys($products)));?>
          <?php else:?>
          <tr>
            <th><?php echo $lang->repo->product;?></th>
            <td class='required'><?php echo html::select('product[]', $products, empty($objectID) ? '' : array_keys($products), "class='form-control chosen' multiple");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->repo->projects;?></th>
            <td id='projectContainer'><?php echo html::select('projects[]', $projects, $relatedProjects, "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th class='thWidth'><?php echo $lang->repo->type;?></th>
            <td style="width:550px"><?php echo html::select('SCM', $lang->repo->scmList, 'Gitlab', "onchange='scmChanged(this.value)' class='form-control chosen'");?></td>
            <td class="tips-git tips"><?php echo $lang->repo->syncTips;?></td>
          </tr>
          <tr class='service hide'>
            <th><?php echo $lang->repo->serviceHost;?></th>
            <td class='required'><?php echo html::select('serviceHost', $serviceHosts, '', "class='form-control chosen'");?></td>
          </tr>
          <tr class='service hide'>
            <th><?php echo $lang->repo->serviceProject;?></th>
            <td class='required'><?php echo html::select('serviceProject', array(''), '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->name;?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr class='hide-service hide-git'>
            <th><?php echo $lang->repo->path;?></th>
            <td class='required'><?php echo html::input('path', '', "class='form-control'");?></td>
            <td class='muted'>
                <span class="tips-git"><?php echo $lang->repo->example->path->git;?></span>
                <span class="tips-svn"><?php echo $lang->repo->example->path->svn;?></span>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->encoding; ?></th>
            <td class='required'><?php echo html::input('encoding', 'utf-8', "class='form-control'");?></td>
            <td class='muted'><?php echo $lang->repo->encodingsTips;?></td>
          </tr>
          <tr class='hide-service'>
            <th><?php echo $lang->repo->client;?></th>
            <td class='required'><?php echo html::input('client', '', "class='form-control'");?></td>
            <td class='muted'>
                <span class="tips-git"><?php echo $lang->repo->example->client->git;?></span>
                <span class="tips-svn"><?php echo $lang->repo->example->client->svn;?></span>
            </td>
          </tr>
          <tr class="account-fields hide-service">
            <th><?php echo $lang->repo->account;?></th>
            <td><?php echo html::input('account', '', "class='form-control'");?></td>
          </tr>
          <tr class="account-fields hide-service">
            <th><?php echo $lang->repo->password;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::password('password', '', "class='form-control'");?>
                <?php echo html::select('encrypt', $lang->repo->encryptList, 'base64', "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->acl;?></th>
            <td class='acl'>
              <div class='input-group mgb-10'>
                <span class='input-group-addon'><?php echo $lang->repo->group?></span>
                <?php echo html::select('acl[groups][]', $groups, '', "class='form-control picker-select' multiple");?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon user-addon'><?php echo $lang->repo->user;?></span>
                <?php echo html::select('acl[users][]', $users, '', "class='form-control picker-select' multiple");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->repo->desc; ?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "rows='3' class='form-control'"); ?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php if(!isonlybody()):?>
                <?php if($this->app->tab == 'devops') echo html::a(inlink('maintain', ""), $lang->goback, '', 'class="btn btn-wide"');?>
                <?php if($this->app->tab == 'project') echo html::a("javascript: parent.window.history.back();", $lang->goback, '', 'class="btn btn-wide"');?>
              <?php endif;?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
