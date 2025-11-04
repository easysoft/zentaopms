<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

define('STORY_MENUS', array('assignedTo','openedBy','reviewedBy','closedBy'));
define('STORY_COLUMNS', array('id','title','pri','status','productTitle','planTitle','openedByName','estimate','stage'));
define('PAGESIZE', array(5,10,15,20,25,30,35,40,45,50,100,200,500,1000,2000));

class storyTester extends tester
{
    /**
     * 验证m=user&f=story的各个子菜单内容和分页功能
     * Verify the content and pagination of each sub-menu in m=user&f=story.
     *
     * @param  array  $users
     * @param  array  $stories
     * @param  string $storyType story|requirement
     * @param  int    $pageSize
     * @return object 成功或失败对象
     */
    public function verifyUserStoryMenus($users = array(), $stories = array(), $storyType = 'story', $pageSize = 5)
    {
        if(empty($users))   return $this->failed('用户列表不能为空');
        if(empty($stories)) return $this->failed('需求列表不能为空');
        if(!in_array($pageSize, PAGESIZE, true)) return $this->failed('pageSize无效');

        $this->login();
        $form = $this->initForm('user', 'story', array('userID' => $users[0]->id, 'storyType' => $storyType), 'appIframe-system');
        $form->wait(2);

        foreach($users as $user)
        {
            if($user != $users[0])
            {
                $form->dom->userPicker->picker($user->realname);
                $form->wait(1);
                if($form->dom->userPicker->getText() != $user->realname) return $this->failed('task页面切换用户显示失败');
            }

            foreach(STORY_MENUS as $menu)
            {
                $form->dom->{$menu}->click();
                $form->wait(1);
                $ret = $this->verifyUserStoryContentAndPagination($form, $user, $stories, $storyType, $menu, $pageSize);
                if($ret) return $ret;
            }
        }

        return $this->success("开源版m=user&f=story&storyType={$storyType}测试成功");
    }

    /**
     * 检查具体子菜单的内容以及分页功能
     * Verify the content and pagination of a specific sub-menu in m=user&f=story.
     *
     * @param  object $form      页面对象
     * @param  array  $users     用户列表
     * @param  array  $stories   研发/用户需求数据
     * @param  string $storyType story|requirement
     * @param  string $menu      assignedTo|openedBy|reviewedBy|closedBy
     * @param  int    $pageSize  每页大小
     * @return object            成功返回null，失败返回对象
     */
    public function verifyUserStoryContentAndPagination($form, $user, $stories = array(), $storyType = 'story', $menu = 'assignedTo', $pageSize = 5)
    {
        $userStories = $this->filterStoriesByMenu($stories, $user, $menu, $storyType);

        $currentPageSize = (int) filter_var($form->dom->pagerSizeMenu->getText(), FILTER_SANITIZE_NUMBER_INT);

        if(count($userStories) > $currentPageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker(max(PAGESIZE));
            $form->wait(1);
        }

        $ret = $this->verifyStoryColumns($form, $userStories, $storyType);
        if($ret) return $ret;

        $ret = $this->verifyStoryPagination($form, $userStories, $pageSize);
        if($ret) return $ret;

        return null;
    }

    /**
     * 根据菜单过滤数据
     * Filter data based on menu
     *
     * @param  array  $stories   需求数据
     * @param  object $user      用户对象
     * @param  string $menu      菜单
     * @param  string $storyType story|requirement
     * @return array             过滤后的需求列表
     */
    private function filterStoriesByMenu($stories, $user, $menu)
    {
        $account  = is_object($user) ? ($user->account ?? '') : ($user['account'] ?? '');
        $filtered = array();

        foreach($stories as $story)
        {
            $sid    = is_object($story) ? ($story->id ?? null) : ($story['id'] ?? null);
            $status = is_object($story) ? ($story->status ?? '') : ($story['status'] ?? '');

            $val = is_object($story) ? ($story->{$menu} ?? '') : ($story[$menu] ?? '');
            if($val === $account)
            {
                if($menu != 'closedBy') $filtered[$sid] = $story;
                else if($status === 'closed') $filtered[$sid] = $story;
            }
        }
        return $filtered;
    }

