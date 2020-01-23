<?php
/* get last review info in this file. */
$lastReview  = $this->repo->getLastReviewInfo($file);
$repoModule  = isset($lastReview) && isset($lastReview->module) ? $lastReview->module : '';

/* get product by cookie or last review in this file. */
$repoProduct = isset($_COOKIE['repoPairs'][$repoID]) ? $_COOKIE['repoPairs'][$repoID] : '';
$repoProduct = isset($lastReview) && isset($lastReview->product) ? $lastReview->product : $repoProduct;
$products    = $this->loadModel('product')->getPairs();
$projects    = $this->repo->getProjectPairs($repoProduct);
$modules     = $this->loadModel('tree')->getOptionMenu($repoProduct, $viewType = 'bug', $startModuleID = 0);
$users       = $this->loadModel('user')->getPairs('devfirst|nodeleted');
$products    = array('' => '') + $products;
$projects    = array('' => '') + $projects;

$cwd         = getcwd();
$commiters   = $this->user->getCommiters();
$blamePairs  = array();
if($suffix and $suffix != 'binary' and strpos($this->config->repo->images, "|$suffix|") === false)
{
    $blames = $this->scm->blame($entry, $info->revision);
    foreach($blames as $line => $blame)
    {
        if(!isset($blame['committer']))
        {
            if(isset($blamePairs[$line - 1])) $blamePairs[$line] = $blamePairs[$line - 1];
            continue;
        }
        $blamePairs[$line] = zget($commiters, $blame['committer'], $blame['committer']);
    }
}
chdir($cwd);

$reviews       = $this->repo->getReview($repoID, $file, $info->revision);
$v1            = isset($oldRevision) ? $oldRevision : 0;
$bugUrl        = $this->repo->createLink('addBug',    "repoID=$repoID&file=&v1=$v1&v2={$info->revision}", "file=$file");
$commentUrl    = $this->repo->createLink('addComment');
$productSelect = html::select('product', $products, $repoProduct, 'class="product form-control chosen" onchange="changeProduct(this)"');
$branches      = $this->loadModel('branch')->getPairs($repoProduct);
$moduleSelect  = html::select('module', $modules, $repoModule, 'class="form-control chosen"');
$projectSelect = html::select('project', $projects, '', 'class="form-control chosen"');
$typeSelect    = html::select('repoType', $lang->repo->typeList, '', 'class="form-control chosen"');
$userSelect    = html::select('assignedTo', $users, '', 'class="form-control chosen assignedTo"');
$bugs = array();
foreach($reviews as $line => $lineReview)
{
    $lineBugs = array();
    foreach ($lineReview['bugs'] as $bugID => $bug)
    {
        $lineBug                            = array();
        $lineBug['id']                      = $bugID;
        $lineBug['line']                    = $line;
        $lineBug['title']                   = $bug->title;
        $lineBug['steps']                   = $bug->steps;
        $lineBug['realname']                = $bug->realname;
        $lineBug['openedDate']              = substr($bug->openedDate, 5, 11);
        $lineBug['lines']                   = $bug->lines;
        if($bug->edit) $lineBug['edit']     = true;
        if($bug->delete) $lineBug['delete'] = true;

        if(isset($lineReview['comments']))
        {
            $comments = $lineReview['comments'][$bugID];
            if(isset($comments))
            {
                $bugComments = array();
                foreach ($comments as $commentID => $comment)
                {
                    $bugComment = array(
                        'id' => $comment->id,
                        'edit' => $comment->edit,
                        'realname' => $comment->realname,
                        'date' => substr($comment->date, 5, 11),
                        'comment' => $comment->comment,
                    );
                    $bugComments[] = $bugComment;
                }
                $lineBug['comments'] = $bugComments;
            }
        }
        $lineBugs[] = $lineBug;
    }

    $bugs[$line] = $lineBugs;
}

js::set('bugs', $bugs);
js::set('productError', $lang->repo->error->product);
js::set('contentError', $lang->repo->error->commentText);
js::set('titleError', $lang->repo->error->title);
js::set('commentError', $lang->repo->error->comment);
js::set('submit', $lang->repo->submit);
js::set('cancel', $lang->repo->cancel);
js::set('confirmDelete', $lang->repo->notice->deleteBug);
js::set('confirmDeleteComment', $lang->repo->notice->deleteComment);
js::set('repoID', $repoID);
js::set('revision', $info->revision);
js::set('file', $file);
js::set('blamePairs', $blamePairs);
?>
<?php if(common::hasPriv('repo', 'addBug')):?>
<form id="bugForm" class="bugForm main-form hide" method="post" action="<?php echo $bugUrl?>">
  <div class="bugFormContainer">
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->repo->product?></th>
        <td class='w-p45'>
          <div class='input-group'>
          <?php echo $productSelect?>
          <?php if($branches) echo html::select('branch', $branches, '', "class='form-control' style='width:65px'");?>
          </div>
        </td>
        <th><?php echo $lang->repo->module?></th>
        <td><?php echo $moduleSelect?></td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->project?></th>
        <td><?php echo $projectSelect?></td>
        <th><?php echo $lang->repo->type?></th>
        <td><?php echo $typeSelect?></td>
      </tr>
      <tr>
        <th><?php echo $lang->repo->assign?></th>
        <td><?php echo $userSelect?></td>
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
        <td colspan='3'>
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
          <?php echo html::submitButton($lang->repo->submit, '', 'btn btn-wide btn-primary bugSubmit');?>
          <?php echo html::commonButton($lang->cancel, "onclick='hiddenForm()'", 'btn btn-wide');?>
        </td>
      </tr>
    </table>
    <div class="optional"></div>
  </div>
