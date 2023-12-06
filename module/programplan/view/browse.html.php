<?php
/**
 * The browse of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: browse.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>#dropMenu{z-index:99999;}</style>
<?php js::set('browseType', $type);?>
<div class='main-table'>
  <?php if(empty($stages)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->programplan->noData;?></span>
      <?php if(common::hasPriv('programplan', 'create')):?>
      <?php echo html::a($this->createLink('programplan', 'create', "projectID=$projectID&productID=$productID"), "<i class='icon icon-plus'></i> " . $lang->programplan->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
    <?php if($type == 'gantt' or $type == 'assignedTo') include './gantt.html.php';?>
    <?php if($type == 'lists') include './list.html.php';?>
  <?php endif;?>
</div>
<script>
$('#subNavbar').find('ul li').each(function()
{
    var that = $(this);
    if(that.attr('data-id') != browseType) that.removeClass('active');
    if(that.attr('data-id') == 'gantt' && 'assignedTo' == browseType) that.addClass('active');
});
</script>
<?php include '../../common/view/footer.html.php';?>
