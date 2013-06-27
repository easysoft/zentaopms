<?php
/**
 * The browse view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: browse.html.php 4660 2013-04-17 08:22:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<?php foreach($bugs as $bug):?>
<?php if($bug->status == 'closed') continue;?>
<div  data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="true">
    <h1><?php echo $bug->title;?></h1>
    <div><?php echo $bug->steps;?></div>
    <div data-role='navbar'>
      <ul>
      <?php
      if($this->session->bugList)
      {
          $browseLink = $this->session->bugList;
      }
      else
      {
          $browseLink = $this->createLink('my', 'bug');
      }
      common::printIcon('bug', 'confirmBug', "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
      common::printIcon('bug', 'assignTo',   "bugID=$bug->id", '',   'button', '', '', 'iframe');
      common::printIcon('bug', 'resolve',    "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
      common::printIcon('bug', 'close',      "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
      common::printIcon('bug', 'activate',   "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
      ?>
      </ul>
    </div>
  </div>
</div>
<?php endforeach;?>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
