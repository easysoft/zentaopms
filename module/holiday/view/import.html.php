<?php
/**
 * The browse view file of holiday module of ZenTao.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     holiday
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
#main > .container {padding: 0;}
.body-modal #mainContent {padding: 0;}
#mainContent .table {margin-bottom: 0;}
#mainContent .table thead th {padding-top: 20px;}
#mainContent .table tr {height: 40px;}
#mainContent .table .c-name {padding-left: 40px;}
#mainContent .table .c-time-limit {padding-right: 40px;}
.body-modal .table-footer {margin-top: 16px;}
#mainContent .table-footer .btn-cancel {margin-left: 16px;}
.body-modal .table-footer .btn {padding: 6px 12px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block main-table'>
    <?php if(!empty($holidays)):?>
    <form class='form-ajax' method='post'>
      <table class='table table-fixed'>
        <thead>
          <tr>
            <th class="c-name"><?php echo $lang->holiday->name;?></th>
            <th class="c-time-limit"><?php echo $lang->holiday->holiday;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($holidays as $holiday):?>
          <tr>
            <td class="c-name"><?php echo $holiday->name;?></td>
            <td class="c-time-limit"><?php echo formatTime($holiday->begin, DT_DATE1) . ' ~ ' . formatTime($holiday->end, DT_DATE1);?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class="table-footer text-center">
        <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
        <?php echo html::commonButton($lang->cancel, 'data-dismiss="modal"', 'btn btn-cancel');?>
    </div>
    </form>
    <?php else:?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->holiday->emptyTip;?></span>
      </p>
    </div>
    <?php endif;?>
  </div>
</div>

<?php if(!empty($holidays)):?>
<script>
$(function(){parent.$('#triggerModal .modal-content .modal-header .close').hide();});
</script>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