</form>
<?php else:?>
<form id='bugForm' class='bugForm hide'>
<?php printf($lang->user->errorDeny, $lang->repo->common, $lang->repo->addBug);?>
</form>
<?php endif;?>

<div class='panel panel-sm hide panel-bug' id='bugPanel'>
  <div class='panel-heading'>
    <div class='panel-actions pull-right'>
      <?php if(common::hasPriv('bug', 'view')):?>
      <a href='javascript:;' class='view-bug'>Bug#<span class='bugid'></span></a>
      <?php else:?>
      Bug#<span class='bugid'></span>
      <?php endif;?>
      <?php if(common::hasPriv('bug', 'edit')):?>
      <a href='javascript:;' class='edit bugEdit'><i class='icon-pencil'></i></a>
      <?php endif;?>
      <?php if(common::hasPriv('bug', 'delete')):?>
      <a href='javascript:;' class='delete bugDelete'><i class='icon-remove'></i></a>
      <?php endif;?>
      <a href="javascript:;"><i class='icon-chevron-down'></i></a>
    </div>
    <i class='icon-bug text-muted'></i> <strong class='title'></strong>
  </div>
  <div class='panel-body'>
    <p><?php echo $lang->repo->lines?> <strong class='code-lines'></strong> &nbsp; <i class='icon-user text-muted'></i> <strong class='realname'></strong> &nbsp;<span class='text-muted bug-date'><i class='icon-time'></i> <span class='openedDate'></span></span></p>
    <form method='post' class='bug-edit-form' action>
      <input type='text' name='commentText' class='commentInput form-control mgb-10'>
      <button type='submit' class='btn btn-sm btn-primary bugEditSubmit'><?php echo $lang->repo->submit?></button>
      <button type='button' class='btn btn-sm bugEditCancel'><?php echo $lang->repo->cancel?></button>
    </form>
    <p class='steps text-content'></p>
    <div class='comments'></div>
    <?php if(common::hasPriv('repo', 'addComment')):?>
    <button class='btn btn-sm addComment' type='button'><?php echo $lang->repo->addComment?></button>
    <form class='commentForm' method='post' action='<?php echo $commentUrl?>' id='commentForm'>
      <textarea name='comment' class='commentText form-control mgb-10' spellcheck='false' placeholder='<?php echo $lang->repo->notice->commentContent?>'></textarea>
      <input class='commentSubmit btn btn-sm btn-primary' type='submit' value='<?php echo $lang->repo->submit?>'>
      <input class='commentCancel btn btn-sm' type='button' value='<?php echo $lang->repo->cancel?>'>
      <input type='hidden' name='objectID' value=''>
      <div class='optional'></div>
    </form>
    <?php endif;?>
  </div>
</div>
<div class='comment hide' id='commentCell'>
  <i class='icon-user text-muted'></i> <strong class='realname'></strong>: <span class='comment-content text-content'></span> <span class='text-muted comment-date'>&nbsp;<i class='icon-time'></i> <span class='date'></span></span> &nbsp;<a href='javascript:;' class='edit commentEdit pull-right'><i class='icon-pencil'></i></a>
  <form method='post' class='comment-edit-form' action=''>
    <textarea name='commentText' class='commentInput form-control mgb-10'></textarea>
    <button type='submit' class='btn btn-sm btn-primary commentEditSubmit'><?php echo $lang->repo->submit?></button>
    <button type='button' class='btn btn-sm commentEditCancel'><?php echo $lang->repo->cancel?></button>
  </form>
</div>
<div class='dropdown' id="bugsPreview">
  <ul class='dropdown-menu fade'>
    <li class='dropdown-header'><?php echo $lang->repo->line?><strong class='code-line'></strong> &nbsp; <i class='icon-bug'></i> <strong class='bug-count'>0</strong> &nbsp; <i class='icon-comments-alt'></i> <strong class='comment-count'>0</strong></li>
  </ul>
</div>
<div id='rowTip' class='hide'><div class='row-tip'><i class='icon-chat-dot preview-icon'></i><div class='on-expand tip'><span><?php echo $lang->repo->expand?> </span><i class='icon-chevron-down'></i></div><div class='on-collapse tip'><span><?php echo $lang->repo->collapse?> </span><i class='icon-chevron-up'></i></div></div></div>
<script>
function changeProduct(select)
{
    loadProductBranches(select);
    productID = $(select).children('option:selected').val();
    link = createLink('repo', 'ajaxGetProjects', 'productID=' + productID);
    $(select).closest('.bugFormContainer').find('select[name=project]').parent().load(link, '', function(){$('#project').chosen();});
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=0&rootModuleID=0&returnType=html');
    $(select).closest('.bugFormContainer').find('select[name=module]').parent().load(moduleLink, '', function(){$('#module').chosen();});
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
            $(select).closest('.input-group').find('#branch').css('width', '65px');
        }   
    });
}

function loadBranch(select)
{
    branch = $(select).val();
    productID = $(select).closest('.input-group').find('#product').val();
    link = createLink('repo', 'ajaxGetProjects', 'productID=' + productID + '&branch=' + branch);
    $(select).closest('.bugFormContainer').find('select[name=project]').parent().load(link, '', function(){$('#project').chosen();});
    moduleLink = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html');
    $(select).closest('.bugFormContainer').find('select[name=module]').parent().load(moduleLink, '', function(){$('#module').chosen();});
}

function hiddenForm()
{
    $('.with-action-row').removeClass('with-action-row');
}

/* remove a function */
function loadModuleRelated(){}
</script>
