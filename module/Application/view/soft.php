<header class="jumbotron subhead" id="overview">
  <div class="container">
    <h1>Windows环境下软件汇总</h1>
    <p class="lead">包括系统的安装及初始化；日常使用的软件列表；软件使用过程中出现的问题报备</p>
  </div>
</header>

<div class="container">
  <div class="row">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <?php foreach($infos as $key => $info) { ?>
        <li><a href="#<?php echo $key; ?>"><i class="icon-chevron-right"></i><?php echo $info['name']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="span9">
      <?php foreach ($infos as $key => $info) { ?>
      <section id="what-next">
      <?php echo $application->render('soft/' . $key, array('data' => $info)); ?>
      </section>
      <?php } ?>
    </div>
  </div>
</div>

