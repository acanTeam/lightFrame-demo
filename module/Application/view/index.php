<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>acanstudio -- acan的在线工作室</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="acanstudio">
<meta name="robots" content="index,follow">
<meta name="application-name" content="bootcss.com">

<!-- Le styles -->
<link href="//cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.2.2/css/bootstrap.min.css" rel="stylesheet">
<link href="//cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.2.2/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/css/docs.css" rel="stylesheet">
<link href="//cdnjs.bootcss.com/ajax/libs/prettify/r224/prettify.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
  <script src="/cdnjs.bootcss.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
<![endif]-->

<!-- Le fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/ico/favicon.png">

<link href="<?php echo $application->configCommon['assetUrl']; ?>bootstrap/demo/bootcss/google/css/google-bootstrap.css" rel="stylesheet">
<link href="//cdnjs.bootcss.com/ajax/libs/select2/3.3.1/select2.css" rel="stylesheet">
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">

<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
      </button>
      <a class="brand" href="<?php echo $application->domain; ?>">acanstudio</a>
      <div class="nav-collapse collapse">
        <ul class="nav">
          <li class="active">
            <a href="<?php echo $application->domain; ?>">首页</a>
          </li>
          <?php foreach ($navbarInfos as $info) { ?>
          <li class="">
            <a href="<?php echo $info['url']; ?>"><?php echo $info['name']; ?></a>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="jumbotron masthead">
  <div class="container">
    <h1>acanstudio</h1>
    <p>我的在线研究室，记录工作、学习和生活的点点滴滴</p>
    <ul class="masthead-links">
      <li><a href="http://github.com/acanstudio" >GitHub-acanstudio</a></li>
      <li><a href="http://github.com/wangcan" >GitHub-wangcan</a></li>
    </ul>
  </div>
</div>

<div class="bs-docs-social">
  <div class="container">
    <ul class="bs-docs-social-buttons">
      
    </ul>
  </div>
</div>

<div class="container">

  <div class="marketing">

    <h1>Introducing Google Bootstrap.</h1>
    <p class="marketing-byline">Need reasons to love Google Bootstrap? Look no further.</p>

    <div class="row-fluid">
      <div class="span4">
        <img class="marketing-img" src="assets/img/bs-docs-twitter-github.png">
        <h2>By nerds, for nerds.</h2>
        <p>Forked from <a href="http://twitter.github.com/bootstrap/" target="_blank">Twitter Bootstrap</a>, built by <a href="https://github.com/todc" target="_blank">todc</a>, GoogleBootstrap utilizes <a href="http://lesscss.org">LESS CSS</a>, is compiled via <a href="http://nodejs.org">Node</a>, and is managed through <a href="http://github.com">GitHub</a> to help nerds do awesome stuff on the web.</p>
      </div>
      <div class="span4">
        <img class="marketing-img" src="assets/img/bs-docs-responsive-illustrations.png">
        <h2>Made for everyone.</h2>
        <p>Bootstrap was made to not only look and behave great in the latest desktop browsers (as well as IE7!), but in tablet and smartphone browsers via <a href="./scaffolding.html#responsive">responsive CSS</a> as well.</p>
      </div>
      <div class="span4">
        <img class="marketing-img" src="assets/img/bs-docs-bootstrap-features.png">
        <h2>Packed with features.</h2>
        <p>A 12-column responsive <a href="./scaffolding.html#gridSystem">grid</a>, dozens of components, <a href="./javascript.html">JavaScript plugins</a>, typography, form controls, and even a <a href="./customize.html">web-based Customizer</a> to make Bootstrap your own.</p>
      </div>
    </div>

    <hr class="soften">

    <h1>Built with Bootstrap.</h1>
    <p class="marketing-byline">For even more sites built with Bootstrap, <a href="http://builtwithbootstrap.tumblr.com/" target="_blank">visit the unofficial Tumblr</a> or <a href="./getting-started.html#examples">browse the examples</a>.</p>
    <div class="row-fluid">
      <ul class="thumbnails example-sites">
        <li class="span3">
          <a class="thumbnail" href="http://soundready.fm/" target="_blank">
            <img src="assets/img/example-sites/soundready.png" alt="SoundReady.fm">
          </a>
        </li>
        <li class="span3">
          <a class="thumbnail" href="http://kippt.com/" target="_blank">
            <img src="assets/img/example-sites/kippt.png" alt="Kippt">
          </a>
        </li>
        <li class="span3">
          <a class="thumbnail" href="http://www.gathercontent.com/" target="_blank">
            <img src="assets/img/example-sites/gathercontent.png" alt="Gather Content">
          </a>
        </li>
        <li class="span3">
          <a class="thumbnail" href="http://www.jshint.com/" target="_blank">
            <img src="assets/img/example-sites/jshint.png" alt="JS Hint">
          </a>
        </li>
      </ul>
     </div>

  </div>

</div>



    <!-- Footer
    ================================================== -->
    <footer class="footer">
      <div class="container">
        <p>Forked from <a href="http://twitter.github.com/bootstrap/" target="_blank">Twitter Bootstrap</a> and built with all the love in the world by <a href="https://github.com/todc" target="_blank">todc</a>.</p>
        <p>Code licensed under <a href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache License v2.0</a>, documentation under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
        <p><a href="http://glyphicons.com">Glyphicons Free</a> licensed under <a href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.</p>
        <ul class="footer-links">
          <li><a href="http://blog.getbootstrap.com">Blog</a></li>
          <li class="muted">&middot;</li>
          <li><a href="https://github.com/twitter/bootstrap/issues?state=open">Issues</a></li>
          <li class="muted">&middot;</li>
          <li><a href="https://github.com/twitter/bootstrap/wiki">Roadmap and changelog</a></li>
        </ul>
      </div>
    </footer>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//cdnjs.bootcss.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.2.2/bootstrap.min.js"></script>
    
    <script src="assets/js/holder/holder.js"></script>
    <script src="//cdnjs.bootcss.com/ajax/libs/prettify/r224/prettify.js"></script>
<script src="//cdnjs.bootcss.com/ajax/libs/select2/3.3.1/select2.min.js"></script>
    <script src="assets/js/application.js"></script>
<script src="/p/projects.js"></script>

  </body>
</html>
