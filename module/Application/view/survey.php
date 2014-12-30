<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="description" content="地图APP用户需求意向调查">
<meta name="keywords" content="地图APP用户需求意向调查,问卷调查,调查问卷,网络调查">  
<link rel="stylesheet" href="http://www.lediaocha.com/assets/libs/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" href="http://www.lediaocha.com/assets/libs/jqueryui/css/jquery-ui.css" />
<link rel="stylesheet" href="http://www.lediaocha.com/assets/libs/timepicker-addon/jquery-ui-timepicker-addon.css" />
<link rel="stylesheet" href="http://www.lediaocha.com/assets/libs/FortAwesome/css/font-awesome.min.css" />
<!--[if IE 7]>
<link rel="stylesheet" href="http://www.lediaocha.com/assets/libs/FortAwesome/css/font-awesome-ie7.min.css">
<![endif]-->
<link rel="stylesheet" href="http://www.lediaocha.com/assets/libs/fancyBox/jquery.fancybox.css" />
<link rel="stylesheet" href="http://www.lediaocha.com/templates/default/template.css" />
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/jquery/jquery.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/jqueryui/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/timepicker-addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/fancyBox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/flowplayer/flowplayer-3.2.12.min.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/assets/libs/swfobject.js"></script>
<script type="text/javascript" src="http://www.lediaocha.com/templates/default/template.js?v=0617"></script>
<script type="text/javascript">
$(function(){
  Answer._lang="zh";
});
function gotoUse(){
  var url = '';
  if(window.parent.length == 0){
    window.location.href = url;
  }
  else{
    window.parent.location.href = url;
  }
}
</script>

<title>地图APP用户需求意向调查</title>
</head>
<body>
<div class="wrapper " id="survey-page">
  <div class="page">
    <form method="GET" name="answer" action="<?php echo $application->domain; ?>lbs/answer" id="answer">
      <div data-mandatory="0" data-type="98" id="5f94ea4c5755935662d03ca5c2e5ff41" class="question">
        <div class="question-answer">
          <h1>调查说明</h1>
          <div class="answer-text clearfix">
          <p>
            1.  该调查主要是向你了解你对地图APP某些需求实现与否的意见或态度<br />
            2.  这里地图APP主要是指提供定位、导航等服务的手机APP<br />
            3.  当前有代表性的地图APP有百度地图、谷歌地图、高德地图等<br />
            4.  该调查主要涉及地图APP35个方面的需求<br />
            5.  针对每个需求，你将要回答两个方面的问题：<br />
                &nbsp;&nbsp;&nbsp; A.  一是当一个需求实现时，你认为如何<br />
                &nbsp;&nbsp;&nbsp; B.  一是当一个需求不被实现时，你认为如何<br />
            6.  针对每个问题，你有五种可选的答案：<br />
            &nbsp;&nbsp;&nbsp;A.  我觉得很好 /你对当前问题的内容持肯定、欢迎态度<br />
            &nbsp;&nbsp;&nbsp;B.  这是必须的  /你认为当前问题的内容是理所当然的<br />
            &nbsp;&nbsp;&nbsp;C.  不知道  /你认为当前问题的内容好坏都可以，无关紧要<br />
            &nbsp;&nbsp;&nbsp;D.  也可以/你觉得当前问题的内容你不太欢迎，但也可以接受<br />
            &nbsp;&nbsp;&nbsp;E.  我觉得不好/你觉得当前问题的内容如果出现了你无法接受<br />
            <strong>7.  特别提醒，你在回答问题时，由于每个需求所提出的两个问题都是一样的，因此你只需要了解每一个需求的内容，而无需关注问题的具体内容。</strong><br />
          </p>
          </div>
        </div>
      </div>
      <?php $i = 1; foreach ($questions['baseQuestions'] as $code => $question) { ?>
      <div data-mandatory="1" data-type="4" id="<?php echo 'baseQuestion_' . $i; ?>" class="question">
        <div class="question-title" data-original-title="" title="">
          <div class="question-code">q<?php echo $i; ?>.</div>
          <div class="question-text clearfix"><strong><?php echo $question['title']; ?></strong></div>
        </div>
        <div class="question-answer format1">
          <div class="answer-text">
            <input type="text" value="<?php if (isset($values[$code])) { echo $values[$code]; } ?>" data-preg="" data-maxinum="" data-mininum="" data-max="0" data-min="0" class="form-text" name="<?php echo $code; ?>" id="<?php echo $code; ?>">
          </div>
        </div>
      </div>
      <?php $i++; } ?>

      <?php $i = 1; foreach ($questions['questions'] as $code => $question) { $haveCode = $code . '_have'; $noCode = $code . '_no'; ?>
      <div data-direction="X" data-mandatory="1" data-type="5" id="<?php echo 'question_' . $i; ?>" class="question">
        <div class="question-title">
          <div class="question-code">Q<?php echo $i; ?>.</div>
          <div class="question-text clearfix">【<strong><?php echo $question['title']; ?></strong>】 需求说明：<?php echo $question['description']; ?></div>
        </div>
        <div class="question-answer">
          <table class="answer-table">
            <tbody>
              <?php foreach (array($haveCode, $noCode) as $elem) { ?>
              <tr class="mandatory">
                <th>&nbsp;</th>
                <th><div class="table-col-text">我觉得很好</div></th>
                <th><div class="table-col-text">这是必须的</div></th>
                <th><div class="table-col-text">不知道</div></th>
                <th><div class="table-col-text">也可以</div></th>
                <th><div class="table-col-text">我觉得不好</div></th>
              </tr>
              <tr data-group="<?php echo $elem; ?>" data-mandatory="1">
                <?php $titleInfo = $elem == $haveCode ? '<td data-child="1"><div class="table-question-text">如果<strong>实现</strong>这个需求，你感觉如何？</div></td>' : '<td data-child="2"><div class="table-question-text">如果<strong>不实现</strong>这个需求，你感觉如何？</div></td>'; echo $titleInfo; ?>
                <?php for ($j = 1; $j < 6; $j++) { ?>
                <td>
                <div class="answer-table-radio <?php if (isset($values[$elem]) && $values[$elem] == $j) { echo 'checked'; } ?>" style="height: 35px; line-height: 35px;">
                  <input type="radio" data-col="<?php echo $j; ?>" data-row="1" name="<?php echo $elem; ?>" data-group="<?php echo $elem; ?>" id="<?php echo $elem . '_' . $j; ?>" value="<?php echo $j; ?>" <?php if (isset($values[$elem]) && $values[$elem] == $j) { echo 'checked="checked"'; } ?>>
                  <div class="answer-icon"></div>
                  <label for="<?php echo $elem . '_' . $j; ?>"></label>
                  </div>
                </td>
                <?php } ?>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>  
      <?php $i++; } ?>

<?php if (!isset($isShow)) { ?>
      <div class="question-page clearfix">
        <input type="hidden" value="dosubmit" name="action">
        <input type="hidden" value="<?php echo $sign; ?>" name="sign">
        <button onclick="Answer.next()" type="button" id="page-next"></button>
      </div>
<?php } ?>
    </form>
  </div>
</div>
</body>
</html>
