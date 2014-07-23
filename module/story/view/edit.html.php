<?php
/**
 * The edit view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: edit.html.php 4645 2013-04-11 08:32:09Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?> <strong><?php echo $story->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title);?></strong>
    <small><?php echo html::icon($lang->icons['edit']) . ' ' . $lang->story->edit;?></small>
  </div>
  <div class='actions'>
    <?php echo html::submitButton($lang->save)?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <fieldset>
        <legend><?php echo $lang->story->legendSpec;?></legend>
        <div class='article-content'><?php echo $story->spec;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->story->verify;?></legend>
        <div class='article-content'><?php echo $story->verify;?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->story->comment;?></legend>
        <div class='form-group'>
          <?php echo html::textarea('comment', '', "rows='5' class='form-control'");?>
        </div>
      </fieldset>
      <div class='actions'>
        <?php 
        echo html::submitButton($lang->save);
        echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"));
        ?>
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->story->legendBasicInfo;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-80px'><?php echo $lang->story->product;?></th>
            <td><?php echo html::select('product', $products, $story->product, 'class="form-control chosen" onchange="loadProduct(this.value)";');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->module;?></th>
            <td id='moduleIdBox'>
            <?php
            echo html::select('module', $moduleOptionMenu, $story->module, 'class="form-control chosen"');
            if(count($moduleOptionMenu) == 1)
            {
                echo "<span class='help-block'>";
                echo html::a($this->createLink('tree', 'browse', "rootID=$story->product&view=story"), $lang->tree->manage, '_blank');
                echo html::a("javascript:loadProductModules($story->product)", $lang->refresh);
                echo '</span>';
            }
            ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->story->plan;?></th>
            <td id='planIdBox'>
            <?php echo html::select('plan', $plans, $story->plan, "class='form-control chosen'");
            if(count($plans) == 1) 
            {
                echo "<span class='help-block'>";
                echo html::a($this->createLink('productplan', 'create', "productID=$story->product"), $lang->productplan->create, '_blank');
                echo html::a("javascript:loadProductPlans($story->product)", $lang->refresh);
                echo '</span>';
            }
            ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->story->source;?></th>
            <td><?php echo html::select('source', $lang->story->sourceList, $story->source, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->status;?></th>
            <td><span class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></span></td>
          </tr>
          <?php if($story->status != 'draft'):?>
          <tr>
            <th><?php echo $lang->story->stage;?></th>
            <td><?php echo html::select('stage', $lang->story->stageList, $story->stage, 'class=form-control');?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->story->pri;?></th>
            <td><?php echo html::select('pri', $lang->story->priList, $story->pri, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->estimate;?></th>
            <td><?php echo html::input('estimate', $story->estimate, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->keywords;?></th>
            <td><?php echo html::input('keywords', $story->keywords, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->mailto;?></th>
            <td><?php echo html::select('mailto[]', $users, str_replace(' ' , '', $story->mailto), "class='form-control' multiple");?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->story->legendLifeTime;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-70px'><?php echo $lang->story->openedBy;?></th>
            <td><?php echo $users[$story->openedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, $story->assignedTo, 'class="form-control chosen"');?></td>
          </tr>
          <?php if($story->reviewedBy):?>
          <tr>
            <th><?php echo $lang->story->reviewedBy;?></th>
            <td><?php echo html::select('reviewedBy[]', $users, str_replace(' ', '', $story->reviewedBy), 'class="form-control chosen" multiple');?></td>
          </tr>
          <?php endif;?>
          <?php if($story->status == 'closed'):?>
          <tr>
            <th><?php echo $lang->story->closedBy;?></th>
            <td><?php echo html::select('closedBy', $users, $story->closedBy, 'class="form-control chosen"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->closedReason;?></th>
            <td><?php echo html::select('closedReason', $lang->story->reasonList, $story->closedReason, 'class="form-control"');?></td>
          </tr>
          <?php endif;?>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->story->legendMisc;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-70px'><?php echo $lang->story->duplicateStory;?></th>
            <td><?php echo html::input('duplicateStory', $story->duplicateStory, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->linkStories;?></th>
            <td><?php echo html::input('linkStories', $story->linkStories, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->childStories;?></th>
            <td><?php echo html::input('childStories', $story->childStories, "class='form-control'");?></td>
          </tr>
       </table>
      </fieldset>
    </div>
  </div>
</div>
</form>
<?php include '../../common/view/footer.html.php';?>
