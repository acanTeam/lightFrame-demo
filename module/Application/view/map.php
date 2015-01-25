<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/css/theme.css" rel="stylesheet">
<div class="container theme-showcase" role="main">

  <div class="page-header"><h3>应用和相关路由</h3></div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>应用</th>
            <th>代码</th>
            <th>路由</th>
            <th>控制器和方法</th>
            <th>访问</th>
          </tr>
        </thead>
        <?php $i = 1; foreach ($application->routeInfos as $app => $infos) { ?>
        <tbody>
          <?php $j = 1; foreach ($infos as $key => $info) { $url = rtrim($application->domain, '/') . $info['0']; ?>
          <tr>
            <td><?php echo $app . ' ( ' . $i . ' ) '; ?></td>
            <td><?php echo $key . ' ( ' . $j . ' ) '; ?></td>
            <td><?php echo $info['0']; ?></td>
            <td><?php echo $info['1']; ?></td>
            <td><a href='<?php echo $url; ?>' target='_blank'>访问</a></td>
          </tr>
          <?php $j++; $i++; } ?>
        </tbody>
        <?php } ?>
      </table>
    </div>
  </div>
</div> <!-- /container -->
<?php $application->render('common/js_base', array('application' => $application)); ?>