    /**
     * 验证列值
     * Verify the values in columns
     *
     * @param  object $form      页面对象
     * @param  array  $stories   需求数据
     * @param  string $storyType story|requirement
     * @return object            成功返回null，失败返回对象
     */
    private function verifyStoryColumns($form, $stories, $storyType = 'story')
    {
        // Lite 版本或 requirement 类型会隐藏 plan；按是否有数据来判断并跳过对齐验证。
        $columns = STORY_COLUMNS;
        if($storyType === 'requirement' || ($this->config->vision ?? '') === 'lite') $columns = array_diff($columns, array('planTitle'));
        if(($this->config->vision ?? '') === 'lite') $columns = array_diff($columns, array('stage'));

        $display = array();
        foreach($columns as $col) $display[$col] = $form->dom->getElementListByXpathKey($col, true);

        if(count($display['id']) != count($stories)) return $this->failed('story页面数据数量与期望不一致');

        foreach($display['id'] as $i => $idText)
        {
            $id = (int)trim($idText);
            if(!isset($stories[$id])) return $this->failed("story{$id}不在当前用户视图范围内");
            $s  = $stories[$id];

            // 先检查title
            $itemTitle = $display['title'][$i] ?? '';
            $itemTitle = str_replace("\xC2\xA0", ' ', $itemTitle);
            $itemTitle = preg_replace('/\s+/u', ' ', trim($itemTitle));
            // 名称类字段：页面可能在标题前显示“子”(childrenAB)或“SR/UR/undefined/UR”等前缀，进行规范化后再比较
            foreach(array('SR', 'UR', 'undefined', 'UR', $this->lang->story->childrenAB) as $prefix)
            {
                if(str_starts_with($itemTitle, $prefix))
                {
                    $itemTitle = trim(substr($itemTitle, strlen($prefix)));
                    break;
                }
            }
            $expectTitle = str_replace("\xC2\xA0", ' ', $s->title);
            $expectTitle = preg_replace('/\s+/u', ' ', trim($expectTitle));
            if($itemTitle !== $expectTitle) return $this->failed("ID={$id}名称不匹配");

            foreach($columns as $c)
            {
                switch ($c)
                {
                    case 'id':
                    case 'title':
                        continue 2; // 名称在上面已单独验证
                    case 'status':
                    case 'stage':
                        // 根据语言获取对应文本
                        $expected = $this->lang->story->{$c . 'List'}->{$s->{$c}} ?? $s->{$c};
                        break;
                    case 'estimate':
                        // 根据语言添加后缀
                        $expected = $s->{$c} . $this->lang->task->suffixHour;
                        break;
                    default:
                        $expected = $s->{$c};
                        break;
                }
                if($display[$c][$i] != $expected) return $this->failed("ID={$id} {$c}不匹配，期望'{$expected}'，实际'{$display[$c][$i]}'");
            }
        }
        return null;
    }

    /**
     * 验证分页
     * verify pagination
     *
     * @param  object $form      页面对象
     * @param  array  $items     需求数据
     * @param  int    $pageSize  每页数量
     * @return object            成功返回null，失败返回对象
     */
    private function verifyStoryPagination($form, $items, $pageSize = 5)
    {
        $totalTasks = count($items);
        $pages      = (int) ceil($totalTasks / $pageSize);

        if(count($items) > $pageSize)
        {
            $form->dom->pagerSizeMenu->click();
            $form->wait(1);
            $form->dom->dropdownPicker($pageSize);
            $form->dom->firstPage->click();
            $form->wait(1);
        }

        for($i = 1; $i <= $pages; $i++)
        {
            $totalActual   = count($form->dom->getElementListByXpathKey('id' ));
            $totalExpected = ($pages === 1) ? $totalTasks : ($i < $pages ? $pageSize : ($totalTasks - $pageSize * ($pages - 1)));

            if($totalActual !== $totalExpected) return $this->failed("分页第{$i}页数量不匹配：期望{$totalExpected}，实际{$totalActual}");

            if($i < $pages && $form->dom->nextPage)
            {
                $form->dom->nextPage->click();
                $form->wait(1);
            }
        }
        return null;
    }
}
