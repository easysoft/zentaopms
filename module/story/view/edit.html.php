<?php
/**
 * The edit view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: edit.html.php 4645 2013-04-11 08:32:09Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<?php js::set('oldProductID', $story->product);?>
<?php js::set('parentStory', !empty($story->children));?>
<?php js::set('moveChildrenTips', $lang->story->moveChildrenTips);?>
<div class='main-content' id='mainContent'>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $story->id;?></span>
        <?php echo html::a($this->createLink('story', 'view', "storyID=$story->id"), $story->title, '', 'class="story-title"');?>
        <small><?php echo $lang->arrow . ' ' . $lang->story->edit;?></small>
      </h2>
    </div>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='form-group'>
            <div class="input-control has-icon-right">
              <div class="colorpicker">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" title="<?php echo $lang->task->colorTag ?>"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                <ul class="dropdown-menu clearfix">
                  <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                </ul>
                <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $story->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".story-title"  data-provide="colorpicker">
              </div>
              <?php echo html::input('title', $story->title, 'class="form-control disabled story-title" disabled="disabled"');?>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendSpec;?></div>
            <div class='detail-content article-content'><?php echo $story->spec;?></div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->verify;?></div>
            <div class='detail-content article-content'><?php echo $story->verify;?></div>
          </div>
          <?php $this->printExtendFields($story, 'div', 'position=left');?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->comment;?></div>
            <div class='form-group'>
              <?php echo html::textarea('comment', '', "rows='5' class='form-control'");?>
            </div>
          </div>
          <div class='actions form-actions text-center'>
            <?php 
            echo html::hidden('lastEditedDate', $story->lastEditedDate);
            echo html::submitButton($lang->save);
            echo html::backButton();
            ?>
          </div>
          <hr class='small' />
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendBasicInfo;?></div>
            <table class='table table-form'>
              <?php if($story->parent <= 0):?>
              <tr>
                <th class='thWidth'><?php echo $lang->story->product;?></th>
                <td>
                  <div class='input-group'>
                    <?php echo html::select('product', $products, $story->product, "onchange='loadProduct(this.value);' class='form-control chosen control-product'");?>
                    <span class='input-group-addon fix-border fix-padding'></span>
                    <?php if($product->type != 'normal') echo html::select('branch', $branches, $story->branch, "onchange='loadBranch();' class='form-control chosen control-branch'");?>
                  </div>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='thWidth'><?php echo $lang->story->module;?></th>
                <td>
                  <div class='input-group' id='moduleIdBox'>
                  <?php
                  echo html::select('module', $moduleOptionMenu, $story->module, "class='form-control chosen'");
                  if(count($moduleOptionMenu) == 1)
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('tree', 'browse', "rootID=$story->product&view=story&currentModuleID=0&branch=$story->branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo '&nbsp; ';
                      echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($story->product)'");
                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <?php if($story->parent >= 0):?>
              <tr>
                <th><?php echo $lang->story->parent;?></th>
                <td><?php echo html::select('parent', $stories, $story->parent, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->plan;?></th>
                <td>
                  <div class='input-group' id='planIdBox'>
                  <?php $multiple = ($this->session->currentProductType != 'normal' and empty($story->branch)) ? true : false;?>
                  <?php echo html::select($multiple ? 'plan[]' : 'plan', $plans, $story->plan, "class='form-control chosen'" . ($multiple ? ' multiple' : ''));
                  if(count($plans) == 1) 
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('productplan', 'create', "productID=$story->product&branch=$story->branch", '', true), $lang->productplan->create, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductPlans($story->product)'");
                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->story->source;?></th>
                <td><?php echo html::select('source', $lang->story->sourceList, $story->source, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->sourceNote;?></th>
                <td><?php echo html::input('sourceNote', $story->sourceNote, "class='form-control'");?>
              </td>
              </tr>
              <tr>
                <th><?php echo $lang->story->status;?></th>
                <td>
                  <span class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></span>
                  <?php echo html::hidden('status', $story->status);?>
                </td>
              </tr>
              <?php if($story->status != 'draft'):?>
              <tr>
                <th><?php echo $lang->story->stage;?></th>
                <td>
                <?php
                if($story->stages and $branches)
                {
                    foreach($story->stages as $branch => $stage)
                    {
                        if(isset($branches[$branch])) echo '<p>' . $branches[$branch] . html::select("stages[$branch]", $lang->story->stageList, $stage, "class='form-control chosen'") . '</p>';
                    }
                }
                else
                {
                    echo html::select('stage', $lang->story->stageList, $story->stage, "class='form-control chosen'");
                }
                ?>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->story->pri;?></th>
                <td><?php echo html::select('pri', $lang->story->priList, $story->pri, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->estimate;?></th>
                <td><?php echo $story->parent >= 0 ? html::input('estimate', $story->estimate, "class='form-control'") : $story->estimate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->keywords;?></th>
                <td><?php echo html::input('keywords', $story->keywords, "class='form-control'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->story->mailto;?></th>
                <td>
                  <div class='input-group'>
                    <?php echo html::select('mailto[]', $users, str_replace(' ' , '', $story->mailto), "class='form-control chosen' multiple");?>
                    <?php echo $this->fetch('my', 'buildContactLists');?>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendLifeTime;?></div>
            <table class='table table-form'>
              <tr>
                <th class='thWidth'><?php echo $lang->story->openedBy;?></th>
                <td><?php echo zget($users, $story->openedBy);?></td>
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
                <td><?php echo html::select('closedReason', $lang->story->reasonList, $story->closedReason, "class='form-control'  onchange='setStory(this.value)'");?></td>
              </tr>
              <?php endif;?>
            </table>
          </div>
    
          <?php $this->printExtendFields($story, 'div', 'position=right');?>

          <div class='detail'>
            <div class='detail-title'><?php echo $lang->story->legendMisc;?></div>
            <table class='table table-form'>
              <?php if($story->status == 'closed'):?>
              <tr id='duplicateStoryBox'>
                <th class='w-70px'><?php echo $lang->story->duplicateStory;?></th>
                <td><?php echo html::input('duplicateStory', $story->duplicateStory, "class='form-control'");?></td>
              </tr>
              <?php endif;?>
              <tr>
                <th class='linkThWidth'><?php echo $lang->story->linkStories;?></th>
                <td><?php echo html::a($this->createLink('story', 'linkStory', "storyID=$story->id&type=linkStories", '', true), $lang->story->linkStory, '', "data-toggle='modal' data-type='iframe' data-width='95%'");?></td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <ul class='list-unstyled'>
                    <?php
                    if($story->linkStories)
                    {
                        $linkStories = explode(',', $story->linkStories);
                        foreach($linkStories as $linkStoryID)
                        {
                            if(isset($story->extraStories[$linkStoryID]))
                            {
                                echo "<li><div class='checkbox-primary'>";
                                echo "<input type='checkbox' checked='checked' name='linkStories[]' value=$linkStoryID />";
                                echo "<label>#{$linkStoryID} {$story->extraStories[$linkStoryID]}</label>";
                                echo '</div></li>';
                            }
                        }
                    }
                    ?>
                    <span id='linkStoriesBox'></span>
                  </ul>
                </td>
              </tr>
              <?php if($story->status == 'closed'):?>
              <tr class='text-top'>
                <th><?php echo $lang->story->childStories;?></th>
                <td>
                  <?php echo html::a($this->createLink('story', 'linkStory', "storyID=$story->id&type=childStories", '', true), $lang->story->linkStory, '', "data-toggle='modal' data-type='iframe' data-width='95%'");?>
                </td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <ul class='list-unstyled'>
                    <?php
                    if($story->childStories)
                    {
                        $childStories = explode(',', $story->childStories);
                        foreach($childStories as $childStoryID)
                        {
                            if(isset($story->extraStories[$childStoryID]))
                            {
                                echo "<li><div class='checkbox-primary'>";
                                echo "<input type='checkbox' checked='checked' name='childStories[]' value=$childStoryID />";
                                echo "<label>#{$childStoryID} {$story->extraStories[$childStoryID]}</label>";
                                echo '</div></li>';
                            }
                        }
                    }
                    ?>
                    <span id='childStoriesBox'></span>
                  </ul>
                </td>
              </tr>
              <?php endif;?>
           </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php js::set('storyType', $story->type);?>
<?php include '../../common/view/footer.html.php';?>
