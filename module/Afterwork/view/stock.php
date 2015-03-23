<link href="<?php echo $application->configCommon['assetUrl']; ?>FontAwesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/site.min.css?v3" rel="stylesheet">
<script>
  var _hmt = _hmt || [];
</script>

<?php echo $navbarContent; ?> 
<div class="jumbotron masthead">
  <div class="container">
    <h2>投资相关知识汇总</h2>
  </div>
</div>

<div class="container projects">
  <div class="projects-header page-header">
    <h2>知识体系汇总</h2>
  </div>
  <div class="row">

    <?php $assetUrlNull = $application->configCommon['assetUrl'] . 'bootstrap/demo/images/null.jpg'; foreach ($infos as $key => $info) { $picture = $application->configCommon['uploadUrl'] . 'acanstudio/stock/' . $key . '.png'; ?>
  	<div class="col-sm-6 col-md-4 col-lg-3 ">
      <div class="thumbnail">
        <a href="<?php echo $application->domain . 'stock/listinfo/' . $key; ?>" title="<?php echo $key; ?>" target="_blank">
        <img class="lazy" src="<?php echo $assetUrlNull; ?>" width="300" height="150" data-src="<?php echo $picture; ?>" alt="<?php echo $info['title']; ?>">
        </a>
        <div class="caption">
          <p><?php echo $info['path']; ?></p>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
</div>

<?php $application->render('common/footer', array('application' => $application)); ?>
<?php $application->render('common/js_base', array('application' => $application)); ?>

<script src="<?php echo $application->configCommon['assetUrl']; ?>jquery_plugin/jquery.unveil.min.js"></script>
<script src="<?php echo $application->configCommon['assetUrl']; ?>jquery_plugin/jquery.scrollUp.min.js"></script>
<script src="<?php echo $application->configCommon['assetUrl']; ?>jquery_plugin/toc.min.js"></script>
<script src="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/js/bootcss.site.js"></script>

