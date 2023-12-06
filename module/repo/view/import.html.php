<?php
/**
 * The import view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('repo', 'import'), "<span class='text'>{$lang->repo->importAction}</span>", '', "class='btn btn-link btn-active-text'");?>
    <div class='input-group w-400px'>
        <span class='input-group-addon'><?php echo $lang->repo->importServer?></span>
      <?php echo html::select("servers", $gitlabPairs, $gitlab->id, "class='form-control chosen' onchange='selectServer()'");?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <form class='main-table form-ajax' id='ajaxForm' method='post'>
    <?php echo html::hidden("serviceHost", $gitlab->id);?>
    <table id='repoList' class='table table-form'>
      <thead>
        <tr>
          <th class='w-300px'><?php echo $lang->repo->gitlabList;?></th>
          <th class='c-name w-300px required'><?php echo $lang->repo->importName;?></th>
          <th class='text-left required'><?php echo $lang->repo->product;?></th>
          <th class='text-left'><?php echo $lang->repo->projects;?></th>
          <th class='c-actions-3 text-center'></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($repoList as $key => $repo): ?>
        <tr class='text'>
          <td class='text-c-name' title='<?php echo $repo->name;?>'>
            <?php echo html::hidden("serviceProject{$key}", $repo->id);?>
            <?php echo $repo->name_with_namespace;?>
          </td>
          <td class='text-c-name'>
            <?php echo html::input("name{$key}", $repo->name, "class='form-control'");?>
          </td>
          <td><?php echo html::select("product{$key}[]", $products, '', "class='form-control chosen product' multiple");?></td>
          <td id='projectContainer<?php echo $key;?>'><?php echo html::select("projects[{$key}][]", $projects, '', "class='form-control chosen project' multiple");?></td>
          <td class='c-actions'><i onclick="delItem(this)" class="icon-close"></i></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
            <td colspan='5' class='text-center form-actions'>
              <?php echo html::submitButton($lang->repo->import); ?>
              <?php if(!isonlybody()):?>
                <?php if($this->app->tab == 'devops') echo html::a(inlink('maintain', ""), $lang->goback, '', 'class="btn btn-wide"');?>
              <?php endif;?>
            </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
