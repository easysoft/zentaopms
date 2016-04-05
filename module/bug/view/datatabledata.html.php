<?php
/**
 * The datatable data view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/datatable.html.php';
$setting = $this->datatable->getSetting('bug');
$widths  = $this->datatable->setFixedFieldWidth($setting);
extract($widths);
?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed datatable' id='bugList' data-checkable='true' data-fixed-left-width='<?php echo $leftWidth?>' data-fixed-right-width='<?php echo $rightWidth?>' data-custom-menu='true' data-checkbox-name='bugIDList[]'>
      <thead>
        <tr><?php foreach ($setting as $key => $value) $this->datatable->printHead($value, $orderBy, $vars);?></tr>
      </thead>
      <tbody>
        <?php foreach($bugs as $bug):?>
        <tr class='text-center' data-id='<?php echo $bug->id?>'>
          <?php foreach ($setting as $key => $value) $this->bug->printCell($value, $bug, $users, $builds, $branches, $modulePairs);?>
        </tr>
        <?php endforeach;?>
      </tbody>
