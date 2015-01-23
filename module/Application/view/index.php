<div class="jumbotron masthead">
  <div class="container">
    <h1>acanstudio</h1>
    <p>我的在线工作室，记录工作、学习和生活的点点滴滴</p>
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

    <?php foreach ($infos as $key => $info) { ?>
    <h1><?php echo $info['title']; ?></h1>
    <p class="marketing-byline"><?php echo $info['description']; ?><a href="<?php echo $info['url']; ?>">更多相关信息</a></p>
    <div class="row-fluid">
      <ul class="thumbnails example-sites">
        <?php foreach($info['elems'] as $subKey => $elem) { ?>        
        <li class="span3">
          <a class="thumbnail" href="<?php echo $application->domain . $elem['url']; ?>" target="_blank">
            <img src="<?php echo $application->configCommon['uploadUrl'] . 'acanstudio/' . $key . '/' . $subKey . '.png'; ?>" alt="<?php echo $key; ?>">
          </a>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?>
  </div>
</div>
