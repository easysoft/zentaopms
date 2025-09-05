# 禅道PMS项目上下文

## 交互
始终用中文回复。

## 项目概述

禅道PMS（项目管理系统）是由禅道软件（青岛）有限公司开发的开源项目管理软件。它是中国第一个开源项目管理软件，旨在覆盖从产品和项目管理到质量管理、文档管理、组织管理和办公管理的主要项目管理流程。

### 主要特性

- 基于禅道PHP框架构建
- 支持多种项目管理方法论：Scrum、瀑布和看板
- 全面的功能包括产品管理、项目管理、质量管理、文档管理
- 多语言支持（中文、英文、德语、法语等）
- 丰富的模块包括数据可视化、度量、DevOps、文档资产管理、自动化测试
- 版本21.7.5（根据VERSION文件）

### 架构

禅道采用模块化MVC架构：
- **框架**：位于`/framework`的自定义PHP框架
- **模块**：位于`/module`的功能模块（如产品、项目、执行、缺陷、任务等）
- **数据库**：基于MySQL，模式在`/db/zentao.sql`中定义
- **Web界面**：位于`/www`的基于PHP的Web应用程序
- **配置**：位于`/config`的集中配置

## 项目结构

```
zentaopms/
├── api/              # API相关文件
├── bin/              # 可执行二进制文件/脚本
├── config/           # 配置文件
├── db/               # 数据库模式文件
├── doc/              # 文档
├── extension/        # 扩展模块
├── framework/        # 核心框架文件
├── lib/              # 库文件
├── misc/             # 杂项文件
├── module/           # 应用模块
├── sdk/              # 软件开发工具包
├── tmp/              # 临时文件
├── www/              # Web根目录
└── README.md         # 项目文档
```

## 构建和运行

### 先决条件

- PHP 8.1或更高版本
- MySQL数据库
- Apache或Nginx Web服务器

### 构建过程

项目使用Makefile进行构建：

1. **清理构建环境**：`make clean`
2. **构建应用程序**：`make`或`make all`
3. **创建包**：`make package`
4. **创建分发版**：`make pms`

构建过程：
- 将必要文件复制到`zentaopms`目录
- 处理和压缩JavaScript/CSS资源
- 设置适当权限
- 创建分发包（ZIP、tar.xz）

### 安装

1. 将分发包解压到Web服务器目录
2. 在`config/my.php`中配置数据库设置（从`config/config.php`复制）
3. 访问Web界面并按照安装向导操作
4. 如果未安装，系统将自动重定向到`install.php`

### 开发环境运行

1. 将源代码放置在Web服务器的文档根目录中
2. 配置Web服务器以服务PHP文件
3. 确保以下目录可写：
   - `tmp/`
   - `www/data/`
   - `config/`
   - `extension/`

### 生产环境运行

1. 使用`make pms`构建分发包
2. 解压到生产服务器
3. 设置目录的适当权限
4. 在`config/my.php`中配置数据库连接
5. 通过Web浏览器访问以完成安装

## 配置

### 主配置

主配置在`config/config.php`中，重要设置包括：

- 数据库连接设置
- 请求路由配置
- 语言和主题设置
- 安全设置
- 文件上传限制

### 自定义配置

创建`config/my.php`来覆盖默认设置：
- 从`config/config.php`复制
- 修改数据库连接设置
- 根据需要调整其他配置

### 数据库配置

数据库表在`config/zentaopms.php`中定义为常量：
- 表前缀：默认为`zt_`
- 超过100个表用于不同功能
- 模式在`db/zentao.sql`中定义

## 开发规范

### 代码结构

- 遵循MVC模式，包含模型、视图和控制器
- 模块组织在`/module/{module}/`目录中
- 每个模块通常包含：
  - `model.php`：业务逻辑
  - `control.php`：控制器动作
  - `view/`：模板文件
  - `lang/`：语言文件
  - `js/`：模块特定的JavaScript
  - `css/`：模块特定的CSS

### 路由

- 默认路由：`index.php?m={module}&f={method}`
- PATH_INFO路由：`index.php/{module}-{method}`
- 可在`config/routes.php`中自定义API路由

### 数据库访问

- 使用自定义DAO（数据访问对象）模式
- 定义在`lib/base/dao/`中
- 示例：`$this->dao->select('*')->from(TABLE_USER)->where('id')->eq($id)->fetch()`

### 测试

- 测试位于模块目录下的`test/`中

## 主要模块

禅道具有广泛的模块结构：
- **产品管理**：product、story、productplan、release
- **项目管理**：project、execution、task、build
- **质量管理**：bug、testcase、testtask、testsuite
- **文档管理**：doc、doclib
- **组织管理**：company、dept、user、group
- **DevOps**：repo、job、compile、mr
- **报表**：report、pivot、chart
- **管理**：admin、custom、extension

## API和集成

- 通过`api.php`提供RESTful API
- Webhooks支持集成
- 支持GitLab、Gogs、Gitea、Jenkins、SonarQube
- Xuanxuan集成用于消息传递

## 安全

- 输入验证和过滤
- XSS防护
- CSRF防护
- 通过DAO防止SQL注入
- 文件上传限制

## 国际化

- 多语言支持，语言文件在`/module/{module}/lang/`中
- 支持的语言：简体中文、繁体中文、英文、德语、法语、越南语、日语、西班牙语、葡萄牙语
- 语言选择可在用户偏好中配置
