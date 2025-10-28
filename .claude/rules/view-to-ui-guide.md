# ZenTao View页面到UI页面重构指导文档

## 概述

本文档用于指导大模型将ZenTao的传统view页面重构为现代化ui页面。ZenTao的ui页面基于自研的Zin前端框架和ZUI3设计系统，提供了声明式的UI构建方式，具有更好的可维护性、一致性和用户体验。

---
**🚨🚨🚨 重要警告 🚨🚨🚨**

**在开始编写任何代码之前，必须先创建重构分支！**
**任何直接在开发分支上进行页面重构的行为都是严重违规！**
**必须严格按照分支→开发→测试→提交→推送的流程执行！**

---

## ⚠️ 重要：分支开发流程（必须执行）

**任何view到ui页面重构都必须严格按照以下分支流程进行：**

### 第一步：创建重构分支（必须）
1. **检查当前分支**：确认当前处于开发分支（通常是release分支或master分支）
2. **创建重构分支**：基于当前开发分支创建新的重构分支
3. **分支命名规范**：`uirefactor/{ModuleName}/{PageName}/{大模型名称}`
   - 示例：`uirefactor/company/browse/claude`
   - 示例：`uirefactor/user/create/claude`
   - 示例：`uirefactor/project/gantt/claude`
4. **切换到重构分支**：立即切换到新创建的重构分支进行开发

### 分支操作命令示例：
```bash
# 检查当前分支
git branch

# 创建并切换到重构分支
git checkout -b uirefactor/company/browse/claude

# 确认已切换到重构分支
git branch
```

**🚨 警告：如果不创建重构分支，将违反ZenTao开发规范，必须立即停止操作并创建正确的分支！**

## ZenTao前端架构概览

### 1. 目录结构对比

```
module/{moduleName}/
├── view/                          # 传统视图文件
│   ├── {pageName}.html.php       # 传统PHP模板
│   └── ...
├── ui/                            # 现代UI页面
│   ├── {pageName}.html.php       # Zin声明式页面
│   └── ...
├── js/                            # JavaScript文件
│   ├── {pageName}.js             # view页面使用的JS
│   ├── {pageName}.ui.js          # ui页面使用的JS
│   └── ...
└── css/                           # 样式文件
    ├── {pageName}.css            # view页面使用的CSS
    ├── {pageName}.ui.css         # ui页面使用的CSS
    └── ...
```

### 2. Zin框架架构
- **lib/zin/**：Zin框架核心代码
- **lib/zin/wg/**：Zin UI组件库（244个组件）
- **www/js/zui3/**：ZUI3前端框架资源

### 3. ZUI3设计系统
- **统一设计语言**：颜色、字体、间距、阴影等设计令牌
- **响应式设计**：自适应不同设备和屏幕尺寸
- **主题支持**：支持多种主题（default、blue、green等）
- **图标系统**：内置ZentaoIcon字体图标

## Zin组件库详解

ZenTao提供了244个Zin组件，以下是按功能分类的核心组件：

### 表单组件
**核心组件目录：**`lib/zin/wg/form*/`、`lib/zin/wg/input*/`、`lib/zin/wg/picker/`

```php
// 表单面板
formPanel(
    set::title($lang->title),
    // 表单行
    formRow(
        // 表单组
        formGroup(
            set::width('1/2'),
            set::label($lang->field->name),
            // 输入框
            input(
                set::name('fieldName'),
                set::value($defaultValue),
                set::placeholder($lang->placeholder)
            )
        ),
        formGroup(
            set::width('1/2'),
            set::label($lang->field->select),
            // 选择器
            picker(
                set::name('selectField'),
                set::items($options),
                set::value($selectedValue)
            )
        )
    ),
    // 日期选择器
    formRow(
        formGroup(
            set::label($lang->field->date),
            datePicker(
                set::name('dateField'),
                set::value($defaultDate)
            )
        )
    )
);
```

### 数据展示组件
**核心组件目录：**`lib/zin/wg/dtable/`、`lib/zin/wg/tabledata/`

```php
// 数据表格
dtable(
    setID('dataTable'),
    set::cols($columns),
    set::data($tableData),
    set::checkable(true),
    set::sortLink(createLink('module', 'method', 'orderBy={name}_{sortType}')),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);
