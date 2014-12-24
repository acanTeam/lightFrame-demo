<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/css/theme.css" rel="stylesheet">
<div class="container theme-showcase" role="main">

  <div class="page-header"><h3>问卷列表</h3></div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>年龄</th>
            <th>工作地址</th>
            <th>地图APP</th>
            <th>参与时间</th>
            <th>IP地址</th>
            <th>问卷环境</th>
            <th>问卷详情</th>
          </tr>
        </thead>
        <?php $i = 1; foreach ($infos as $info) { ?>
        <tbody>
          <tr>
            <td><?php echo $info['id']; ?></td>
            <td><?php echo $info['age']; ?></td>
            <td><?php echo $info['job_address']; ?></td>
            <td><?php echo $info['mapapp']; ?></td>
            <td><?php echo $info['create_time']; ?></td>
            <td><?php echo $info['ip']; ?></td>
            <td><?php echo $info['user_agent']; ?></td>
            <td><?php echo "<a href='{$application->domain}lbs/show?id={$info['id']}' target='_blank'>详情</a>"; ?></td>
          </tr>
        </tbody>
        <?php } ?>
      </table>
    </div>
  </div>
</div> <!-- /container -->
<?php $application->render('common/js_base', array('application' => $application)); ?>
