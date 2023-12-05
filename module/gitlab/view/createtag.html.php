<?php
/**
 * The create view file of protext tag of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->createTag;?></h2>
      </div>
      <form id='tagForm' method='post' class='form-ajax' enctype="multipart/form-data">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->gitlab->tag->name;?></th>
            <td><?php echo html::input('tag_name', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->tag->ref;?></th>
            <td><?php echo html::select('ref', $branches, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->tag->message;?></th>
            <td><?php echo html::textarea('message', '', "rows='10' class='form-control'");?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseTag', "gitlabID=$gitlabID&projectID=$projectID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
