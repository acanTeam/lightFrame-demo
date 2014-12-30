<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/css/offcanvas.css" rel="stylesheet">
<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
  <div class="container">
    <div class="navbar-header"><a class="navbar-brand" href="<?php echo 'baseUrl'; ?>">UML of PHP</a></div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
      <?php foreach ($infos as $basePath => $info) { if ($basePath == '_files') { continue; } ?>
        <li <?php if ($currentPath == $basePath) echo 'class="active"'; ?>><a href="<?php echo 'url'; ?>"><?php echo $basePath; ?></a></li>
      <?php } ?>
      </ul>
    </div>
  </div>
</div>
<div class="container">
<?php foreach ($files as $key => $info) { ?>
  <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
    <div class="list-group">
    <?php
    foreach ($info as $file) {
      $fileBase = basename($file);
      $url = str_replace($application->configCommon['uploadPath'], $application->configCommon['uploadUrl'], $file);
      echo '<a href="' . $url . '" class="list-group-item" target="_blank">' . $fileBase . '</a>';
    } ?>
    </div>
  </div>
<?php } ?>
</div>
