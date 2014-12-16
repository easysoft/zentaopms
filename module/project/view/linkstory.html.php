<?php
/**
 * The link story view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: linkstory.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='titlebar'>
  <div class='heading' style='margin-bottom: 15px'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?></span>
    <strong><small><?php echo html::icon($lang->icons['link']);?></small> <?php echo $lang->project->linkStory;?></strong>
  </div>
  <div class='actions'><?php echo html::a($this->server->http_referer, '<i class="icon-goback icon-level-up icon-large icon-rotate-270"></i> ' . $lang->goback, '', "class='btn'")?></div>
  <div id='querybox' class='show'></div>
</div>
<form method='post' class='form-condensed'>
  <table align='center' class='table tablesorter table-fixed'> 
    <thead>
    <tr>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->story->product;?></th>
      <th><?php echo $lang->story->title;?></th>
      <th><?php echo $lang->story->plan;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-80px'><?php echo $lang->story->estimateAB;?></th>
    </tr>
    </thead>
    <tbody>
    <?php $storyCount = 0;?>
    <?php foreach($allStories as $story):?>
    <?php if(isset($prjStories[$story->id])) continue;?>
    <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id");?>
    <tr class='text-center'>
      <td class='text-left'>
        <input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>'/> 
        <input type='hidden'   name='products[]' value='<?php echo $story->product;?>' />
        <?php echo html::a($storyLink, $story->id);?>
      </td>
      <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
      <td><?php echo html::a($this->createLink('product', 'browse', "productID=$story->product"), $products[$story->product], '_blank');?></td>
      <td class='text-left nobr' title="<?php echo $story->title?>"><?php echo html::a($storyLink, $story->title);?></td>
      <td><?php echo $story->planTitle;?></td>
      <td><?php echo $users[$story->openedBy];?></td>
      <td><?php echo $story->estimate;?></td>
    </tr>
    <?php $storyCount ++;?>
    <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='7' class='text-left'>
          <div class='table-actions clearfix'>
            
          <?php 
          if($storyCount) echo "<div class='btn-group'>" . html::selectButton() .'</div>' . html::submitButton();
          else echo "<div class='text'>" . $lang->project->whyNoStories . '</div>';
          ?>
          </div>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<script type='text/javascript'>$(function(){ajaxGetSearchForm()});</script>
<?php include '../../common/view/footer.html.php';?>
