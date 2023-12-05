<?php
/**
 * The create view file of protect tag of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@easycorp.ltd>
 * @package     sonarqube
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->sonarqube->createProject;?></h2>
      </div>
      <form id='branchForm' method='post' class='form-ajax' enctype="multipart/form-data">
        <table class='table table-form'>
          <tr>
            <th class='w-110px'><?php echo $lang->sonarqube->projectName;?></th>
            <td><?php echo html::input('projectName', '', "class='form-control' placeholder='{$lang->sonarqube->placeholder->projectName}'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->sonarqube->projectKey;?></th>
            <td><?php echo html::input('projectKey', '', 'class="form-control" placeholder="' . $lang->sonarqube->placeholder->projectKey . '"');?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseProject', "sonarqubeID=$sonarqubeID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
