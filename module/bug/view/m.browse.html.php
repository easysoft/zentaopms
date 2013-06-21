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
<ul data-role='listview'>
<?php foreach($bugs as $bug):?>
<?php if($bug->status == 'closed') continue;?>
<li>
    <?php echo html::a($this->createLink('bug', 'view', "bugID=$bug->id"), "#$bug->id $bug->title")?>
    <span class='ui-li-count'><?php echo $lang->bug->statusList[$bug->status]?></span>
</li>
<?php endforeach;?>
</ul>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
