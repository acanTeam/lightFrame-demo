<link href="<?php echo $application->configCommon['assetUrl'] . 'bootstrap/demo/css/' . $demo . '.css'; ?>" rel="stylesheet">
<button type="button" class="btn btn-default pull-right tooltip-bottom" title="This should be shifted to the left">Shift Left</button>
<button type="button" class="btn btn-default tooltip-bottom" title="This should be shifted to the right">Shift Right</button>
<button type="button" class="btn btn-default tooltip-right" title="This should be shifted down">Shift Down</button>

<button type="button" class="btn btn-default tooltip-right btn-bottom" title="This should be shifted up">Shift Up</button>

<div class="container-viewport">
  <button type="button" class="btn btn-default tooltip-viewport-bottom" title="This should be shifted to the left">Shift Left</button>
  <button type="button" class="btn btn-default tooltip-viewport-right" title="This should be shifted down">Shift Down</button>

  <button type="button" class="btn btn-default pull-right tooltip-viewport-bottom" title="This should be shifted to the right">Shift Right</button>

  <button type="button" class="btn btn-default tooltip-viewport-right btn-bottom" title="This should be shifted up">Shift Up</button>
</div>

<?php $application->render('common/js_base', array('application' => $application)); ?>
