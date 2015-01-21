<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
  <title><?php echo 'title'; ?></title>
  <meta name="description" content="<?php echo 'tagline';?>" />
  <meta name="author" content="<?php echo 'author'; ?>">
  <meta charset="UTF-8">
  <link rel="icon" href="<?php echo $application->configCommon['assetUrl'] . 'document/images/favicon-' . 'blue' . '.png'; ?>" type="image/x-icon">
  <!-- Mobile -->
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href='<?php echo $application->configCommon['assetUrl']; ?>document/css/style.css' rel='stylesheet' type='text/css'>

  <link href='<?php $theme = isset($configs['theme']) && !empty($configs['theme']) ? $configs['theme'] : 'blue'; echo $application->configCommon['assetUrl'] . 'document/css/theme_' . $theme . '.css'; ?>' rel='stylesheet' type='text/css'>

</head>
<body>

<div class="navbar navbar-fixed-top hidden-print">
  <div class="container">
    <a class="brand navbar-brand pull-left" href="javascript: void(0);"><?php echo $docsInfo['title']; ?></a>
    <p class="navbar-text pull-right"><?php echo $docsInfo['description']; ?></p>
  </div>
</div>
<?php if (isset($configs['github']) && !empty($configs['github'])) { ?>
<a href="https://github.com/<?php echo $configs['github']; ?>" target="_blank" id="github-ribbon" class="hidden-print">
  <img src="<?php echo $application->configCommon['assetUrl']; ?>document/images/github_fork.png" alt="Fork me on GitHub">
</a>
<?php } ?>

<?php echo $contentLayout; ?>

<?php $application->render('common/js_base', array('application' => $application)); ?>
<script src="<?php echo $application->configCommon['assetUrl']; ?>document/js/highlight.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<script src="<?php echo $application->configCommon['assetUrl']; ?>document/js/editor.js"></script>
<script src="<?php echo $application->configCommon['assetUrl']; ?>document/js/custom.js"></script>
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</body>
</html>

