<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/css/theme.css" rel="stylesheet">
<div class="container theme-showcase" role="main">

  <div class="page-header"><h3>问卷列表</h3></div>
  <div class="row">
    <div class="col-md-12">
      <table class="table">
        <thead>
          <tr>
            <th>题号</th>
            <th>标题</th>
            <th>我觉得很好</th>
            <th>这是必须的</th>
            <th>不知道</th>
            <th>也可以</th>
            <th>我觉得不好</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo '选中次数'; ?></td>
            <td><?php echo '--'; ?></td>
            <td><?php $haveNum1 = isset($questionNums['have'][1]) ? $questionNums['have'][1] : 0; $noNum1 = isset($questionNums['no'][1]) ? $questionNums['no'][1] : 0; echo 'have:' . $haveNum1 . '<br />no:' . $noNum1; ?></td>
            <td><?php $haveNum2 = isset($questionNums['have'][2]) ? $questionNums['have'][2] : 0; $noNum2 = isset($questionNums['no'][2]) ? $questionNums['no'][2] : 0; echo 'have:' . $haveNum2 . '<br />no:' . $noNum2; ?></td>
            <td><?php $haveNum3 = isset($questionNums['have'][3]) ? $questionNums['have'][3] : 0; $noNum3 = isset($questionNums['no'][3]) ? $questionNums['no'][3] : 0; echo 'have:' . $haveNum3 . '<br />no:' . $noNum3; ?></td>
            <td><?php $haveNum4 = isset($questionNums['have'][4]) ? $questionNums['have'][4] : 0; $noNum4 = isset($questionNums['no'][4]) ? $questionNums['no'][4] : 0; echo 'have:' . $haveNum4 . '<br />no:' . $noNum4; ?></td>
            <td><?php $haveNum5 = isset($questionNums['have'][5]) ? $questionNums['have'][5] : 0; $noNum5 = isset($questionNums['no'][5]) ? $questionNums['no'][5] : 0; echo 'have:' . $haveNum5 . '<br />no:' . $noNum5; ?></td>
          </tr>
        <?php $i = 1; foreach ($questions['questions'] as $code => $info) { ?>
          <tr>
            <td><?php echo $code; ?></td>
            <td><?php echo $info['title']; ?></td>
            <td><?php echo $info['1']; ?></td>
            <td><?php echo $info['2']; ?></td>
            <td><?php echo $info['3']; ?></td>
            <td><?php echo $info['4']; ?></td>
            <td><?php echo $info['5']; ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div> <!-- /container -->
<?php $application->render('common/js_base', array('application' => $application)); ?>
