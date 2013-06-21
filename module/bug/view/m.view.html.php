<?php
/**
 * The view file of view method of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4642 2013-04-11 05:38:37Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<h3 class='title'><?php echo "Bug #$bug->id $bug->title"?></h3>
<div class='textContent'><?php echo $bug->steps;?></td>
<?php include '../../common/view/m.action.html.php';?>
<div data-role='footer' data-position='fixed'>
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
    common::printIcon('bug', 'confirmBug', "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
    common::printIcon('bug', 'assignTo',   "bugID=$bug->id", '',   'button', '', '', 'iframe', true);
    common::printIcon('bug', 'resolve',    "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
    common::printIcon('bug', 'close',      "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
    common::printIcon('bug', 'activate',   "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
    echo '<li>' . html::a($browseLink, $lang->goback) . '</li>';
    ?>
    </ul>
  </div>
</div>
<?php include '../../common/view/m.footer.html.php';?>
