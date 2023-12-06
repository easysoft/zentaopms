<?php
/**
 * The batch create view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou <zhouxin@cnezsoft.com>
 * @package     kanban
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header clearfix">
    <h2 class="pull-left">
      <?php echo $lang->kanban->batchCreateCard;?>
    </h2>
  </div>
  <form method='post' class='load-indicator batch-actions-form form-ajax' enctype='multipart/form-data' id="batchCreateCardForm">
    <div class="table-responsive">
      <table class="table table-form">
        <thead>
          <tr>
            <th class='c-id text-center'><?php echo $lang->idAB;?></th>
            <th class='c-name required'><?php echo $lang->kanbancard->name?></th>
            <th><?php echo $lang->kanbancard->lane;?></th>
            <th><?php echo $lang->kanbancard->assignedTo;?></th>
            <th class='c-estimate'><?php echo $lang->kanbancard->estimate;?></th>
            <th class='c-date'><?php echo $lang->kanbancard->begin;?></th>
            <th class='c-date'><?php echo $lang->kanbancard->end;?></th>
            <th><?php echo $lang->kanbancard->desc;?></th>
            <th class='c-pri w-120px'><?php echo $lang->kanbancard->pri;?></th>
          </tr>
        </thead>
        <tbody>
          <?php $pri = 3;?>
          <?php for($i = 0; $i < $config->kanban->batchCreate; $i++):?>
          <tr>
            <td class='text-center'><?php echo $i + 1;?></td>
            <td class='text-center'><?php echo html::input("name[$i]", '', "class='form-control title-import'");?></td>
            <?php if($i > 0) $lanePairs['ditto'] = $this->lang->kanbancard->ditto;?>
            <td style='overflow:visible'><?php echo html::select("lane[$i]", $lanePairs, $i > 0 ? 'ditto' : key($lanePairs), "class='form-control chosen'")?></td>
            <td style='overflow:visible'><?php echo html::select("assignedTo[$i][]", $users, $app->user->account, "class='form-control chosen' multiple");?></td>
            <td><?php echo html::input("estimate[$i]", '', "class='form-control text-center'");?></td>
            <td>
              <div class='input-group'>
                <?php
                echo html::input("begin[$i]", '', "class='form-control form-date' onkeyup='toggleCheck(this)'");
                if($i != 0) echo "<span class='input-group-addon estStartedBox'><input type='checkbox' name='beginDitto[$i]' id='beginDitto$i' " . ($i > 0 ? "checked" : '') . " /> {$lang->kanbancard->ditto}</span>";
                ?>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <?php
                echo html::input("end[$i]", '', "class='form-control form-date' onkeyup='toggleCheck(this)'");
                if($i != 0) echo "<span class='input-group-addon deadlineBox'><input type='checkbox' name='endDitto[$i]' id='endDitto$i' " . ($i > 0 ? "checked" : '') . " /> {$lang->kanbancard->ditto}</span>";
                ?>
              </div>
            </td>
            <td ><?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control autosize'");?></td>
            <td ><?php echo html::select("pri[$i]", (array)$lang->kanbancard->priList, $pri, 'class=form-control');?></td>
          </tr>
          <?php endfor;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='8' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
