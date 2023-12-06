<?php
/**
 * The feedback view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<?php $viewMethod = 'view'?>
<style>
.table-form>tbody>tr>th {width:135px;}
.c-solution {white-space: nowrap; overflow: hidden;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->my->featureBar[$app->rawMethod]['feedback'] as $key => $name)
    {
        $label  = "<span class='text'>{$name}</span>";
        $label .= $key == $browseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active = $key == $browseType ? 'btn-active-text' : '';
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=$key"), $label, '', "class='btn btn-link $active'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->user->search;?></a>
  </div>
</div>
<div id="mainContent">
  <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='workFeedback'></div>
  <?php if(empty($feedbacks)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->feedback->noFeedback;?></span></p>
  </div>
  <?php else:?>
  <?php
  include '../../../extension/biz/feedback/view/data.html.php';
  ?>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
