<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo $application->domain; ?>">首页</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      <?php foreach ($navbarInfos as $menu) { ?>
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
