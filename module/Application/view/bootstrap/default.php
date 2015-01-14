<link href="<?php echo $application->configCommon['assetUrl']; ?>FontAwesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/site.min.css?v3" rel="stylesheet">
<script>
  var _hmt = _hmt || [];
</script>

<?php echo $navbarContent; ?> 
<div class="jumbotron masthead">
  <div class="container">
    <h1>Bootstrap</h1>
    <h2>彪悍的前端开发框架，美轮美奂的前端触手可及。</h2>
    <!--<p class="masthead-button-links"><a class="btn btn-lg btn-primary btn-shadow" href="" target="_blank" role="button"></a></p>
    <ul class="masthead-links">
      <li><a href="" role="button"></a></li>
    </ul>-->
  </div>
</div>

<!--<div class="bc-social">
  <div class="container">
    <ul class="bc-social-buttons">
      <li class="social-qq"><i class="fa fa-qq"></i>Text</li>
      <li class="social-forum"><a class="" href="" title="" target="_blank"><i class="fa fa-comments"></i>Text</a></li>
      <li class="social-weibo"><a href="" title="" target="_blank"><i class="fa fa-weibo"></i> Text</a></li>
    </ul>
  </div>
</div>-->
<?php
$title = $method == 'demo' ? 'Bootstrap官方示例' : 'Bootstrap典型案例';
$description = $method == 'demo' ? 'Bootstrap入门和提升的捷径' : 'Bootstrap的典型应用，结合jquery的插件，前端从此简单而优雅';
?>
<div class="container projects">
  <div class="projects-header page-header">
    <h2><?php echo $title; ?></h2>
    <p><?php echo $description; ?></p>
  </div>
  <div class="row">

    <?php $assetUrlNull = $application->configCommon['assetUrl'] . 'bootstrap/demo/images/null.jpg'; foreach ($infos as $key => $info) { $picture = $application->configCommon['assetUrl'] . 'bootstrap/demo/picture/' . $key . '.jpg'; $info['author'] = $method == 'demo' ? 'twbs' : $info['author']; ?>
  	<div class="col-sm-6 col-md-4 col-lg-3 ">
      <div class="thumbnail">
        <a href="demo/<?php echo $key; ?>" title="<?php echo $key; ?>" target="_blank">
        <img class="lazy" src="<?php echo $assetUrlNull; ?>" width="300" height="150" data-src="<?php echo $picture; ?>" alt="<?php echo $key; ?>">
        </a>
        <div class="caption">
          <h3> 
            <a href="demo/<?php echo $key; ?>" title="<?php echo $key; ?>" target="_blank"><?php echo $key; ?><br><small>by @<?php echo $info['author']; ?></small></a>
          </h3>
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

