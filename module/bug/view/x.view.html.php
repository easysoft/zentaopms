<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<style>
#main {padding: 0px 0;} 
</style>
<div class='xuanxuan-card'>
  <div id="mainContent" class="main-row">
    <div class='panel-heading strong'>
        <span class="label label-id"><?php echo $bug->id;?></span> <span class="text" title='<?php echo $bug->title;?>'><?php echo $bug->title;?></span>
    </div>
    <div class="main-col">
      <div class="cell">
        <div class="detail">
          <div class="detail-title"><?php echo $lang->bug->legendSteps;?></div>
          <div class="detail-content article-content">
            <?php
            $tplStep = strip_tags(trim($lang->bug->tplStep));
            $steps   = str_replace('<p>' . $tplStep, '<p class="stepTitle">' . $tplStep . '</p><p>', $bug->steps);
  
            $tplResult = strip_tags(trim($lang->bug->tplResult));
            $steps     = str_replace('<p>' . $tplResult, '<p class="stepTitle">' . $tplResult . '</p><p>', $steps);
  
            $tplExpect = strip_tags(trim($lang->bug->tplExpect));
            $steps     = str_replace('<p>' . $tplExpect, '<p class="stepTitle">' . $tplExpect . '</p><p>', $steps);
  
            $steps = str_replace('<p></p>', '', $steps);
            echo $steps;
            ?>
          </div>
        </div>
        <?php echo $this->fetch('file', 'printFiles', array('files' => $bug->files, 'fieldset' => 'true'));?>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=bug&objectID=$bug->id");?>
        <?php include '../../common/view/action.html.php';?>
      </div>
      <?php
      $params        = "bugID=$bug->id";
      $copyParams    = "productID=$productID&branch=$bug->branch&extras=bugID=$bug->id";
      $convertParams = "productID=$productID&branch=$bug->branch&moduleID=0&from=bug&bugID=$bug->id";
      ?>
    </div>
  </div>
</div>
<div class='xuancard-actions fixed'>
<?php
$params = "id=$bug->id";
echo "<div class='btn-group'>";
common::printIcon('bug', 'confirmBug', $params, $bug, 'button', 'confirm', '', 'iframe', true, "data-width='90%'");
common::printIcon('bug', 'assignTo',   $params, $bug, 'button', '', '', 'iframe', true, "data-width='90%'");
common::printIcon('bug', 'resolve',    $params, $bug, 'button', 'checked', '', 'iframe showinonlybody', true, "data-width='90%'");
common::printIcon('bug', 'activate',   $params, $bug, 'button', '', '', 'text-success iframe showinonlybody', true, "data-width='90%'");
common::printIcon('bug', 'edit',       $params, $bug, 'button', '', '', 'iframe showinonlybody', true, "data-width='90%'");
common::printIcon('bug', 'close',      $params, $bug, 'button', '', '', 'text-danger iframe showinonlybody', true, "data-width='90%'");
echo "</div>";
?>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
