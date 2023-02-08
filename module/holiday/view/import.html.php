<?php
/**
 * The browse view file of holiday module of ZenTao.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     holiday
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
.body-modal #mainContent {padding-top: 28px;}
#mainContent td.text-center {border: none;}
#mainContent td.text-center .btn-cancel {margin-left: 20px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <?php if(!empty($holidays)):?>
    <form class='form-ajax' method='post'>
      <table class='table'>
        <thead>
          <tr>
            <th><?php echo $lang->holiday->name;?></th>
            <th><?php echo $lang->holiday->holiday;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($holidays as $holiday):?>
          <tr>
            <td><?php echo $holiday->name;?></td>
            <td><?php echo formatTime($holiday->begin, DT_DATE1) . ' ~ ' . formatTime($holiday->end, DT_DATE1);?></td>
          </tr>
          <?php endforeach;?>
        <tr>
          <td colspan='2' class='text-center'>
            <?php echo html::submitButton($lang->import);?>
            <?php echo html::commonButton($lang->cancel, 'data-dismiss="modal"', 'btn btn-wide btn-cancel');?>
          </td>
        </tr>
        </tbody>
      </table>
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
<?php include '../../common/view/footer.html.php';?>
