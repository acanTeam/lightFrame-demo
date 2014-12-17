<link href="<?php echo $application->configCommon['assetUrl'] . 'bootstrap/demo/css/' . $demo . '.css'; ?>" rel="stylesheet">
    <!-- Begin page content -->
    <div class="container">
      <div class="page-header">
        <h1>Sticky footer</h1>
      </div>
      <p class="lead">Pin a fixed-height footer to the bottom of the viewport in desktop browsers with this custom HTML and CSS.</p>
      <p>Use <a href="../sticky-footer-navbar">the sticky footer with a fixed navbar</a> if need be, too.</p>
    </div>

    <footer class="footer">
      <div class="container">
        <p class="text-muted">Place sticky footer content here.</p>
      </div>
    </footer>
<?php $application->render('common/js_base', array('application' => $application)); ?>
