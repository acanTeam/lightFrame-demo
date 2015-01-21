<?php 
$links = isset($configs['links']) && is_array($configs['links']) ? $configs['links'] : false; 
$thirds = isset($configs['thirds']) && is_array($configs['thirds']) ? $configs['thirds'] : false; 
?>
<div class="container-fluid fluid-height wrapper">
  <div class="row columns content">

    <div class="left-column article-tree col-sm-3 hidden-print">
      <!-- For Mobile -->
      <div class="responsive-collapse">
        <button id="menu-spinner-button" class="btn btn-sidebar" type="button">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="sub-nav-collapse" id="sub-nav-collapse">
        <!-- Navigation -->
        <!--<ul class="nav nav-list">
          <li><a href="//daux.io/Getting_Started">Getting Started</a></li>
          <li class="open">
            <a class="folder" href="//daux.io/Examples">Examples</a>
            <ul class="nav nav-list">
<li class="open">
            <a class="aj-nav folder" href="#">More Examples</a>
            <ul class="nav nav-list">
              <li><a href="//daux.io/More_Examples/Hello_World">Hello World</a></li>
            </ul>
</li>
              <li><a href="//daux.io/Examples/GitHub_Flavored_Markdown">GitHub Flavored Markdown</a></li>
              <li class="active"><a href="//daux.io/Examples/Code_Highlighting">Code Highlighting</a></li>
            </ul>
          </li>
          <li>
            <a class="aj-nav folder" href="#">More Examples</a>
            <ul class="nav nav-list">
              <li><a href="//daux.io/More_Examples/Hello_World">Hello World</a></li>
            </ul>
          </li>
        </ul>-->

        <?php echo $navigation; ?>
        <div class="well well-sidebar">
          <?php if (!empty($links)) { foreach ($links as $link => $linkTitle) { echo '<a href="' . $link . '" target="_blank">' . $linkTitle . '</a><br>'; } } ?>
          <a id="toggleCodeBlockBtn" href="javascript: toggleCodeBlocks();">Show Code Blocks Inline</a><br>
          <?php if (!empty($thirds)) { foreach ($thirds as $third) { ?>
          <div class="twitter">
            <hr/><iframe allowtransparency="true" frameborder="0" scrolling="no" style="width:162px; height:20px;" src="<?php echo $third; ?>"></iframe>
          </div>
          <?php } } ?>
        </div>
      </div>
    </div>
    <div class="right-column <?php echo 'float-view'; // ($params['float']?'float-view':''); ?> content-area col-sm-9">
      <div class="content-page">
        <article>
          <?php if (true) { //$params['date_modified']) { ?>
            <div class="page-header sub-header clearfix">
              <h1><?php echo $breadCrumb; ?>
                <?php //if ($page['file_editor']) echo '<a href="javascript:;" id="editThis" class="btn">Edit this page</a>'; ?>
              </h1>
              <span style="float: left; font-size: 10px; color: gray;">
                <?php //echo date('Y-m-d H:i:s'); //date("l, F j, Y", $page['modified_time']);?>
              </span>
              <span style="float: right; font-size: 10px; color: gray;">
                <?php //echo date('Y-m-d H:i:s');//date("g:i A", $page['modified_time']);?>
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

          <?php echo $fileInfo['content']; ?>
          <?php if (false) {//$page['file_editor']) { ?>
            <div class="editor<?php echo 'paddingtop'; //if(!$params['date_modified']) echo ' paddingTop'; ?>">
              <h3>You are editing <?php echo 'path'; ?>&nbsp;<a href="javascript:;" class="closeEditor btn btn-warning">Close</a></h3>
              <div class="navbar navbar-inverse navbar-default navbar-fixed-bottom" role="navigation">
                <div class="navbar-inner">
                  <a href="javascript:;" class="save_editor btn btn-primary navbar-btn pull-right">Save file</a>
                </div>
              </div>
              <textarea id="markdown_editor"><?php echo $fileInfo['contentSource'];?></textarea>
              <div class="clearfix"></div>
            </div>
          <?php } ?>
        </article>
      </div>
    </div>
  </div>
</div>
