<?php
/* get last review info in this file. */
$lastReview = $this->mr->getLastReviewInfo($repo->id);

/* Get product pairs. */
if(isset($repo->product) and $repo->product)
{
    $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('`id`')->in($repo->product)->fetchPairs();
}
else
{
    $products = $this->loadModel('product')->getPairs();
}

/* get product by cookie or last review in this file. */
$repoProduct   = isset($_COOKIE['repoPairs'][$repoID]) ? $_COOKIE['repoPairs'][$repoID] : '';
$repoProduct   = (!empty($lastReview->bug) && isset($lastReview->bug->product)) ? $lastReview->bug->product : $repoProduct;
$repoProduct   = isset($products[$repoProduct]) ? $repoProduct : key($products);
$bugRepoModule = (!empty($lastReview->bug) && $lastReview->bug->product == $repoProduct) ? $lastReview->bug->module : '';
$executions    = $this->mr->getExecutionPairs($repoProduct);
$modules       = $this->loadModel('tree')->getOptionMenu($repoProduct, $viewType = 'bug', $startModuleID = 0);
$users         = $this->loadModel('user')->getPairs('devfirst|nodeleted|noclosed');
$products      = array('' => '') + $products;
$executions    = array('' => '') + $executions;

$taskExecutions = $executions;
if(empty($repo->product)) $taskExecutions = array('' => '') + $this->loadModel('execution')->getPairs();
$repoExecution  = (!empty($lastReview->task) && isset($lastReview->task->execution)) ? $lastReview->task->execution : $this->session->execution;
$repoExecution  = isset($taskExecutions[$repoExecution]) ? $repoExecution : key($taskExecutions);
$taskModules    = array('' => '');
$taskRepoModule = 0;
$taskMembers    = array('' => '');
if($repoExecution)
{
    $taskModules    = $this->loadModel('tree')->getTaskOptionMenu($repoExecution, 0, 0, '');
    $taskRepoModule = (!empty($lastReview->task) && $lastReview->task->execution == $repoExecution) ? $lastReview->task->module : '';
    $taskMembers    = $this->loadModel('user')->getTeamMemberPairs($repoExecution, 'execution', 'nodeleted');
}

$reviews = $this->mr->getReview($repoID, $MR->id);
$v1      = helper::safe64Encode($sourceBranch);
$v2      = helper::safe64Encode($targetBranch);

$this->loadModel('repo');

$taskModuleSelect    = html::select('taskModule', $taskModules, $taskRepoModule, 'class="form-control chosen"');
$taskUserSelect      = html::select('taskAssignedTo', $taskMembers, '', 'class="form-control chosen"');
$taskExecutionSelect = html::select('taskExecution', $taskExecutions, $repoExecution, 'class="form-control chosen" onchange="changeExecution(this)"');

$reviewUrl       = $this->createLink('mr', 'addReview', "repoID=$repoID&mr={$MR->id}&v1=$v1&v2=$v2");
$productSelect   = html::select('product', $products, $repoProduct, 'class="product form-control chosen" onchange="changeProduct(this)"');
$branches        = $this->loadModel('branch')->getPairs($repoProduct);
$moduleSelect    = html::select('module', $modules, $bugRepoModule, 'class="form-control chosen"');
$executionSelect = html::select('execution', $executions, '', 'class="form-control chosen"');
$typeSelect      = html::select('repoType', $lang->repo->typeList, '', 'class="form-control chosen"');
$userSelect      = html::select('assignedTo', $users, '', 'class="form-control chosen assignedTo"');

$lineReviews = array();
foreach($reviews as $line => $lineReview)
{
    foreach($lineReview as $objectType => $objects)
    {
        foreach($objects as $objectID => $object)
        {
            $lineReview               = array();
            $lineReview['id']         = $objectID;
            $lineReview['line']       = $line;
            $lineReview['title']      = $objectType == 'bug' ? $object->title : $object->name;
            $lineReview['content']    = $objectType == 'bug' ? $object->steps : $object->desc;
            $lineReview['realname']   = $object->realname;
            $lineReview['openedDate'] = substr($object->openedDate, 5, 11);
            $lineReview['lines']      = $object->lines;
            $lineReview['objectType'] = $objectType;
            $lineReview['entry']      = $object->entry;
            $lineReview['edit']       = common::hasPriv($objectType, 'edit');
            $lineReview['delete']     = common::hasPriv($objectType, 'delete');
            $lineReview['view']       = common::hasPriv($objectType, 'view');
            $lineReviews[$line][] = $lineReview;
        }
    }
}

