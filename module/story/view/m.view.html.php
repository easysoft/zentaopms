<?php
/**
 * The view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<h3><?php echo "STORY #$story->id $story->title"?></h3>
<div>
  <p><strong><?php echo $lang->story->spec?><strong></p>
  <?php echo $story->spec?>
</div>
<div>
  <p><strong><?php echo $lang->story->verify?><strong></p>
  <?php echo $story->verify?>
</div>
<?php include '../../common/view/m.action.html.php'?>
<div data-role='footer' data-position='fixed'>
<div data-role='navbar'>
<ul>
<?php
  $browseLink  = $app->session->storyList != false ? $app->session->storyList : $this->createLink('product', 'browse', "productID=$story->product&moduleID=$story->module");
  $actionLinks = '';
  ob_start();
  if($this->story->isClickable($story, 'review'))
  {
      echo "<li>";
      common::printLink('story', 'review', "storyID=$story->id", $lang->story->review);
      echo '</li>';
  }
  if($this->story->isClickable($story, 'activate'))
  {
      echo "<li>";
      common::printLink('story', 'activate', "storyID=$story->id", $lang->story->activate);
      echo '</li>';
  }
  if($this->story->isClickable($story, 'close'))
  {
      echo "<li>";
      common::printLink('story', 'close', "storyID=$story->id", $lang->story->close);
      echo '</li>';
  }
  echo "<li>";
  common::printLink('story', 'delete', "storyID=$story->id", $lang->delete, 'hiddenwin');
  echo '</li>';
  echo "<li>" . html::a($browseLink, $lang->goback) . '</li>';
  $actionLinks = ob_get_contents();
  ob_clean();
  echo $actionLinks;
?>
</ul>
</div>
</div>
<?php include '../../common/view/syntaxhighlighter.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
