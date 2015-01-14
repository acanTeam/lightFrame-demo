<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title>acan的在线工作室-acanstudio</title>
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

<?php echo $contentLayout; ?>
<?php $application->render('common/footer', array('application' => $application)); ?>

<script src="//cdnjs.bootcss.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.2.2/bootstrap.min.js"></script>
<script src="//cdnjs.bootcss.com/ajax/libs/prettify/r224/prettify.js"></script>
<script src="//cdnjs.bootcss.com/ajax/libs/select2/3.3.1/select2.min.js"></script>
</body>
</html>
