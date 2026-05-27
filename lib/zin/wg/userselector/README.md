# userSelector

`userSelector` 是一个通用的 ZIN 用户选择部件，支持：

- 按部门筛选用户
- 搜索用户
- 全选 / 反选当前可见用户
- 显示已选择用户
- 通过隐藏表单控件提交选中用户的 `account`
- 排除已存在用户，避免在可选列表中重复出现

## 入口

在 ZIN 中通过 `userSelector()` 调用：

```php
use function zin\userSelector;
```

或直接在已有 `zin` 命名空间上下文中使用：

```php
userSelector(...)
```

部件 helper 定义见 [func.php](/repo/zentaopms/lib/zin/func.php)。

## 基本示例

```php
userSelector();
```

默认行为：

- 标题显示为“选择用户”
- 默认表单字段名为 `selectedUsers[]`
- 未传 `users` 时，自动读取系统未删除用户
- 未传 `depts` 时，自动读取部门列表

## 常用示例

### 1. 指定表单字段名

```php
userSelector
(
    set::name('reviewers')
);
```

提交表单时会生成多个隐藏控件：

```html
<input type="hidden" name="reviewers[]" value="zhangsan" />
<input type="hidden" name="reviewers[]" value="lisi" />
```

### 2. 设置默认已选用户

```php
userSelector
(
    set::value(array('zhangsan', 'lisi'))
);
```

也支持逗号分隔字符串：

```php
userSelector
(
    set::value('zhangsan,lisi')
);
```

### 3. 排除已存在用户

```php
userSelector
(
    set::existingUsers(array('wangwu', 'zhaoliu'))
);
```

也支持逗号分隔字符串：

```php
userSelector
(
    set::existingUsers('wangwu,zhaoliu')
);
```

行为说明：

- `existingUsers` 中的用户不会出现在“选择用户”列表
- 搜索、按部门筛选、全选、反选都不会作用到这些用户
- 如果这些用户同时出现在 `value` 中，也会被自动排除
- 已排除用户不会出现在“已选择”列表中
- 已排除用户不会生成隐藏表单控件

### 4. 自定义样式

`userSelector` 会把外部传入的剩余属性透传到根节点，因此可以直接传 `style`、`className` 等属性：

```php
userSelector
(
    set::style(array('max-height' => 'calc(100vh - 12.5rem)')),
    set::className('my-user-selector')
);
```

## 支持的主要属性

| 属性 | 类型 | 默认值 | 说明 |
| --- | --- | --- | --- |
| `name` | `string` | `selectedUsers` | 隐藏表单字段名，内部会自动补成数组形式 `[]` |
| `value` | `string|array` | `null` | 默认已选用户，值为用户 `account` |
| `existingUsers` | `string|array` | `null` | 已存在用户，值为用户 `account`，这些用户会从可选列表中排除 |
| `users` | `array` | 自动读取 | 自定义用户数据源 |
| `depts` | `array` | 自动读取 | 自定义部门数据源 |
| `title` | `string` | `选择用户` | 部件标题 |
| `deptTitle` | `string` | `按部门筛选` | 部门区域标题 |
| `userTitle` | `string` | `选择用户` | 用户列表标题 |
| `selectedTitle` | `string` | `已选择` | 已选区域标题 |
| `searchPlaceholder` | `string` | 系统搜索文案 | 搜索框占位文本 |
| `allText` | `string` | `全部用户` | “全部”节点文案 |
| `selectAllText` | `string` | `全选` | 全选按钮文案 |
| `inverseText` | `string` | `反选` | 反选按钮文案 |
| `emptyText` | `string` | `暂无可选用户` | 空列表文案 |

## `users` 数据格式

如果传自定义 `users`，推荐至少包含以下字段：

```php
array(
    array(
        'account'  => 'zhangsan',
        'realname' => '张三',
        'dept'     => 1,
        'avatar'   => '/path/avatar.png'
    )
)
```

兼容字段说明：

- `account` 为空时，会尝试读取 `value` 或 `id`
- `realname` 为空时，会尝试读取 `name` 或 `text`
- `dept` 为空时，会尝试读取 `deptID`
- `avatar` 为空时，会尝试读取 `src`

## `depts` 数据格式

如果传自定义 `depts`，推荐至少包含以下字段：

```php
array(
    array(
        'id'     => 1,
        'parent' => 0,
        'name'   => '研发部',
        'path'   => ',1,'
    )
)
```

兼容字段说明：

- `id` 为空时，会尝试读取 `value`
- `name` 为空时，会尝试读取 `text`

## 表单提交说明

部件会在根节点下维护隐藏表单控件，提交的是选中用户的 `account`。

例如：

```php
userSelector
(
    set::name('selectedUsers'),
    set::value(array('zhangsan', 'lisi'))
);
```

提交时对应字段为：

```html
<input type="hidden" name="selectedUsers[]" value="zhangsan" />
<input type="hidden" name="selectedUsers[]" value="lisi" />
```

## 注意事项

- `value` 和 `existingUsers` 都按用户 `account` 匹配
- `existingUsers` 优先级高于 `value`
- 仅当前渲染出来的可选用户会参与全选 / 反选
- 搜索框有内容时，右侧会显示清空按钮
