<?php
/**
 * The editcard of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: editcard.html.php 4903 2021-12-13 14:25:59Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <div class='main-header'>
      <h2><?php echo $lang->kanbancard->edit;?></h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->kanbancard->name;?></div>
            <div class='form-group'>
              <div class="input-control has-icon-right">
                <?php echo html::input('name', $card->name, 'class="form-control"');?>
              </div>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->kanbancard->desc;?></div>
            <div class='form-group'>
              <?php echo html::textarea('desc', $card->desc, "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='detail text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </div>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->kanbancard->legendBasicInfo;?></div>
              <table class="table table-form">
                <tr>
                  <th><?php echo $lang->kanbancard->assignedTo;?></th>
                  <td><?php echo html::select('assignedTo[]', $kanbanUsers, $card->assignedTo, "class='form-control chosen' multiple");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->kanbancard->begin;?></th>
                  <td><?php echo html::input('begin', helper::isZeroDate($card->begin) ? '' : $card->begin, "class='form-control form-date'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->kanbancard->end;?></th>
                  <td><?php echo html::input('end', helper::isZeroDate($card->end) ? '' : $card->end, "class='form-control form-date'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->kanbancard->pri;?></th>
                  <td><?php echo html::select('pri', $lang->kanbancard->priList, $card->pri, "class='form-control'");?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->kanbancard->estimate;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('estimate', $card->estimate, "class='form-control'");?>
                      <span class='input-group-addon'>h</span>
                    </div>
                  </td>
                </tr>
                <?php if($kanban->performable):?>
                <tr>
                  <th><?php echo $lang->kanbancard->progress;?></th>
                  <td>
                    <div class='input-group'>
                      <?php echo html::input('progress', $card->progress, "class='form-control'");?>
                      <span class='input-group-addon'>%</span>
                    </div>
                  </td>
                </tr>
                <?php endif;?>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
