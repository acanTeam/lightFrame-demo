<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/css/theme.css" rel="stylesheet">
<div class="container theme-showcase" role="main">

  <div class="page-header"><h3>域名和相关子域</h3></div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>域名</th>
            <th>子域</th>
            <th>本地地址</th>
            <th>线上地址</th>
          </tr>
        </thead>
        <?php $i = 1; foreach ($domains as $domain => $info) { ?>
        <tbody>
          <?php $j = 1; foreach ($info as $subDomain => $subInfo) { $url = 'http://' . $subDomain . '.' . $domain; ?>
          <tr>
            <td><?php echo $domain . ' ( ' . $i . ' ) '; ?></td>
            <td><?php echo $subDomain . ' ( ' . $j . ' ) '; ?></td>
            <td><?php echo "<a href='{$url}.biz' target='_blank'>{$url}.biz</a>"; ?></td>
            <td><?php echo "<a href='{$url}.com' target='_blank'>{$url}.com</a>"; ?></td>
          </tr>
          <?php $j++; $i++; } ?>
        </tbody>
        <?php } ?>
      </table>
    </div>
  </div>
</div> <!-- /container -->
<?php $application->render('common/js_base', array('application' => $application)); ?>
