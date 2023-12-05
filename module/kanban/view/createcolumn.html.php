<?php
/**
 * The create lane view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('colorList',$config->kanban->columnColorList);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->createColumn;?></h2>
    </div>
    <form id='createColumnForm' method='post' class='form-ajax' action='<?php echo inlink('createColumn', "columnID=$column->id&position=$position");?>' onsubmit='return setWIPLimit();'>
      <table class='table table-form'>
        <tr>
          <th class="w-120px"><?php echo $lang->kanbancolumn->name;?></th>
          <td>
            <div class='required required-wrapper'></div>
            <?php echo html::input('name', '', "class='form-control'");?>
          </td>
        </tr>
        <tr class='child-column'>
          <th><?php echo $lang->kanban->WIPCount;?></th>
          <td>
            <div class='required required-wrapper'></div>
            <div class='input-group'>
              <?php echo html::input('WIPCount', '', "class='form-control' disabled");?>
              <?php echo html::hidden('limit', -1, "class='form-control'");?>
              <span class='input-group-addon'>
                <label class='checkbox-inline'>
                  <input type='checkbox' name='noLimit' id='noLimit' value='-1' checked/> <?php echo $this->lang->kanban->noLimit;?>
                </label>
              </span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->kanbancolumn->color;?></th>
          <td>
            <div id='color-picker'></div>
            <?php echo html::input('color', '#333', "class='hidden'");?>
          </td>
        </tr>
        <tr>
          <td colspan='2' class='form-actions text-center'>
            <?php echo html::hidden('group', $column->group);?>
            <?php echo html::hidden('parent', $column->parent > 0 ? $column->parent : 0);?>
            <?php echo html::submitButton();?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