js::set('reviews', $lineReviews);
js::set('productError', $lang->repo->error->product);
js::set('contentError', $lang->repo->error->commentText);
js::set('titleError', $lang->repo->error->title);
js::set('commentError', $lang->repo->error->comment);
js::set('submit', $lang->repo->submit);
js::set('cancel', $lang->repo->cancel);
js::set('confirmDelete', $lang->repo->notice->deleteReview);
js::set('repoID', $repoID);
js::set('MRID', $MR->id);
js::set('revision', '');
?>
<?php if(common::hasPriv('mr', 'addReview')):?>
<form id="reviewForm" class="reviewForm main-form hide" method="post" action="<?php echo $reviewUrl?>">
  <div class="reviewFormContainer">
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->mr->reviewType;?></th>
        <td class='w-p45'><?php echo html::select('reviewType', $lang->mr->reviewTypeList, 'bug', "class='form-control' onchange=changeReviewType(this)")?></td>
        <th class='w-100px'></th>
        <td class='w-p45'></td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->product?></th>
        <td>
          <div class='input-group'>
          <?php echo $productSelect?>
          <?php if($branches) echo html::select('branch', $branches, '', "class='form-control' style='width:95px' onchange='loadBranch(this)'");?>
          </div>
        </td>
        <th><?php echo $lang->repo->module?></th>
        <td><?php echo $moduleSelect?></td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->execution?></th>
        <td>
          <div class='bugExecutionBox'><?php echo $executionSelect?></div>
          <div class='taskExecutionBox hide required'><?php echo $taskExecutionSelect?></div>
        </td>
        <th><?php echo $lang->repo->type?></th>
        <td><?php echo $typeSelect?></td>
        <th class='taskModuleBox hide'><?php echo $lang->repo->module?></th>
        <td class='taskModuleBox hide'><?php echo $taskModuleSelect?></td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->assign?></th>
        <td>
          <div class='bugAssignedToBox'><?php echo $userSelect?></div>
          <div class='taskAssignedToBox hide'><?php echo $taskUserSelect?></div>
        </td>
        <th><?php echo $lang->repo->lines?></th>
        <td class='lines'>
          <div class="input-group">
            <input class="line form-control" type="number" min="1" name="begin">
            <span class="input-group-addon fix-border">-</span>
            <input class="line form-control" type="number" min="1" name="end">
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->title?></th>
        <td colspan='3' class='required'>
        <?php echo html::input('title', '', "class='form-control'");?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->detile?></th>
        <td colspan='3'><textarea id="commentText" name="commentText" class="commentInput form-control" spellcheck="false"></textarea></td>
      </tr>
      <tr>
        <th></th>
        <td colspan="3" class='form-actions'>
          <?php echo html::hidden('entry');?>
          <?php echo html::submitButton($lang->repo->submit, '', 'btn btn-wide btn-primary reviewSubmit');?>
          <?php echo html::commonButton($lang->cancel, "onclick='hiddenForm()'", 'btn btn-wide');?>
        </td>
      </tr>
    </table>
    <div class="optional"></div>
  </div>
</form>
<?php else:?>
<form id='reviewForm' class='reviewForm hide'>
<?php printf($lang->user->errorDeny, $lang->mr->common, $lang->mr->addReview);?>
</form>
<?php endif;?>

<div class='panel panel-sm hide panel-review' id='reviewPanel'>
  <div class='panel-heading'>
    <div class='panel-actions pull-right'>
      <a href='javascript:;' class='delete reviewDelete'><i class='icon-remove'></i></a>
      <a href="javascript:;"><i class='icon-chevron-down'></i></a>
    </div>
    <i class='text-muted'></i> <strong class='title'></strong>
  </div>
  <div class='panel-body'>
    <p><?php echo $lang->repo->lines?> <strong class='code-lines'></strong> &nbsp; <i class='icon-user text-muted'></i> <strong class='realname'></strong> &nbsp;<span class='text-muted bug-date'><i class='icon-time'></i> <span class='openedDate'></span></span></p>
    <p class='content text-content'></p>
  </div>
