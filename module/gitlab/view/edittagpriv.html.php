<?php
/**
 * The edit view file of protect tag of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     gitlab
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->gitlab->editTagPriv;?></h2>
      </div>
      <form id='branchForm' method='post' class='form-ajax' enctype="multipart/form-data">
        <table class='table table-form'>
          <tr>
            <th class='w-110px'><?php echo $lang->gitlab->tag->name;?></th>
            <td><?php echo html::input('tagName', $tag, "class='form-control' disabled");?></td>
            <td></td>
            <?php echo html::hidden('name', $tag);?>
          </tr>
          <tr>
            <th><?php echo $lang->gitlab->tag->accessLevel;?></th>
            <td><?php echo html::select('create_access_level', $lang->gitlab->branch->branchCreationLevelList, $tagPriv->createAccessLevel, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browseTagPriv', "gitlabID=$gitlabID&projectID=$projectID"), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
