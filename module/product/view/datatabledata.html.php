<?php
/**
 * The datatable data view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/datatable.html.php';
$setting = $this->datatable->getSetting('product');
$widths  = $this->datatable->setFixedFieldWidth($setting);
extract($widths);
?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed datatable' id='storyList' data-checkable='true' data-fixed-left-width='<?php echo $leftWidth?>' data-fixed-right-width='<?php echo $rightWidth?>' data-custom-menu='true' data-checkbox-name='storyIDList[]'>
      <thead>
        <tr><?php foreach($setting as $key => $value) $this->datatable->printHead($value, $orderBy, $vars);?></tr>
      </thead>
      <tbody>
        <?php foreach($stories as $story):?>
        <tr class='text-center' data-id='<?php echo $story->id?>'>
          <?php foreach($setting as $key => $value) $this->story->printCell($value, $story, $users, $branches, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases);?>
        </tr>
        <?php endforeach;?>
      </tbody>