</div>
<div class='dropdown' id="reviewsPreview">
  <ul class='dropdown-menu fade'>
    <li class='dropdown-header'><?php echo $lang->repo->line?><strong class='code-line'></strong> &nbsp; <i class='icon-bug'></i> <strong class='bug-count'>0</strong> &nbsp; <i class='icon-comments-alt'></i> <strong class='comment-count'>0</strong></li>
  </ul>
</div>
<div id='rowTip' class='hide'><div class='row-tip'><div class='on-expand tip'><span><?php echo $lang->repo->expand?> </span><i class='icon-chevron-down'></i></div><div class='on-collapse tip'><span><?php echo $lang->repo->collapse?> </span><i class='icon-chevron-up'></i></div></div></div>
<script>
function changeReviewType(select)
{
    var reviewType = $(select).val();
    if(reviewType == 'bug')
    {
        $('#product').closest('td').show();
        $('#product').closest('td').prev().show();
        $('#module').closest('td').show();
        $('#module').closest('td').prev().show();
        $('#repoType').closest('td').show();
        $('#repoType').closest('td').prev().show();

        $('.taskModuleBox').addClass('hide');
        $('.bugAssignedToBox').removeClass('hide');
        $('.taskAssignedToBox').addClass('hide');
        $('.bugExecutionBox').removeClass('hide');
        $('.taskExecutionBox').addClass('hide');
    }
    else
    {
        $('#product').closest('td').hide();
        $('#product').closest('td').prev().hide();
        $('#module').closest('td').hide();
        $('#module').closest('td').prev().hide();
        $('#repoType').closest('td').hide();
        $('#repoType').closest('td').prev().hide();

        $('.taskModuleBox').removeClass('hide');
        $('.bugAssignedToBox').addClass('hide');
        $('.taskAssignedToBox').removeClass('hide');
        $('.bugExecutionBox').addClass('hide');
        $('.taskExecutionBox').removeClass('hide');
    }
}

function changeProduct(select)
{
    loadProductBranches(select);
    productID = $(select).children('option:selected').val();
    link = createLink('repo', 'ajaxGetExecutions', 'productID=' + productID);
    $(select).closest('.reviewFormContainer').find('select[name=execution]').parent().load(link, '', function(){$('#execution').chosen();});
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=0&rootModuleID=0&returnType=html');
    $(select).closest('.reviewFormContainer').find('select[name=module]').parent().load(moduleLink, '', function(){$('#module').chosen();});
}

function changeExecution(select)
{
    if($('#reviewType').val() == 'bug') return false;

    var executionID = $(select).val();

    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'execution=' + executionID + '&viewtype=task&branch=0&rootModuleID=0&returnType=html');
    $('td.taskModuleBox').load(moduleLink, '', function(){$('.taskModuleBox #module').attr('id', 'taskModule').attr('name', 'taskModule').chosen();});

    assignLink = createLink('execution', 'ajaxGetMembers', 'executionID=' + executionID);
    $('.taskAssignedToBox').load(assignLink, '', function(){$('.taskAssignedToBox #assignedTo').attr('id', 'taskAssignedTo').attr('name', 'taskAssignedTo').chosen();});
}

function loadProductBranches(select)
{
    $(select).closest('.input-group').find('#branch').remove();
    productID = $(select).children('option:selected').val();
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + productID), function(data)
    {
        if(data)
        {
            $(select).closest('.input-group').append(data);
            $(select).closest('.input-group').find('#branch').css('width', '95px');
        }
    });
}

function loadBranch(select)
{
    branch = $(select).val();
    productID = $(select).closest('.input-group').find('#product').val();
    link = createLink('repo', 'ajaxGetExecutions', 'productID=' + productID + '&branch=' + branch);
    $(select).closest('.reviewFormContainer').find('select[name=execution]').parent().load(link, '', function(){$('#execution').chosen();});
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html');
    $(select).closest('.reviewFormContainer').find('select[name=module]').parent().load(moduleLink, '', function(){$('#module').chosen();});
}

function hiddenForm()
{
    $('.with-action-row').removeClass('with-action-row');
}

/* remove a function */
function loadModuleRelated(){}
</script>
