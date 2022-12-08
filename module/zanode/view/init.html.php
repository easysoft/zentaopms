<?php

/**
 * The view file of zanode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke zhao <zhaoke@cnezsoft.com>
 * @package     automation
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php js::set('nodeID', $nodeID) ?>
<?php js::set('zanodeLang', $lang->zanode); ?>
<div id='mainContent' class='main-content'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->zanode->init->title; ?></h2>
      </div>

      <div class="host-desc-container">
        <h4><?php echo $lang->zanode->init->descTitle; ?></h4>
        <div>
          <span class='dot-symbol'></span>
          <span>
            <?php echo $lang->zanode->init->initDesc . ' ' . $node->secret . ' -z ' . getWebRoot(true); ?>
          </span>
        </div>
        <div id="statusContainer">
        </div>
      </div>

      <div class="text-center host-action">
        <button type='button' id='checkServiceStatus' class='btn btn-info margin-top-18'><?php echo $lang->zanode->init->checkStatus; ?></button>
        <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, "data-app='{$app->tab}'", 'btn'); ?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/successmodal.html.php';?>
<?php include '../../common/view/footer.html.php'; ?>
