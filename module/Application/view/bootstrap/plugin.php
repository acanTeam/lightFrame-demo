<?php
$pluginInfos = array(
    'scrollup' => array(
        'name' => '返回顶部',
        'description' => '',
        'version' => '2.4.0',
        'homePage' => 'https://github.com/markgoodyear/scrollup',
    ),
);

$frameInfos = array(
    'bootstrap' => array(
        'name' => 'Bootstrap',
        'description' => '前端的标准架构',
        'version' => '3.3.1',
        'homePage' => 'https://github.com/markgoodyear/scrollup',
    ),
);

$softInfos = array(
    'system' => array(
        'name' => '系统相关软件',
        'description' => '系统安装软件，或跟系统相关的一些工具',
        'infos' => $systemSoftInfos,
    ),
    'develop' => array(
        'name' => '开发相关软件',
        'description' => '开发相关的软件和工具',
        'infos' => $developSoftInfos,
    ),
    's360' => array(
        'name' => '360软件管家',
        'description' => '直接由360软件管家负责维护和管理的软件',
        'infos' => $s360SoftInfos,
    ),
    'other' => array(
        'name' => '其他软件',
        'description' => '',
        'infos' => $otherSoftInfos,
    )
);
?>
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
        <h2><?php echo $softInfo['name']; ?></h2>
        <p><?php echo $softInfo['description']; ?></p>
        <table class="table table-bordered table-striped">
          <thead>
            <?php if (in_array($key, array('develop', 'system'))) { ?>
            <tr><th>软件</th><th>当前使用的版本</th><th>官网</th></tr>
            <?php } else { ?>
            <tr><th  colspan="4">软件列表</th></tr>
            <?php } ?>
          </thead>
          <tbody>
            <?php if (in_array($key, array('develop', 'system'))) { foreach($softInfo['infos'] as $name => $info) { ?>
            <tr>
              <td><?php echo $name; ?></td>
              <td><?php echo $info['version']; ?></td>
              <td><?php echo "<a href='{$info['officeWeb']}' target='_blank'>官网</a>"; if (isset($info['download'])) { echo "<a href='{$info['download']}' target='_blank'>下载</a>"; } ?></td>
            </tr>
            <?php } } else { $i = 0; foreach ($softInfo['infos'] as $soft) { if ($i / 4 == 0) { echo '<tr>'; } ?>
              <td><?php echo $soft; ?></td>
            <?php if ($i % 4 == 3) { echo '</tr>'; } $i++; } if ($i % 4 !=  3) { echo '</tr>'; } } ?>
          </tbody>
        </table>
      </section>
      <?php } ?>
    </div>
  </div>
</div>

