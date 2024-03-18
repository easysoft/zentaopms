<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class bugBasicInfo extends wg
{
    protected static array $defineProps = array
    (
        'bug'        => '?object',   // 当前Bug。
        'product'    => '?object',   // 当前产品。
        'users'      => '?array',    // 用户列表。
        'statusText' => '?string',   // 状态信息。
        'modulePath' => '?string'    // 模块路径。
    );

    protected function getModuleItems(object $bug, null|bool|object $product, array $branches): array
    {
        $modulePath = $this->prop('modulePath', data('modulePath'));
        $items      = array();
        if($modulePath)
        {
            if($bug->branch and isset($branches[$bug->branch]))
            {
                $items[] = array('text' => $branches[$bug->branch]);
            }

            foreach($modulePath as $key => $module)
            {
                $items[] = $product->shadow || !common::hasPriv('bug', 'browse') ? array('text' => $module->name) : array('text' => $module->name, 'url' => createLink('bug', 'browse', "productID={$bug->product}&branch={$bug->branch}&browseType=byModule&param={$module->id}"), 'icon' => '');
            }
        }
        if(!$items) $items = array('/');
        return $items;
    }

    protected function getItems(): array
    {
        global $lang, $config;

        $bug = $this->prop('bug', data('bug'));
        if(!$bug) return array();

        $canViewProduct = common::hasPriv('project', 'view');
        $canBrowseBug   = common::hasPriv('bug', 'browse');
        $canViewPlan    = common::hasPriv('productplan', 'view');
        $canViewCase    = common::hasPriv('testcase', 'view');

        $product      = $this->prop('product',      data('product'));
        $project      = $this->prop('project',      data('project'));
        $users        = $this->prop('users',        data('users'));
        $statusText   = $this->prop('statusText',   data('statusText'));
        $branches     = $this->prop('branches',     data('branches'));
        $branchName   = $this->prop('branchName',   data('branchName'));

        $branchTitle  = sprintf($lang->product->branch, $lang->product->branchName[$product->type]);
        $productLink  = $bug->product && $canViewProduct ? helper::createLink('product',     'view',   "productID={$bug->product}")                           : '';
        $branchLink   = $bug->branch  && $canBrowseBug   ? helper::createLink('bug',         'browse', "productID={$bug->product}&branch={$bug->branch}")     : '';
        $planLink     = $bug->plan    && $canViewPlan    ? helper::createLink('productplan', 'view',   "planID={$bug->plan}&type=bug")                        : '';
        $fromCaseLink = $bug->case    && $canViewCase    ? helper::createLink('testcase',    'view',   "caseID={$bug->case}&caseVersion={$bug->caseVersion}") : '';

        $items = array();
        if(empty($product->shadow))
        {
            $items[$lang->bug->product] = $productLink ? array
            (
                'control'  => 'link',
                'url'      => $productLink,
                'text'     => $product->name,
                'data-app' => 'product'
            ) : $product->name;
        }

        if($product->type != 'normal')
        {
            $items[$branchTitle] = $branchLink ? array
            (
                'control'  => 'link',
                'url'      => $branchLink,
                'text'     => $branchName
            ) : $branchName;
        }

        $items[$lang->task->module] = array
        (
            'control' => 'breadcrumb',
            'items'   => $this->getModuleItems($bug, $product, $branches)
        );

        if(empty($product->shadow) || !empty($project->multiple))
        {
            $items[$lang->bug->plan] = $planLink ? array
            (
                'control'  => 'link',
                'url'      => $planLink,
                'text'     => $bug->planName
            ) : $bug->planName;
        }

        $caseText = $bug->case ? "#{$bug->case} {$bug->caseTitle}" : '';
        $items[$lang->bug->fromCase] = $fromCaseLink ? array
        (
            'control'  => 'link',
            'url'      => $fromCaseLink,
            'text'     => $caseText
        ) : $caseText;

        $items[$lang->bug->type] = zget($lang->bug->typeList, $bug->type, $bug->type);

        $items[$lang->bug->severity] = array
        (
            'control' => 'severitylabel',
            'level'   => $bug->severity
        );

        $items[$lang->bug->pri] = array
        (
            'control' => 'pri',
            'pri'     => $bug->pri,
            'text'    => $lang->bug->priList
        );

        $items[$lang->bug->status] = array
        (
            'control' => 'status',
            'class'   => 'bug-status',
            'status'  => $bug->status,
            'text'    => $statusText
        );

        $items[$lang->bug->activatedCount] = $bug->activatedCount ? array('content' => $bug->activatedCount) : '';
        $items[$lang->bug->activatedDate]  = formatTime($bug->activatedDate);
        $items[$lang->bug->confirmed]      = $lang->bug->confirmedList[$bug->confirmed];
        $items[$lang->bug->assignedTo]     = $bug->assignedTo ? zget($users, $bug->assignedTo) . $lang->at . formatTime($bug->assignedDate) : '';

        $items[$lang->bug->deadline] = array
        (
            'control' => 'span',
            'content' => html(formatTime($bug->deadline) . (isset($bug->delay) ? sprintf($lang->bug->notice->delayWarning, $bug->delay) : ''))
        );

        $items[$lang->bug->feedbackBy]  = $bug->feedbackBy;
        $items[$lang->bug->notifyEmail] = $bug->notifyEmail;

        $osList = explode(',', $bug->os);
        $osText = '';
        foreach($osList as $os) $osText .= zget($lang->bug->osList, $os) . ' ';
        $items[$lang->bug->os] = trim($osText);

        $browserList = explode(',', $bug->browser);
        $browserText = '';
        foreach($browserList as $browser) $browserText .= zget($lang->bug->browserList, $browser) . ' ';
        $items[$lang->bug->browser] = trim($browserText);

        $items[$lang->bug->keywords] = $bug->keywords;

        $mailtoList = explode(',', $bug->mailto);
        $mailtoText = '';
        foreach($mailtoList as $account) $mailtoText .= zget($users, $account) . ' ';
        $items[$lang->bug->mailto] = trim($mailtoText);

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('bug-basic-info'),
            set::items($this->getItems())
        );
    }
}
