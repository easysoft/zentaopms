<?php
/**
 * The view file of review method of story module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include './header.html.php';?>
<script language='Javascript'>
function switchShow(result)
{
    if(result == 'reject')
    {
        $('#closedReasonBox').show();
        $('#preVersionBox').hide();
    }
    else if(result == 'revert')
    {
        $('#preVersionBox').show();
        $('#closedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
    }
    else
    {
        $('#preVersionBox').hide();
        $('#closedReasonBox').hide();
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
        $('#closedReasonBox').hide();
    }
}

function setStory(reason)
{
    if(reason == 'duplicate')
    {
        $('#duplicateStoryBox').show();
        $('#childStoriesBox').hide();
    }
    else if(reason == 'subdivided')
    {
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').show();
    }
    else
    {
        $('#duplicateStoryBox').hide();
        $('#childStoriesBox').hide();
    }
}
</script>
<div class='yui-d0'>
  <form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $story->title;?></caption>
    <tr>
      <th class='w-100px rowhead'><?php echo $lang->story->reviewedDate;?></th>
      <td><?php echo html::input('reviewedDate', date('Y-m-d'), 'class=text-3');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->story->reviewResult;?></th>
      <td><?php echo html::select('result', $lang->story->reviewResultList, '', 'class=select-3 onchange="switchShow(this.value)"');?></td>
    </tr>
    <tr id='closedReasonBox' class='hidden'>
      <th class='rowhead'><?php echo $lang->story->closedReason;?></th>
      <td><?php echo html::select('closedReason', $lang->story->reasonList, '', 'class=select-3 onchange="setStory(this.value)"');?></td>
    </tr>
    <tr id='duplicateStoryBox' class='hidden'>
      <th class='rowhead'><?php echo $lang->story->duplicateStory;?></th>
      <td><?php echo html::input('duplicateStory', '', 'class=text-3');?></td>
    </tr>
    <tr id='childStoriesBox' class='hidden'>
      <th class='rowhead'><?php echo $lang->story->childStories;?></th>
      <td><?php echo html::input('childStories', '', 'class=text-3');?></td>
    </tr>
    <?php if($story->status == 'changed' or ($story->status == 'draft' and $story->version > 1)):?>
    <tr id='preVersionBox' class='hidden'>
      <th class='rowhead'><?php echo $lang->story->preVersion;?></th>
      <td><?php echo html::radio('preVersion', array_combine(range($story->version - 1, 1), range($story->version - 1, 1)), $story->version - 1);?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th class='rowhead'><?php echo $lang->story->assignedTo;?></th>
      <td><?php echo html::select('assignedTo', $users, $story->lastEditedBy ? $story->lastEditedBy : $story->openedBy, 'class=select-3');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->story->reviewedBy;?></th>
      <td><?php echo html::input('reviewedBy', $app->user->account . ', ', 'class=text-1');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->story->comment;?></th>
      <td><textarea rows='8' name='comment' class='area-1'></textarea></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'>
      <?php echo html::submitButton();?>
      <?php echo html::linkButton($lang->goback, inlink('view', "storyID=$story->id"));?>
      </td>
    </tr>
  </table>
  </form>
  <?php include '../../common/action.html.php';?>
</div>
<?php include '../../common/footer.html.php';?>
