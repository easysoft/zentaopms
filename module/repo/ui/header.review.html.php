<?php
declare(strict_types=1);
/**
 * The header view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     repo
 * @link        http://www.zentao.net
 */

namespace zin;
global $app;
$app->loadLang('bug');

/* get last review info in this file. */
$lastReview   = $this->repo->getLastReviewInfo($file);
$repoModule   = isset($lastReview) && isset($lastReview->module) ? $lastReview->module : '';
$infoRevision = isset($info->revision) ? (string)$info->revision : $revision;

/* Get product pairs. */
if($repo->product)
{
    $products       = array();
    $userProductIds = explode(',', $this->app->user->view->products);
    $repoProductIds = explode(',', $repo->product);
    if($userProductIds && $repoProductIds) $products = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('`id`')->in(array_intersect($userProductIds, $repoProductIds))->fetchPairs();
}
else
{
    $products = $this->loadModel('product')->getPairs('', 0, '', 'all');
}

/* get product by cookie or last review in this file. */
$repoProduct = isset($_COOKIE['repoPairs'][$repoID]) ? $_COOKIE['repoPairs'][$repoID] : '';
$repoProduct = isset($lastReview) && isset($lastReview->product) ? $lastReview->product : $repoProduct;
$repoProduct = isset($products[$repoProduct]) ? $repoProduct : key($products);
$executions  = $this->repo->getExecutionPairs($repoProduct);
$modules     = $this->loadModel('tree')->getOptionMenu($repoProduct, $viewType = 'bug', $startModuleID = 0);
$users       = $this->loadModel('user')->getPairs('devfirst|nodeleted|noclosed');
$products    = array('' => '') + $products;
$executions  = array('' => '') + $executions;

$cwd         = getcwd();
$commiters   = $this->user->getCommiters();
$blamePairs  = array();
if($suffix and $suffix != 'binary' and strpos($this->config->repo->images, "|$suffix|") === false)
{
    $blames = $this->scm->blame($entry, $infoRevision, false);
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

$v1              = isset($oldRevision) ? str_replace('-', '*', $oldRevision) : 0;
$v2              = str_replace('-', '*', $infoRevision);
$reviews         = $this->repo->getReview($repoID, $file, empty($type) || $type == 'view' ? '' : $v2);
$bugUrl          = $this->repo->createLink('addBug', "repoID=$repoID");
$commentUrl      = $this->repo->createLink('addComment');
$branches        = $this->loadModel('branch')->getPairs($repoProduct);
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
        $lineBug['file']                    = $bug->entry;
        $lineBug['revision']                = $bug->v2;
        if($bug->edit) $lineBug['edit']     = true;
        if(!empty($bug->delete)) $lineBug['delete'] = true;

        if(isset($lineReview['comments']))
        {
            if(isset($lineReview['comments'][$bugID]))
            {
                $comments    = $lineReview['comments'][$bugID];
                $bugComments = array();
                foreach ($comments as $commentID => $comment)
                {
                    $bugComment = array(
                        'id'       => $comment->id,
                        'edit'     => $comment->edit,
                        'realname' => $comment->realname,
                        'user'     => $comment->user,
                        'date'     => substr($comment->date, 5, 11),
                        'comment'  => $comment->comment,
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
$productPickerItems   = array();
$executionPickerItems = array();
$modulePickerItems    = array();
$typePickerItems      = array();
$userPickerItems      = array();
$branchPickerItems    = array();
foreach($products as $id => $product) $productPickerItems[] = array('text' => $product, 'value' => $id);
foreach($branches as $id => $branch) $branchPickerItems[] = array('text' => $branch, 'value' => $id);
foreach($executions as $id => $execution) $executionPickerItems[] = array('text' => $execution, 'value' => $id);
foreach($modules as $id => $module) $modulePickerItems[] = array('text' => $module, 'value' => $id);
foreach($lang->bug->typeList as $id => $repoType) $typePickerItems[] = array('text' => $repoType, 'value' => $id);
foreach($users as $id => $user) $userPickerItems[] = array('text' => $user, 'value' => $id);

$browser = helper::getBrowser();
jsVar('browser', $browser['name']);
jsVar('bugs', $bugs);
jsVar('products', $productPickerItems);
jsVar('executions', $executionPickerItems);
jsVar('modules', $modulePickerItems);
jsVar('branches', $branchPickerItems);
jsVar('users', $userPickerItems);
jsVar('typeList', $typePickerItems);
jsVar('userList', $userPickerItems);
jsVar('repoProduct', $repoProduct);
jsVar('repoModule', $repoModule);
jsVar('bugRevision', $v2);
jsVar('productError', $lang->repo->error->product);
jsVar('contentError', $lang->repo->error->commentText);
jsVar('titleError', $lang->repo->error->title);
jsVar('commentError', $lang->repo->error->comment);
jsVar('submit', $lang->repo->submit);
jsVar('cancel', $lang->repo->cancel);
jsVar('confirmDelete', $lang->repo->notice->deleteBug);
jsVar('confirmDeleteComment', $lang->repo->notice->deleteComment);
jsVar('blamePairs', $blamePairs);
jsVar('isonlybody', isonlybody());

formPanel
(
    set::formID('bugForm'),
    setStyle('display', 'none'),
    set::url($bugUrl),
    set::actions
    (
        array(
            'submit',
            array(
                'text'    => $lang->cancel,
                'class'   => 'cancel',
                'onclick' => 'hiddenForm()'
            )
        )
    ),
    $this->app->tab == 'project' && $objectID ? input(set::type('hidden'), set::name('project'), set::value($objectID)) : null,
    formRow
    (
        formGroup
        (
            set::id('product'),
            set::label($lang->repo->product),
            set::control('picker'),
            set::name('product'),
            set::required(true),
            set::items($products),
            set::value($repoProduct),
            on::change('changeProduct')
        ),
        formGroup
        (
            setID('branch'),
            setClass('ml-1'),
            setStyle('display', 'none'),
            set::control('picker'),
            set::name('branch'),
            set::items($productPickerItems),
            on::change('loadBranch')
        )
    ),
    formRow
    (
        formGroup
        (
            set::id('execution'),
            set::width('1/2'),
            set::label($lang->repo->execution),
            set::control('picker'),
            set::name('execution'),
            set::items($executions),
            on::change('changeExecution')
        ),
        formGroup
        (
            set::id('module'),
            set::width('1/2'),
            set::label($lang->repo->module),
            set::control('picker'),
            set::name('module'),
            set::items($modules),
            set::value($repoModule),
            on::change('changeExecution')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->repo->title),
            set::name('title'),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            set::id('repoType'),
            set::width('1/2'),
            set::label($lang->repo->type),
            set::control('picker'),
            set::name('repoType'),
            set::items($lang->repo->typeList)
        ),
        formGroup
        (
            set::id('assignedTo'),
            set::width('1/2'),
            set::label($lang->repo->assign),
            set::control('picker'),
            set::name('assignedTo'),
            set::items($users)
        )
    ),
    formGroup
    (
        set::label($lang->repo->lines),
        inputGroup
        (
            input
            (
                set::type('number'),
                set::min(1),
                set::name('begin')
            ),
            span
            (
                setClass('input-group-addon ghost'),
                ' - '
            ),
            input
            (
                set::type('number'),
                set::min(1),
                set::name('end')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            setClass('w-full'),
            set::label($lang->repo->detile),
            set::name('steps'),
            set::control('editor'),
            set::rows(6)
        )
    ),
    formHidden('fromReversion', base64_decode($v1)),
    formHidden('revision', base64_decode($v2)),
    formHidden('file', $file)
);
