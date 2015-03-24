<link href="<?php echo $application->configCommon['assetUrl']; ?>FontAwesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/site.min.css?v3" rel="stylesheet">
<script>
  var _hmt = _hmt || [];
</script>

<?php echo $navbarContent; ?> 
<div class="jumbotron masthead">
  <div class="container">
    <h1>文档系统</h1>
    <h2>技术相关知识汇总，文档是markdown形式的文件，通过php解析为html在线阅读</h2>
    <!--<p class="masthead-button-links"><a class="btn btn-lg btn-primary btn-shadow" href="" target="_blank" role="button"></a></p>-->
    <ul class="masthead-links">
      <li><a href="http://github.com/acanstudio/docs" role="button">Github版本库</a></li>
    </ul>
  </div>
</div>

<div class="container projects">
  <div class="projects-header page-header">
    <h2>文档列表</h2>
    <p><?php //echo $description; ?></p>
  </div>
  <div class="row">

    <?php $assetUrlNull = $application->configCommon['assetUrl'] . 'bootstrap/demo/images/null.jpg'; foreach ($infos as $key => $info) { $picture = $application->configCommon['uploadUrl'] . 'acanstudio/docs/' . $key . '.png'; ?>
  	<div class="col-sm-6 col-md-4 col-lg-3 ">
      <div class="thumbnail">
        <a href="<?php echo $application->domain . 'document/' . $key; ?>" title="<?php echo $key; ?>" target="_blank">
        <img class="lazy" src="<?php echo $assetUrlNull; ?>" style="height: 150px; width: 300px;" data-src="<?php echo $picture; ?>" alt="<?php echo $key; ?>">
        </a>
        <div class="caption">
          <p><?php echo $info['description']; ?></p>
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