```

### 布局组件
**核心组件目录：**`lib/zin/wg/toolbar/`、`lib/zin/wg/sidebar/`、`lib/zin/wg/featurebar/`

```php
// 特性栏（标签导航）
featureBar(
    set::current($browseType),
    set::linkParams("browseType={key}"),
    li(searchToggle(set::module('user')))
);

// 工具栏
toolbar(
    btn(
        set::icon('plus'),
        setClass('btn primary'),
        set::url(createLink('module', 'create')),
        $lang->create
    ),
    btnGroup(
        btn(...),
        dropdown(...)
    )
);

// 侧边栏
sidebar(
    moduleMenu(set(array(
        'modules' => $menuData,
        'activeKey' => $activeKey,
        'settingLink' => $settingLink
    )))
);
```

### 交互组件
**核心组件目录：**`lib/zin/wg/btn*/`、`lib/zin/wg/modal*/`、`lib/zin/wg/dropdown/`

```php
// 按钮组
btnGroup(
    btn(
        set::icon('edit'),
        set::url($editLink),
        $lang->edit
    ),
    btn(
        set::icon('trash'),
        setClass('btn danger'),
        setData('confirm', $lang->confirmDelete),
        set::url($deleteLink),
        $lang->delete
    )
);

// 模态框触发器
modalTrigger(
    set::url(createLink('module', 'create')),
    set::width('800px'),
    btn(
        set::icon('plus'),
        $lang->create
    )
);
```

### 完整组件参考

由于Zin组件库包含244个组件，完整列表请参考：
- **组件源码目录**：`lib/zin/wg/`
- **在线文档**：https://openzui.com/zin
- **常用组件快速查找**：
  ```bash
  # 在项目根目录执行
  find lib/zin/wg -name "*.php" -type f | grep -E "(btn|form|input|table|modal|menu|nav)" | sort
  ```

## 重构实施指南

### 第一步：分析现有view页面

**必须在重构分支上执行以下操作：**

```bash
# 确认当前在重构分支
git branch --show-current

# 分析目标模块
ls -la module/{moduleName}/view/
ls -la module/{moduleName}/ui/
ls -la module/{moduleName}/js/
ls -la module/{moduleName}/css/
```

### 第二步：HTML结构重构

**传统view页面结构：**
```php
<?php
include '../../common/view/header.html.php';
js::set('variable', $value);
?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($link, $text, '', "class='btn'");?>
  </div>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='main-col'>
    <form method='post' action='<?php echo $this->createLink()?>'>
      <table class='table'>
        <tr>
          <th><?php echo $lang->field?></th>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
```

**现代ui页面结构：**
```php
<?php
declare(strict_types=1);
namespace zin;

jsVar('variable', $value);

featureBar(
    set::current($browseType),
    set::linkParams("browseType={key}")
);

toolbar(
    btn(
        set::icon('plus'),
        setClass('btn primary'),
        set::url($createLink),
        $lang->create
    )
);

dtable(
    setID('dataTable'),
    set::cols($columns),
    set::data($tableData),
    set::checkable(true)
);

render();
```

### 第三步：JavaScript文件重构

**重构命名：**
- `{pageName}.js` → `{pageName}.ui.js`

**传统view JavaScript：**
```javascript
$(document).ready(function(){
    $('#form').submit(function(){
        // 处理表单提交
        return true;
    });

    $('.btn-delete').click(function(){
        return confirm('确认删除？');
    });
});
```

**现代ui JavaScript：**
```javascript
/**
 * 表单提交处理
 */
function submitForm()
{
    // 使用现代JavaScript
    const formData = new FormData(document.getElementById('form'));
    // 处理逻辑
    return true;
}

/**
 * 删除确认
 */
function confirmDelete()
{
    return confirm(window.confirmDelete || '确认删除？');
}
```

### 第四步：CSS样式重构

**重构命名：**
- `{pageName}.css` → `{pageName}.ui.css`

**样式适配ZUI3：**
```css
/* 使用ZUI3变量和类名 */
.custom-panel {
    background: var(--color-canvas);
    border: 1px solid var(--color-border);
    border-radius: var(--space-1);
    padding: var(--space-4);
}

/* 响应式设计 */
@media (max-width: 768px) {
    .custom-panel {
        padding: var(--space-2);
    }
}
```

## 测试和验证

### 第一步：语法验证（在重构分支上）
```bash
# 检查PHP语法
php -l module/{moduleName}/ui/{pageName}.html.php

