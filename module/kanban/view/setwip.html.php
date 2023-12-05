<?php
/**
 * The set WIP file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     kanban
 * @version     $Id: setwip.html.php 935 2021-10-25 10:56:24Z liyuchun@easycorp.ltd $
 * @link        https://www.zentao.net
 */
?>

<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo "<span title='$title'>" . $title . '</span>';?>
      </h2>
    </div>
    <form method='post' class="load-indicator main-form form-ajax" target='hiddenwin' onsubmit='return setWIPLimit();'>
      <table align='center' class='table table-form'>
        <?php if($column->parent != -1 and $from !='kanban'):?>
        <tr>
          <th><?php echo $lang->kanban->WIPStatus;?></th>
          <td colspan='2'>
            <?php echo html::input('WIPStatus', zget($lang->kanban->{$column->laneType . 'Column'}, $column->type, ''), "class='form-control' disabled");?>
          </td>
          <td></td>
        </tr>
        <?php if($column->laneType == 'story'):?>
        <tr>
          <th><?php echo $lang->kanban->WIPStage;?></th>
          <td colspan='2'>
            <?php $stage = zget($config->kanban->storyColumnStageList, $column->type);?>
            <?php echo html::input('WIPStage', zget($lang->story->stageList, $stage), "class='form-control' disabled");?>
          </td>
        </tr>
        <?php endif;?>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->kanban->WIPCount;?></th>
          <td colspan='2'>
            <div class="table-col">
              <?php $attr = $column->limit == -1 ? 'disabled' : '';?>
              <?php echo html::input('WIPCount', $column->limit != -1 ? $column->limit : '', "class='form-control' $attr");?>
            </div>
            <div class="table-col w-50px">
              <?php echo html::hidden('limit', $column->limit, "class='form-control'");?>
              <span class="input-group-addon" style="border: 1px solid #dcdcdc; border-left-width: 0px;">
                <div class='checkbox-primary'>
                  <input id='noLimit' name='noLimit' value='-1' type='checkbox' class='no-margin' <?php echo $column->limit == -1 ? 'checked' : '';?>/>
                  <label for='needNotReview'><?php echo $lang->kanban->noLimit;?></label>
                </div>
              </span>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
