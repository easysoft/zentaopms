<?php
/**
 * The create card view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@cnezsoft.com>
 * @package     kanban
 * @version     $Id: createcard.html.php 5090 2021-12-13 13:49:24Z tainshujie@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanbancard->create;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->kanbancard->name;?></th>
          <td colspan='2'>
            <div class='input-group title-group'>
              <?php echo html::input('name', '', "class='form-control required'");?>
              <span class="input-group-addon fix-border br-0"><?php echo $lang->kanbancard->pri;?></span>
              <div class="input-group-btn pri-selector" data-type="pri">
                <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                  <span class="pri-text"><span class="label-pri label-pri-3" title="3">3</span></span> &nbsp;<span class="caret"></span>
                </button>
                <div class='dropdown-menu pull-right'>
                <?php echo html::select('pri', $lang->kanbancard->priList, 3, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                </div>
              </div>
              <div class='table-col w-120px'>
                <div class="input-group">
                  <span class="input-group-addon fix-border br-0"><?php echo $lang->kanbancard->estimate;?></span>
                  <?php echo html::input('estimate', '', "class='form-control' placeholder='{$lang->kanbancard->lblHour}' autocomplete='off'");?>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbanlane->common;?></th>
          <td colspan='2'>
            <?php echo html::select('lane', $lanePairs, key($lanePairs), "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->assignedTo;?></th>
          <td colspan='2'>
            <?php echo html::select('assignedTo[]', $users, $app->user->account, "class='form-control chosen' multiple");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->beginAndEnd;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::input('begin', '', "class='form-control form-date' placeholder='{$lang->kanbancard->begin}'");?>
              <span class='input-group-addon fix-border'>~</span>
              <?php echo html::input('end', '', "class='form-control form-date' placeholder='{$lang->kanbancard->end}'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancard->desc;?></th>
          <td colspan='3'><?php echo html::textarea('desc', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
$('#pri').on('change', function()
{
    var $select = $(this);
    var $selector = $select.closest('.pri-selector');
    var value = $select.val();
    $selector.find('.pri-text').html('<span class="label-pri label-pri-' + value + '" title="' + value + '">' + value + '</span>');
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
