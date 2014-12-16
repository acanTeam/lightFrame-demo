<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php if (isset($navbarInfos['index']['url'])) { echo $navbarInfos['index']['url']; } ?>">
        <?php if (isset($navbarInfos['index']['name'])) { echo $navbarInfos['index']['name']; } ?>
      </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      <?php foreach ($navbarInfos['menus'] as $menu) { ?>
        <?php if (!isset($menu['menus'])) { ?>
        <li ><a href="<?php echo $menu['url']; ?>"><?php echo $menu['name']; ?></a></li>
        <?php } else { ?>
        <li class="dropdown">
          <a href="javascript:void();" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $menu['name']; ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php foreach ($menu['menus'] as $subMenu) { ?>
            <li><a href="<?php echo $subMenu['url']; ?>"><?php echo $subMenu['name']; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
      <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<!--
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand hidden-sm" href="http://www.bootcss.com">Bootstrap中文网</a>
        </div>
        <div class="navbar-collapse collapse" role="navigation">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Bootstrap2中文文档<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <a href="http://v2.bootcss.com/getting-started.html" target="_blank">起步</a>
                </li>
                <li>
                  <a href="http://v2.bootcss.com/scaffolding.html" target="_blank">脚手架</a>
                </li>
                <li>
                  <a href="http://v2.bootcss.com/base-css.html" target="_blank">基本CSS样式</a>
                </li>
                <li>
                  <a href="http://v2.bootcss.com/components.html" target="_blank">组件</a>
                </li>
                <li>
                  <a href="http://v2.bootcss.com/javascript.html" target="_blank">JavaScript插件</a>
                </li>
                <li>
                  <a href="http://v2.bootcss.com/customize.html" target="_blank">定制</a>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Bootstrap3中文文档<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <a href="http://v3.bootcss.com/getting-started/" target="_blank">起步</a>
                </li>
                <li>
                  <a href="http://v3.bootcss.com/css/" target="_blank">CSS</a>
                </li>
                <li>
                  <a href="http://v3.bootcss.com/components/" target="_blank">组件</a>
                </li>
                <li>
                  <a href="http://v3.bootcss.com/javascript/" target="_blank">JavaScript插件</a>
                </li>
                <li>
                  <a href="http://v3.bootcss.com/customize/" target="_blank">定制</a>
                </li>
              </ul>
            </li>
            <li><a href="/p/lesscss/" target="_blank" onclick="_hmt.push(['_trackEvent', 'navbar', 'click', 'less'])">Less 教程</a></li>
            <li><a href="http://jquery.bootcss.com/" target="_blank" onclick="_hmt.push(['_trackEvent', 'navbar', 'click', 'jquery'])">jQuery API</a></li>
            <li><a href="http://expo.bootcss.com" target="_blank" onclick="_hmt.push(['_trackEvent', 'navbar', 'click', 'expo'])">网站实例</a></li>
            <li><a href="http://job.bootcss.com" target="_blank" onclick="_hmt.push(['_trackEvent', 'navbar', 'click', 'job'])">高薪工作</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right hidden-sm">
            <li><a href="/about/" onclick="_hmt.push(['_trackEvent', 'navbar', 'click', 'about'])">关于</a></li>
          </ul>
        </div>
      </div>
    </div>
-->
