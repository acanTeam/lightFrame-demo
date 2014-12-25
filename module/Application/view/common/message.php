<link href="<?php echo $application->configCommon['assetUrl'] . 'bootstrap/demo/css/sticky-footer.css'; ?>" rel="stylesheet">
<div class="container">
  <div class="page-header">
    <h1>提示信息</h1>
  </div>
  <p class="lead"><?php echo $message; ?></p>
</div>

<?php if (!empty($urlForward)) { ?>
<script language="javascript">
setTimeout("redirect('<?php echo $urlForward?>');", <?php echo $sleepTime; ?>);

function redirect(url) {
  location.href = url;
}
</script> 
<?php } ?>

<?php $application->render('common/js_base', array('application' => $application)); ?>
