<div class="container-fluid fluid-height wrapper">
  <div class="row columns content">
    <div class="left-column article-tree col-sm-3 hidden-print">
      <div class="responsive-collapse">
        <button type="button" class="btn btn-sidebar" id="menu-spinner-button">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div id="sub-nav-collapse" class="sub-nav-collapse">
        <?php
          //if ($page['language'] !== '') echo $this->get_navigation($tree->value[$page['language']], $page['language'], $params['request'], $base_page, $params['mode']);
          //else echo $this->get_navigation($tree, '', $params['request'], $base_page, $params['mode']);
        ?>
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
    <div class="right-column <?php echo 'float-view'; // ($params['float']?'float-view':''); ?> content-area col-sm-9">
      <div class="content-page">
        <article>
          <?php if (true) { //$params['date_modified']) { ?>
            <div class="page-header sub-header clearfix">
              <h1><?php
                  //if ($page['breadcrumbs']) echo $this->get_breadcrumb_title($page, $base_page);
                  //else echo $page['title'];
                ?>
                <?php //if ($page['file_editor']) echo '<a href="javascript:;" id="editThis" class="btn">Edit this page</a>'; ?>
              </h1>
              <span style="float: left; font-size: 10px; color: gray;">
                <?php echo date('Y-m-d H:i:s'); //date("l, F j, Y", $page['modified_time']);?>
              </span>
              <span style="float: right; font-size: 10px; color: gray;">
                <?php echo date('Y-m-d H:i:s');//date("g:i A", $page['modified_time']);?>
              </span>
            </div>
          <?php } else { ?>
            <div class="page-header">
              <h1><?php
                  //if ($page['breadcrumbs']) echo $this->get_breadcrumb_title($page, $base_page);
                  //else echo $page['title'];
                ?>
                <?php //if ($page['file_editor']) echo '<a href="javascript:;" id="editThis" class="btn">Edit this page</a>'; ?>                  </h1>
            </div>
          <?php } ?>

          <?php echo 'content'; ?>
          <?php if (true) {//$page['file_editor']) { ?>
            <div class="editor<?php echo 'paddingtop'; //if(!$params['date_modified']) echo ' paddingTop'; ?>">
              <h3>You are editing <?php echo 'path'; ?>&nbsp;<a href="javascript:;" class="closeEditor btn btn-warning">Close</a></h3>
              <div class="navbar navbar-inverse navbar-default navbar-fixed-bottom" role="navigation">
                <div class="navbar-inner">
                  <a href="javascript:;" class="save_editor btn btn-primary navbar-btn pull-right">Save file</a>
                </div>
              </div>
              <textarea id="markdown_editor"><?php echo 'markdown';?></textarea>
              <div class="clearfix"></div>
            </div>
          <?php } ?>
        </article>
      </div>
    </div>
  </div>
</div>
