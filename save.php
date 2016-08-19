<?php
require 'sources/autoload.php';
require 'sources/config.php';

if (!isset($_GET)) die('saving error with factset');
$query = $_GET['q'];
$NQ = new NineteenQuestions($query);

$objectID = 0;
if (isset($_GET['obj'])) {
  $objectID = intval($_GET['obj']);
} else if (isset($_GET['objectname'])) {
  $objectID = $NQ->addObject($_GET['objectname']);
}

$dh = $NQ->query("SELECT id, name, sub FROM objects WHERE id=$objectID");
list($oid, $oname, $osub) = mysql_fetch_array($dh)
  or die ("object database error");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="robots" content="noindex" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>19 Questions</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>var q="<?= htmlentities($query) ?>"</script>
    <style>
      body {background: #F1EBEB; margin-top: 2em}
      .well {background:-webkit-linear-gradient(#5CCEEE 0%, #93e1f6 100%); border: none}
    </style>
  </head>
  <body>
    <div class="container">
      <div class="well well-lg">
        <h2>19 Questions <small style="color:#333">you think of something, we guess what it is</small></h2>
        <hr>
<?php
if (isset ($_POST['action']) && $_POST['action'] == 'save')
{
  $NQ->teach($oid);
  echo "<p>Thanks for playing. <a href=\"play.php\" class=\"btn btn-lg btn-primary\">Play again</a> <a href=\"index.php\" class=\"btn btn-lg btn-default\">Go to the 19Q homepage</a>";
  echo "<hr>";
  echo "<p><a href=\"http://www.facebook.com/sharer.php?u=http%3A%2F%2Fgoo.gl%2F3XhDR&t=19 Questions Game\" class=\"btn btn-lg btn-success\">Share on Facebook</a>
          <a href=\"http://twitter.com/intent/tweet?text=19 Questions Game&url=http%3A%2F%2Fgoo.gl%2F3XhDR\" class=\"btn btn-lg btn-success\">Share on Twitter</a>
          <a href=\"https://plusone.google.com/_/+1/confirm?hl=en&url=http%3A%2F%2Fgoo.gl%2F3XhDR\" class=\"btn btn-lg btn-success\">Share on Google Plus</a></p>";
}
else
{
?>
        <form method="post">
          <input name="obj" value="<?= htmlentities($_GET['obj']) ?>" type="hidden">
          <input name="objectname" value="<?= htmlentities($_GET['objectname']) ?>" type="hidden">
          <input name="q" value="<?= htmlentities($_GET['q']) ?>" type="hidden">
          <input name="action" value="save" type="hidden">
          <p>You are about to teach <b>19 Questions</b> this information about <b><?= $oname ?></b>. <input class="btn btn-lg btn-primary" type="submit" value="Teach 19 Questions"></p>
        </form>
        <hr>
<?php
  $pastQuestions = $NQ->getPastQuestions();
  foreach (array_reverse($pastQuestions) as $pastQuestion)
  {
    list($name, $subtext, $answer) = $pastQuestion;
    if ($answer == 'wrong') continue;
    if (strlen($subtext)) $name .= " ($subtext)";
    echo "<p>".htmlentities($name)." &mdash; $answer</p>";
  }
}
?>
      </div>
    </div>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-52764-3', 'phor.net');
      ga('send', 'pageview');
    </script>
  </body>
</html>
