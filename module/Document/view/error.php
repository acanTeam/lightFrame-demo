<div class="container-fluid fluid-height wrapper">
  <div class="row columns content">
    <div class="left-column article-tree col-sm-3 hidden-print">
      <!-- For Mobile -->
      <div class="responsive-collapse">
        <button type="button" class="btn btn-sidebar" id="menu-spinner-button">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div id="sub-nav-collapse" class="sub-nav-collapse">
        <!-- Navigation -->
        <?php //echo $this->get_navigation($tree, '', $base_page); ?>
        <div class="well well-sidebar">
          <?php foreach (array('a' => 'b', 'c' => 'd') as $name => $url) echo '<a href="' . $url . '" target="_blank">' . $name . '</a><br>'; ?>
          <?php foreach (array('a', 'b', 'c') as $handle) { ?>
            <div class="twitter">
              <hr/>
              <iframe allowtransparency="true" frameborder="0" scrolling="no" style="width:162px; height:20px;" src=""></iframe>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="right-column content-area col-sm-9">
      <div class="content-page">
        <article>
          <div class="page-header">
            <h1><?php echo 'title'; ?></h1>
          </div>

          <?php echo 'content'; ?>
        </article>
      </div>
    </div>
  </div>
</div>
