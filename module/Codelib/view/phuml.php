<header class="jumbotron subhead" id="overview">
  <div class="container">
    <h1>PHP相关的UML图</h1>
    <p class="lead">包括ZF2、PHPUnit、Composer等开源项目的UML图，PHP内置类库的UML图，如SPL等；其他PHP代码的UML图；这些图都是由phUML工具生成</p>
  </div>
</header>

<div class="container">
  <div class="row">
    <div class="span3 bs-docs-sidebar">
      <ul class="nav nav-list bs-docs-sidenav">
        <?php foreach($paths as $path) { ?>
        <li><a href="#<?php echo $path; ?>"><i class="icon-chevron-right"></i><?php echo $path; ?></a></li>
        <?php } ?>
      </ul>
    </div>
    <div class="span9">
      <?php foreach ($paths as $path) { ?>
      <section id="<?php echo $path; ?>">
        <h2><?php echo $path; ?></h2>
        <table class="table table-bordered table-striped">
          <thead>
            <tr><th  colspan="4">UML图列表</th></tr>
          </thead>
          <tbody>
<?php
$i = 0; 
foreach ($files[$path] as $file) { 
    if ($i / 4 == 0) { 
        echo '<tr>'; 
    } 
    $url = str_replace($application->configCommon['uploadPath'], $application->configCommon['uploadUrl'], $file);
    echo '<td><a href="' . $url . '" class="list-group-item" target="_blank">' . basename($file) . '</a></td>';
    if ($i % 4 == 3) { 
        echo '</tr>'; 
    } 
    $i++; 
} 
if ($i % 4 !=  3) { 
    echo '</tr>'; 
} 
?>
          </tbody>
        </table>

      </section>
      <?php } ?>
    </div>
  </div>
</div>
