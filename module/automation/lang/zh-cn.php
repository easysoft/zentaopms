<?php
/**
 * The automation module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     automation
 * @version     $Id: zh-cn.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->automation->common = '自动化测试';
$lang->automation->index  = '首页';

$lang->automation->description = '自动化测试解决方案';
$lang->automation->ztf         = 'ZTF自动化测试管理框架';
$lang->automation->zendata     = 'ZenData数据生成工具';

$lang->automation->details = <<<EOF
<article class="article">
  <header>
    <h1 class="text-center">自动化测试解决方案</h1>
    <div class="abstract">
      <p>
      摘要：欢迎使用禅道提供的自动化解决方案，本次为大家带来了两款工具。Zentao Testing Framework，简称ZTF，是一款开源自动化测试管理框架。
      ZenData可以用于手工测试场景下面测试数据的准备，也可以用于自动化测试脚本里面的数据生成和解析。
      </p>
    </div>
  </header>

  <section class="content">
    <h2>ZTF自动化测试管理框架</h2>
    <p>
      Zentao Testing Framework，简称ZTF，是一款开源自动化测试管理框架。
      和市面上已有的自动化测试框架相比，ZTF更聚焦于自动化测试的管理功能。
      ZTF提供了自动化测试脚本的定义、管理、驱动、执行结果的回传、bug的创建以及和其他自动化测框架的集成。
      ZTF使用go语言开发，可以支持各种平台。ZTF支持常见的编程语言，您可以选择您喜欢用的语言来开发自动化测试脚本。
    </p>
    <div class="section">
      <div class="text-left" style="padding:25px 15px 10px 15px;">
        <p>
          <a href="https://ztf.im/index.html" class="btn btn-primary bg-primary btn-lg" target="_blank">禅道ZTF官网</a> &nbsp; &nbsp;
          <a href="https://ztf.im/ztf.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZTF框架下载</a> &nbsp; &nbsp;
          <a href="https://ztf.im/book/ztf/ztf-about-26.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZTF框架手册</a>
        </p>
        <div style="margin-top:15px;" class="text-muted">
          <p>
            我们还提供了QQ群和微信群的交流方式，官方QQ技术支持响应时间为：上午9:00—11:30，下午13:00—17:30（周六周日，节假日休息）
          </p>
          <p>
            软件测试交流群： 522506766  (添加客服微信： easycorp666，可邀请您加入软件测试微信群)
          </p>
        </div>
      </div>
    </div>
    <h2>ZenData数据生成工具</h2>
    <p>
      ZenData主要两大功能：数据生成和数据解析。通过一个配置文件，可以使用ZenData生成您想要的各种数据。
      同样也可以对某一个数据文件，指定其数据类型定义的配置文件，完成到结构化数据的解析。
      ZenData可以用于手工测试场景下面测试数据的准备，也可以用于自动化测试脚本里面的数据生成和解析。还可以一键生成海量数据用于性能和压力测试。
    </p>
    <div class="section">
      <div class="text-left" style="padding:25px 15px 10px 15px;">
        <p>
          <a href="https://www.zendata.cn/index.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZenData官网</a> &nbsp; &nbsp;
          <a href="https://www.zendata.cn/download.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZenData下载</a> &nbsp; &nbsp;
          <a href="https://www.zendata.cn/book/zendata/why-zendata-115.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZenData手册</a>
        </p>
        <div style="margin-top:15px;" class="text-muted">
          <p>
            我们还提供了QQ群和微信群的交流方式，官方QQ技术支持响应时间为：上午9:00—11:30，下午13:00—17:30（周六周日，节假日休息）
          </p>
          <p>
            软件测试交流群： 522506766  (添加客服微信： easycorp666，可邀请您加入软件测试微信群)
          </p>
        </div>
      </div>
    </div>
  </section>
</article>
EOF;
$lang->automation->ztfDetails = <<<EOF
<article class="article">
  <header>
    <h1 class="text-center">ZTF自动化测试管理框架</h1>
    <div class="abstract">
      <p>
        摘要：Zentao Testing Framework，简称ZTF，是一款开源自动化测试管理框架。
      </p>
    </div>
  </header>

  <section class="content">
    <h4>一、ZTF是什么</h4>
    <p>
      Zentao Testing Framework，简称ZTF，是一款开源自动化测试管理框架。
      和市面上已有的自动化测试框架相比，ZTF更聚焦于自动化测试的管理功能。
      ZTF提供了自动化测试脚本的定义、管理、驱动、执行结果的回传、bug的创建以及和其他自动化测框架的集成。
      ZTF使用go语言开发，可以支持各种平台。ZTF支持常见的编程语言，您可以选择您喜欢用的语言来开发自动化测试脚本。
    </p>
    <h4>二、为什么来做ZTF</h4>
    <p>
      市面上已经有很多自动化测试框架，我们为什么还要来做ZTF呢？ZTF主要是解决测试管理的问题。
      目前市面上的自动化测试框架主要分为两大类，一类是单元测试框架，一类是某种领域的自动化测试框架。
      单元测试框架是跟各个语言绑定的，比如cppunit, phpunit等。第二类框架以selenium为代表，可以用来做web的自动化测试。
      也还有做GUI或者手机应用乃至游戏等场景的自动化测试的框架。
    </p>
    <p>
      这就产生了一个问题，我们需要将这些自动化测试框架的测试脚本统一管理起来。
      每一个脚本都可以和测试管理系统里面的一个用例进行关联，脚本里面的步骤信息和管理系统里面的用例信息可以互相同步。
      测试执行的结果可以反映到测试管理系统中，失败的测试脚本可以创建bug。
      为此我们开发了ZTF测试管理框架。
    </p>
    <h4>三、ZTF和其他自动化测试框架的关系</h4>
    <p>
      ZTF和其他的自动化测试框架是互相合作的关系。
      您可以继续使用之前的自动化测试框架来进行脚本的开发，只需要通过注释的方式加入几个标签就可以转换成ztf的自动化测试脚本。
    </p>
    <h4>四、ZTF自动化测试框架的特点</h4>
    <ol>
      <li>简单：ZTF的语法标签和规则都很简单，一看就会，很容易上手。</li>
      <li>跨平台：ZTF使用GO语言开发，跨平台，只有一个可执行文件，就可以运行。</li>
      <li>跨语言：ZTF支持常见的编程语言，你喜欢用什么就用什么。</li>
      <li>跨框架：ZTF可以和市面上常见的单元测试框架、常见的自动化测试框架都可以很好的集成。</li>
      <li>工程化：使用ZTF可以真正达到工程化的自动化测试，可以大批量大规模的进行自动化测试的管理和执行。</li>
      <li>跨场景：借助于其他框架，ZTF可以用来做单元测试、接口测试、web界面测试、GUI界面测试、APP测试等多种场景。</li>
    </ol>
    <div class="section">
      <div class="text-left" style="padding:25px 15px 10px 15px;">
        <p>
          <a href="https://ztf.im/index.html" class="btn btn-primary bg-primary btn-lg" target="_blank">禅道ZTF官网</a> &nbsp; &nbsp;
          <a href="https://ztf.im/ztf.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZTF框架下载</a> &nbsp; &nbsp;
          <a href="https://ztf.im/book/ztf/ztf-about-26.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZTF框架手册</a>
        </p>
        <div style="margin-top:15px;" class="text-muted">
          <p>
            我们还提供了QQ群和微信群的交流方式，官方QQ技术支持响应时间为：上午9:00—11:30，下午13:00—17:30（周六周日，节假日休息）
          </p>
          <p>
            软件测试交流群： 522506766  (添加客服微信： easycorp666，可邀请您加入软件测试微信群)
          </p>
        </div>
      </div>
    </div>
  </section>
</article>
EOF;

$lang->automation->zendataDetails = <<<EOF
<article class="article">
  <header>
    <h1 class="text-center">ZenData数据生成工具</h1>
    <div class="abstract">
      <p>
        摘要：ZenData使用YAML来作为数据类型配置文件实现了数据的生成和解析。
        ZenData可以用于手工测试场景下面测试数据的准备，也可以用于自动化测试脚本里面的数据生成和解析。
        还可以一键生成海量数据用于性能和压力测试。
      </p>
    </div>
  </header>

  <section class="content">
    <h4>一、ZenData的实现原理</h4>
    <p>
      ZenData使用YAML来作为数据类型配置文件。对于每一种类型的数据来讲，我们都可以将其拆分成若干的字段。
      比如我们要测试一个用户的订单表，会有订单的id，用户id或者账户名，订单的名称，订单的金额，订单的时间，订单状态等等字段。
      我们可以针对每一个字段配置其取值的范围。比如订单id，我可以定义为从1到10000，用户id可以是1-100。
      这样每一个字段都有了一个取值范围列表。当ZenData来生成数据的时候，从每一个字段里面依次取一个值，将其拼接在一起，就形成了一条记录。
      大家可以想象成每个字段都是一个转盘，按照自己的频率进行旋转，每一次旋转都从中取出一个值，和其他的字段进行组合。
    </p>
    <p>上面说的是最基本的的工作原理，ZenData还提供了步长、随机、循环、引用等多种定义方式，我们会在接下来的手册里面展开讲。</p>
    <h4>二、ZenData的用途</h4>
    <p>
      ZenData主要两大功能：数据生成和数据解析。通过一个配置文件，可以使用ZenData生成您想要的各种数据。
      同样也可以对某一个数据文件，指定其数据类型定义的配置文件，完成到结构化数据的解析。
      ZenData可以用于手工测试场景下面测试数据的准备，也可以用于自动化测试脚本里面的数据生成和解析。还可以一键生成海量数据用于性能和压力测试。
    </p>
    <h4>三、ZenData主要的特点</h4>
    <ol>
      <li>简单无依赖，只有一个可执行文件，即可满足命令行生成和HTTP接口两种数据生成服务。</li>
      <li>使用配置文件来生成数据，使用人员不需要有开发知识，即可上手应用。</li>
      <li>提供了功能强大的语法，分组、区间、步长、循环、随机、格式化和前后缀等，配置灵活性极强。</li>
      <li>支持从文本文件中读取数据，方便用户对字段取值进行精确控制。</li>
      <li>提供了Excel表格数据的标准SQL查询接口，使用更加灵活。</li>
      <li>使用预制的序列（ranges）、实例（instances）、配置（config）对定义进行复用，以解决复杂数据格式的定义。</li>
      <li>语法支持继承和扩展，为定义文件间的复用提供方便。</li>
      <li>可以反向解析数据，可以对程序的输出进行解析，方便自动化测试脚本进行比对。</li>
      <li>发行包內置了基础业务数据的定义文件（不断完善中）。</li>
      <li>提供了HTTP接口数据生成服务，各种语言都可以方便调用。</li>
    </ol>
    <div class="section">
      <div class="text-left" style="padding:25px 15px 10px 15px;">
        <p>
          <a href="https://www.zendata.cn/index.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZenData官网</a> &nbsp; &nbsp;
          <a href="https://www.zendata.cn/download.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZenData下载</a> &nbsp; &nbsp;
          <a href="https://www.zendata.cn/book/zendata/why-zendata-115.html" class="btn btn-primary bg-primary btn-lg" target="_blank">ZenData手册</a>
        </p>
        <div style="margin-top:15px;" class="text-muted">
          <p>
            我们还提供了QQ群和微信群的交流方式，官方QQ技术支持响应时间为：上午9:00—11:30，下午13:00—17:30（周六周日，节假日休息）
          </p>
          <p>
            软件测试交流群： 522506766  (添加客服微信： easycorp666，可邀请您加入软件测试微信群)
          </p>
        </div>
      </div>
    </div>
  </section>
  </section>
</article>
EOF;
