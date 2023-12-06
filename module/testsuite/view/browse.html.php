<?php
/**
 * The browse view file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->testsuite->confirmDelete)?>
<?php js::set('flow', $config->global->flow);?>
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php
    common::sortFeatureMenu();
    foreach($lang->testsuite->featureBar['browse'] as $featureType => $label)
    {
        $activeClass = $type == $featureType ? 'btn-active-text' : '';
        $label       = "<span class='text'>$label</span>";
        if($type == $featureType) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        echo html::a(inlink('browse', "productID=$productID&type=$featureType"), $label, '',"class='btn btn-link $activeClass'");
    }
    ?>
  </div>
  <?php if(common::canModify('product', $product)):?>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('testsuite', 'create', "product=$productID", "<i class='icon icon-plus'></i> " . $lang->testsuite->create, '', "class='btn btn-primary'");?>
  </div>
  <?php endif;?>
</div>
<div id='mainContent' class='main-table' data-ride='table'>
  <?php if(empty($suites)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testsuite->noTestsuite;?></span>
      <?php if(common::canModify('product', $product) and common::hasPriv('testsuite', 'create')):?>
      <?php echo html::a($this->createLink('testsuite', 'create', "product=$productID"), "<i class='icon icon-plus'></i> " . $lang->testsuite->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head' id='suiteList'>
    <thead>
    <?php $vars = "productID=$productID&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='c-id text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
        <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->testsuite->name);?></th>
        <th><?php echo $lang->testsuite->desc;?></th>
        <th class='c-user'><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->testsuite->addedBy);?></th>
        <th class='c-full-date'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->testsuite->addedDate);?></th>
        <?php
        $extendFields = $this->testsuite->getFlowExtendFields();
        foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
        ?>
        <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($suites as $suite):?>
    <tr class='text-left'>
      <td><?php echo html::a(helper::createLink('testsuite', 'view', "suiteID=$suite->id"), sprintf('%03d', $suite->id));?></td>
      <td class='text-left c-name' title="<?php echo $suite->name?>">
        <?php if($suite->type == 'public') echo "<span class='label label-success label-badge'>{$lang->testsuite->authorList['public']}</span> ";?>
        <?php if($suite->type == 'private') echo "<span class='label label-info label-badge'>{$lang->testsuite->authorList['private']}</span> ";?>
        <?php echo html::a(inlink('view', "suiteID=$suite->id"), $suite->name);?>
      </td>
      <td class='c-desc'>
        <?php $desc = trim(strip_tags(str_replace(array('</p>', '<br />', '<br>', '<br/>'), "\n", str_replace(array("\n", "\r"), '', $suite->desc)), '<img>'));?>
        <div title='<?php echo $desc;?>'><?php echo nl2br($desc);?></div>
      </td>
      <td><?php echo zget($users, $suite->addedBy);?></td>
      <td><?php echo $suite->addedDate ? substr($suite->addedDate, 0, 10) : '';?></td>
      <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $suite) . "</td>";?>
      <td class='c-actions'>
        <?php echo $this->testsuite->buildOperateMenu($suite, 'browse');?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <div class='table-statistic'><?php echo $summary;?></div>
    <?php $pager->show('right', 'pagerjs');?>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
