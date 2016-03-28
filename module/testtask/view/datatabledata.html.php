<?php
/**
 * The datatable data view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/datatable.html.php';
$this->config->testcase->datatable->defaultField = $this->config->testtask->datatable->defaultField;
$this->config->testcase->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$this->config->testcase->datatable->fieldList['assignedTo']['fixed']    = 'no';
$this->config->testcase->datatable->fieldList['assignedTo']['width']    = '80';
$this->config->testcase->datatable->fieldList['assignedTo']['required'] = 'no';
$this->config->testcase->datatable->fieldList['actions']['width']       = '100';

$setting = $this->datatable->getSetting('testtask');
$widths  = $this->datatable->setFixedFieldWidth($setting);
extract($widths);
?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed datatable' id='caseList' data-checkable='<?php echo $hasCheckbox?>' data-fixed-left-width='<?php echo $leftWidth?>' data-fixed-right-width='<?php echo $rightWidth?>' data-custom-menu='true' data-checkbox-name='caseIDList[]'>
      <thead>
        <tr><?php foreach($setting as $key => $value) $this->datatable->printHead($value, $orderBy, $vars);?></tr>
      </thead>
      <tbody>
        <?php foreach($runs as $run):?>
        <tr class='text-center' data-id='<?php echo $run->case?>'>
          <?php foreach($setting as $key => $value) $this->testtask->printCell($value, $run, $users, $task, $branches);?>
        </tr>
        <?php endforeach;?>
      </tbody>
