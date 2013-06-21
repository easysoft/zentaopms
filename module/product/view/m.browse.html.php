<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4660 2013-04-17 08:22:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<ul data-role='listview'>
<?php foreach($stories as $story):?>
<?php if($story->status == 'closed') continue;?>
<li>
<?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), "#$story->id $story->title")?>
<span class='ui-li-count'><?php echo $lang->story->statusList[$story->status]?></span>
</li>
<?php endforeach;?>
</ul>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
