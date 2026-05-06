<?php
declare(strict_types=1);
/**
 * The userSelector widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      OpenAI
 * @package     zin
 * @link        https://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'avatar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class userSelector extends wg
{
    /**
     * 定义对外可配置属性。
     * Define the public props exposed by the widget.
     */
    protected static array $defineProps = array(
        'id?: string',
        'name?: string="selectedUsers"',
        'title?: string',
        'deptTitle?: string',
        'userTitle?: string',
        'selectedTitle?: string',
        'searchPlaceholder?: string',
        'allText?: string',
        'selectAllText?: string',
        'inverseText?: string',
        'emptyText?: string',
        'depts?: array',
        'users?: array',
        'existingUsers?: string|array',
        'value?: string|array'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    /**
     * 设置默认文案与默认数据源。
     * Initialize default copy and fallback data sources.
     */
    protected function created()
    {
        global $app, $lang;
        $app->loadLang('common');
        $app->loadLang('user');
        $app->loadLang('dept');

        /* 统一从语言项读取默认文案，避免在部件中散落硬编码文本。
           Read default labels from language items so the widget stays locale-aware. */
        $selectorLang = zget($lang, 'userSelector', new \stdClass());

        if(!$this->hasProp('title'))             $this->setProp('title', zget($selectorLang, 'title', 'Select Users'));
        if(!$this->hasProp('deptTitle'))         $this->setProp('deptTitle', zget($selectorLang, 'deptTitle', 'Filter by Department'));
        if(!$this->hasProp('userTitle'))         $this->setProp('userTitle', zget($selectorLang, 'userTitle', 'Select Users'));
        if(!$this->hasProp('selectedTitle'))     $this->setProp('selectedTitle', zget($selectorLang, 'selectedTitle', 'Selected'));
        if(!$this->hasProp('searchPlaceholder')) $this->setProp('searchPlaceholder', $lang->searchAB ?? $lang->user->search);
        if(!$this->hasProp('allText'))           $this->setProp('allText', zget($selectorLang, 'allText', 'All Users'));
        if(!$this->hasProp('selectAllText'))     $this->setProp('selectAllText', $lang->selectAll);
        if(!$this->hasProp('inverseText'))       $this->setProp('inverseText', $lang->selectReverse);
        if(!$this->hasProp('emptyText'))         $this->setProp('emptyText', zget($selectorLang, 'emptyText', 'No users available'));

        $name = $this->prop('name');
        if(!str_ends_with($name, ']')) $this->setProp('name', $name . '[]');

        if(!$this->prop('depts'))
        {
            $depts = $app->control->loadModel('dept')->getList();
            $this->setProp('depts', $depts);
        }

        if(!$this->prop('users'))
        {
            $users = $app->control->loadModel('user')->getList('nodeleted');
            $this->setProp('users', $users);
        }
    }

    /**
     * 将字符串或数组形式的账号列表规范成唯一的一维数组。
     * Normalize account collections from string/array input into a unique flat array.
     *
     * @param  string|array|null $value 原始传入值 / Raw incoming value.
     * @return array
     */
    protected function normalizeValue(string|array|null $value = null): array
    {
        $value = is_null($value) ? $this->prop('value') : $value;
        if(is_array($value)) $value = array_map('strval', $value);
        else $value = empty($value) ? array() : array_map('trim', explode(',', $value));

        $value = array_filter($value, static function($item)
        {
            return $item !== '';
        });

        return array_values(array_unique($value));
    }

    /**
     * 归一化需要从可选列表中排除的已有用户。
     * Normalize users that should be excluded from the selectable list.
     */
    protected function normalizeExistingUsers(): array
    {
        return $this->normalizeValue($this->prop('existingUsers'));
    }

    /**
     * 规范化部门数据，补齐树渲染需要的基础字段。
     * Normalize department data into a tree-friendly flat map.
     */
    protected function normalizeDepts(): array
    {
        $deptList = array();
        foreach((array)$this->prop('depts') as $dept)
        {
            if(is_object($dept)) $dept = (array)$dept;
            if(!is_array($dept)) continue;

            $id = (int)zget($dept, 'id', zget($dept, 'value', 0));
            if(!$id) continue;

            $deptList[$id] = array(
                'id'     => $id,
                'parent' => (int)zget($dept, 'parent', 0),
                'name'   => (string)zget($dept, 'name', zget($dept, 'text', '')),
                'path'   => (string)zget($dept, 'path', ",{$id},"),
                'grade'  => (int)zget($dept, 'grade', 1),
                'order'  => (int)zget($dept, 'order', 0)
            );
        }

        return $deptList;
    }

    /**
     * 规范化用户数据，并在这里完成“已存在用户排除”与“默认选中状态”计算。
     * Normalize user data and calculate both exclusion and checked state here.
     *
     * @param  array $deptMap        部门映射 / Department map.
     * @param  array $selected       默认已选账号 / Preselected accounts.
     * @param  array $existingUsers  需要排除的账号 / Accounts excluded from selection.
     * @return array
     */
    protected function normalizeUsers(array $deptMap, array $selected, array $existingUsers = array()): array
    {
        $userList = array();
        foreach((array)$this->prop('users') as $user)
        {
            if(is_object($user)) $user = (array)$user;
            if(!is_array($user)) continue;

            $account = (string)zget($user, 'account', zget($user, 'value', zget($user, 'id', '')));
            if($account === '') continue;
            if(in_array($account, $existingUsers, true)) continue;

            $realname = (string)zget($user, 'realname', zget($user, 'name', zget($user, 'text', $account)));
            $deptID   = (int)zget($user, 'dept', zget($user, 'deptID', 0));
            $deptPath = (string)zget($user, 'path', zget($deptMap[$deptID] ?? array(), 'path', ''));
            $avatar   = (string)zget($user, 'avatar', zget($user, 'src', ''));
            $keywords = strtolower(trim($realname . ' ' . $account));

            $userList[$account] = array(
                'account'  => $account,
                'realname' => $realname,
                'dept'     => $deptID,
                'path'     => $deptPath,
                'avatar'   => $avatar,
                'checked'  => in_array($account, $selected, true),
                'keywords' => $keywords
            );
        }

        return $userList;
    }

    /**
     * 递归构建部门树。
     * Build the department tree recursively.
     *
     * 这里保留轻量的原生结构，是为了精确控制节点展开、激活和缩进样式。
     * Native lightweight markup is kept here to retain exact control over expand,
     * active, and indentation behavior.
     */
    protected function buildDeptTree(array $deptMap, int $parent = 0): ?node
    {
        $children = array_filter($deptMap, static function($dept) use ($parent)
        {
            return $dept['parent'] === $parent;
        });

        if(empty($children)) return null;

        usort($children, static function($left, $right)
        {
            if($left['order'] === $right['order']) return $left['id'] <=> $right['id'];
            return $left['order'] <=> $right['order'];
        });

        $list = ul(setClass('user-selector-dept-children'));
        foreach($children as $dept)
        {
            $childTree = $this->buildDeptTree($deptMap, $dept['id']);
            $hasChild  = !empty($childTree);

            $list->add
            (
                li
                (
                    setClass('user-selector-dept-node'),
                    setData(array('dept-node' => $dept['id'], 'expanded' => $hasChild ? 'true' : 'false')),
                    div
                    (
                        setClass('user-selector-dept-row'),
                        $hasChild ? h::button
                        (
                            set::type('button'),
                            setClass('user-selector-dept-toggle'),
                            setData(array('toggle-dept' => $dept['id'])),
                            icon('chevron-right')
                        ) : span(setClass('user-selector-dept-toggle is-empty')),
                        h::button
                        (
                            set::type('button'),
                            setClass('user-selector-dept-option'),
                            setData(array('dept' => $dept['id'])),
                            $dept['name']
                        )
                    ),
                    $childTree
                )
            );
        }

        return $list;
    }

    /**
     * 构建左侧可选用户列表。
     * Build the selectable user list shown in the center panel.
     *
     * 复选框本身保持隐藏，只作为状态源；界面勾选态由自定义指示器负责呈现。
     * The checkbox remains hidden and acts as the state source, while the visual
     * checked state is rendered by a custom indicator.
     */
    protected function buildUserList(array $users): node
    {
        $list = ul(setClass('user-selector-user-list'), setData(array('users' => 'list')));
        foreach($users as $user)
        {
            $indicatorClass = $user['checked'] ? 'is-checked' : '';
            $list->add
            (
                li
                (
                    setClass('user-selector-user-item'),
                    setData(array(
                        'user'     => $user['account'],
                        'dept'     => (string)$user['dept'],
                        'path'     => $user['path'],
                        'keywords' => $user['keywords']
                    )),
                    h::button
                    (
                        set::type('button'),
                        setClass('user-selector-user-btn'),
                        h::input
                        (
                            set::type('checkbox'),
                            setClass('hidden user-selector-user-checkbox'),
                            set('value', $user['account']),
                            $user['checked'] ? set::checked(true) : null
                        ),
                        avatar
                        (
                            setClass('user-selector-avatar'),
                            set::size(32),
                            set::text($user['realname']),
                            !empty($user['avatar']) ? set::src($user['avatar']) : null
                        ),
                        div
                        (
                            setClass('user-selector-user-main'),
                            span(setClass('user-selector-user-name'), $user['realname']),
                            span(setClass('user-selector-user-account'), $user['account'])
                        ),
                        span
                        (
                            setClass('user-selector-user-indicator', $indicatorClass),
                            icon('check')
                        )
                    )
                )
            );
        }

        return $list;
    }

    /**
     * 构建右侧已选择用户列表。
     * Build the selected-user list rendered on the right side.
     */
    protected function buildSelectedList(array $users, array $selected): node
    {
        $list = ul(setClass('user-selector-selected-list'), setData(array('selected-list' => 'true')));

        foreach($selected as $account)
        {
            if(!isset($users[$account])) continue;

            $user = $users[$account];
            $list->add
            (
                li
                (
                    setClass('user-selector-selected-item'),
                    setData(array('selected-user' => $account)),
                    avatar
                    (
                        setClass('user-selector-avatar'),
                        set::size(32),
                        set::text($user['realname']),
                        !empty($user['avatar']) ? set::src($user['avatar']) : null
                    ),
                    span(setClass('user-selector-selected-name'), $user['realname']),
                    h::button
                    (
                        set::type('button'),
                        setClass('user-selector-remove user-selector-remove-user'),
                        setData(array('remove-user' => $account)),
                        icon('close')
                    )
                )
            );
        }

        return $list;
    }

    /**
     * 构建提交表单所需的隐藏输入。
     * Build hidden inputs used for normal form submission.
     *
     * 每个选中账号都会生成一个隐藏输入，名称沿用外部传入的 name。
     * Each selected account is mirrored into a hidden input using the externally
     * provided field name.
     */
    protected function buildHiddenInputs(array $selected): node
    {
        $name      = $this->prop('name');
        $container = div(setClass('user-selector-hidden-inputs hidden'), setData(array('hidden-inputs' => 'true')));

        foreach($selected as $account)
        {
            $container->add
            (
                input
                (
                    set::type('hidden'),
                    set::name($name),
                    set::value($account),
                    setData(array('hidden-input' => 'true'))
                )
            );
        }

        return $container;
    }

    /**
     * 组装部件最终结构。
     * Assemble the final widget structure.
     *
     * 这里先完成数据清洗，再统一下发给树、可选列表、已选列表与隐藏表单域，
     * 保证几处视图共享同一份状态源。
     * Data is normalized first and then shared by the tree, selectable list,
     * selected list, and hidden inputs so all views stay in sync.
     */
    protected function build()
    {
        $deptMap        = $this->normalizeDepts();
        $selected       = $this->normalizeValue();
        $existingUsers  = $this->normalizeExistingUsers();

        /* 已存在用户优先级高于默认选中值，避免它们重新出现在当前选择器中。
           Existing users take precedence over preselected values so they cannot
           reappear in this selector instance. */
        $selected       = array_values(array_filter($selected, static function($account) use ($existingUsers)
        {
            return !in_array($account, $existingUsers, true);
        }));
        $users          = $this->normalizeUsers($deptMap, $selected, $existingUsers);

        /* 再次基于最终可渲染用户清洗 selected，防止传入了不存在账号。
           Clean selected accounts again against the final renderable user map to
           avoid keeping unknown accounts in the UI or hidden inputs. */
        $selected  = array_values(array_filter($selected, static function($account) use ($users)
        {
            return isset($users[$account]);
        }));
        $rootID    = $this->prop('id', $this->gid);
        $deptTree  = $this->buildDeptTree($deptMap);

        return div
        (
            setID($rootID),
            setClass('user-selector'),
            set($this->getRestProps()),
            setData(array('widget' => 'user-selector', 'name' => $this->prop('name'))),
            on::init()->call('window.initUserSelector', jsRaw('$element')),
            div
            (
                setClass('user-selector-body'),
                div
                (
                    setClass('user-selector-panel user-selector-panel-dept'),
                    div(setClass('user-selector-panel-title'), $this->prop('deptTitle')),
                    div
                    (
                        setClass('user-selector-panel-content is-dept'),
                        div
                        (
                            setClass('user-selector-dept-row'),
                            span(setClass('user-selector-dept-toggle is-empty')),
                            h::button
                            (
                                set::type('button'),
                                setClass('user-selector-dept-option is-active'),
                                setData(array('dept' => 'all')),
                                $this->prop('allText')
                            )
                        ),
                        $deptTree
                    )
                ),
                div
                (
                    setClass('user-selector-panel user-selector-panel-users'),
                    div(setClass('user-selector-panel-title'), $this->prop('userTitle')),
                    div
                    (
                        setClass('user-selector-search'),
                        icon('search'),
                        h::input
                        (
                            set::type('text'),
                            setClass('user-selector-search-input'),
                            set('placeholder', $this->prop('searchPlaceholder')),
                            setData(array('search' => 'true'))
                        ),
                        h::button
                        (
                            set::type('button'),
                            setClass('user-selector-search-clear hidden'),
                            setData(array('clear-search' => 'true')),
                            icon('close')
                        )
                    ),
                    div
                    (
                        setClass('user-selector-toolbar'),
                        h::button
                        (
                            set::type('button'),
                            setClass('user-selector-toolbar-btn user-selector-action-inverse'),
                            setData(array('action' => 'inverse')),
                            $this->prop('inverseText')
                        ),
                        h::button
                        (
                            set::type('button'),
                            setClass('user-selector-toolbar-btn user-selector-action-select-all'),
                            setData(array('action' => 'select-all')),
                            $this->prop('selectAllText')
                        )
                    ),
                    div
                    (
                        setClass('user-selector-panel-content is-users'),
                        div
                        (
                            setClass('user-selector-list-scroll'),
                            $this->buildUserList($users),
                            div(setClass('user-selector-empty hidden'), setData(array('empty' => 'true')), $this->prop('emptyText'))
                        )
                    )
                ),
                div
                (
                    setClass('user-selector-panel user-selector-panel-selected'),
                    div
                    (
                        setClass('user-selector-panel-title'),
                        $this->prop('selectedTitle'),
                        span(setClass('user-selector-selected-count'), setData(array('selected-count' => 'true')), '(' . count($selected) . ')')
                    ),
                    div
                    (
                        setClass('user-selector-panel-content is-selected'),
                        div
                        (
                            setClass('user-selector-list-scroll'),
                            $this->buildSelectedList($users, $selected)
                        )
                    )
                )
            ),
            $this->buildHiddenInputs($selected)
        );
    }
}