# 检查JavaScript语法
node -c module/{moduleName}/js/{pageName}.ui.js 2>/dev/null || echo "JS语法检查需要Node.js"
```

### 第二步：功能测试
1. 在浏览器中访问重构后的页面
2. 验证所有原有功能是否正常
3. 检查响应式布局在不同设备上的表现
4. 验证表单提交、数据加载等交互功能

### 第三步：样式检查
1. 确认页面符合ZUI3设计规范
2. 检查颜色、字体、间距是否一致
3. 验证暗色主题兼容性

## 提交和推送流程

**重构完成后按照以下步骤提交代码（必须在重构分支上执行）：**

### 第一步：验证当前分支
```bash
# 确认当前在重构分支上，不在开发分支上
git branch --show-current
# 应该显示：uirefactor/{moduleName}/{pageName}/claude
```

### 第二步：添加重构文件
```bash
# 仅添加重构相关文件
git add module/{moduleName}/ui/{pageName}.html.php
git add module/{moduleName}/js/{pageName}.ui.js
git add module/{moduleName}/css/{pageName}.ui.css
# 注意：不要添加指导文档文件到重构分支！指导文档应保留在开发分支上
```

### 第三步：提交代码
```bash
git commit -m "* [ui] Refactor {ModuleName} {PageName} from view to ui

🤖 Generated with [Claude Code](https://claude.ai/code)

Co-Authored-By: Claude <noreply@anthropic.com>"
```

### 第四步：推送到远程仓库
```bash
git push
```

### 第五步：切换回开发分支
```bash
git checkout {原开发分支名}  # 如 release/21.7.5 或 master
```

**⚠️ 提交信息规范：**
- 必须以符号开头：`+`（新增）、`-`（删除）、`*`（修改）
- 格式：`{symbol} [ui] {描述}`
- 必须使用英文描述
- 必须包含Claude Code标识
- 必须包含Co-Authored-By信息

## 常见问题和解决方案

### 页面无法正常显示
**问题：** 重构后页面显示空白或报错

**解决方案：**
1. 检查文件头部是否包含正确声明
   ```php
   <?php
   declare(strict_types=1);
   namespace zin;
   ```
2. 确认所有组件语法正确
3. 检查是否调用了`render()`函数

### 样式显示异常
**问题：** 页面样式与预期不符

**解决方案：**
1. 确认CSS文件命名为`.ui.css`
2. 检查是否正确使用ZUI3样式变量
3. 验证响应式布局代码

### JavaScript功能失效
**问题：** 重构后JavaScript交互不工作

**解决方案：**
1. 检查文件命名是否为`.ui.js`
2. 确认事件绑定方式是否正确
3. 验证DOM选择器是否匹配新的结构

## 验证检查清单

重构完成后，请逐项确认：

### 🔴 必须项（缺一不可）
- [ ] **已创建并切换到正确的uirefactor分支**
- [ ] **当前不在master/release等主分支上工作**
- [ ] **所有重构文件都在分支上提交**

### 🟡 文件结构要求
- [ ] ui目录下创建了对应的.html.php文件
- [ ] JavaScript文件命名为.ui.js
- [ ] CSS文件命名为.ui.css
- [ ] 文件头部包含正确的namespace声明

### 🟢 技术实现要求
- [ ] HTML使用Zin声明式语法
- [ ] 正确使用Zin组件（参考lib/zin/wg/目录）
- [ ] JavaScript代码适配新的DOM结构
- [ ] CSS样式符合ZUI3设计规范
- [ ] 页面功能完全正常
- [ ] 响应式布局正常工作

### 🔵 提交流程要求
- [ ] 代码通过语法检查
- [ ] 提交信息使用英文并符合规范格式
- [ ] 提交信息包含必要的符号前缀（* [ui]）
- [ ] 提交信息包含Claude Code标识
- [ ] 分支已推送到远程仓库
- [ ] 工作完成后已切回开发分支

## 注意事项

1. **严格遵循分支开发流程**：任何违反分支开发规范的行为都是不可接受的
2. **保持功能完整性**：重构不能改变原有功能，只能改善实现方式
3. **遵循设计规范**：必须符合ZUI3设计系统的视觉和交互标准
4. **充分测试验证**：确保重构后的页面在各种场景下都能正常工作
5. **组件优先原则**：优先使用Zin提供的标准组件，避免自定义实现
6. **性能优化**：利用声明式语法和组件化架构提升页面性能

**通过严格遵循本指南，可以确保view到ui页面重构工作的规范性、一致性和高质量。**