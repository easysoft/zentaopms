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
<script>
function showDetail(storyID)
{
    $.get(createLink('story', 'ajaxGetDetail', "storyID=" + storyID), function(data){$('#item' + storyID).html(data)})
}
</script>
<?php foreach($stories as $story):?>
<?php if($story->status == 'closed') continue;?>
<div  data-role="collapsible-set">
  <div data-role="collapsible" data-collapsed="true">
    <h1 onClick="showDetail(<?php echo $story->id; ?>)"><?php echo $story->title;?></h1>

    <div id='item<?php echo $story->id;?>'></div>
    <div data-role='navbar'>
      <ul>
        <?php
        if(!$story->deleted)
        {
            common::printIcon('story', 'review',     "storyID=$story->id", $story);
            common::printIcon('story', 'close',      "storyID=$story->id", $story, '', '', '', 'iframe', true);
            common::printIcon('story', 'activate',   "storyID=$story->id", $story, '', '', '', 'iframe', true);
            common::printIcon('story', 'delete', "storyID=$story->id", '', '', '', 'hiddenwin');
        }
        ?>
      </ul>
    </div>

  </div>
</div>
<?php endforeach;?>
<p><?php $pager->show('right', 'short')?></p>
<?php include '../../common/view/m.footer.html.php';?>
