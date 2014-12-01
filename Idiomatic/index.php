  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">

  <head>
  <meta name="generator" content="jemdoc, see http://jemdoc.jaboc.net/" />
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <link rel="stylesheet" href="style.css" type="text/css" />

  <script src="js/jquery-1.8.2.min.js"></script>
  <script src="js/bootstrap.min.js"></script>

  <title>Idiomatic</title>

  <script type="text/javascript">

    read_idioms('week');//Show week by default
    function read_idioms(duration) {
      $.ajax(
      {
        url: 'read_idioms.php',
        data: {'duration': duration},
        type: 'POST',
        success: function (output) {
          document.getElementById("idioms").innerHTML = output;
        }
      }
      );
    }

  </script>

  </head>


  <body>

  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <div id = "like_button" class="fb-like" 
    data-href="https://www.facebook.com/idiomatic.12" 
    data-layout="standard" 
    data-action="like" 
    data-show-faces="true" 
    data-share="true">
  </div>	

  <div id="users">        
      <?php include "read_top_users.php"; ?>      
  </div>

  <center>

  <h1><a href=index.php>Idiomatic</a> &mdash; <i>Find trending idioms!</i></h1>

  <table id="content">

  <tr><td>
  <p align=justify style="margin-left:25px; font-size:20px;">
  1. &nbsp; <a href="#" onclick="read_idioms('week');">Trending Idioms of last week</a><br />
  2. &nbsp; <a href="#" onclick="read_idioms('month');">Trending Idioms of last month</a><br />
  3. &nbsp; <a href="#" onclick="read_idioms('');">All Idioms</a><br />
  </p>
  </td></tr></table>

  <div id="idioms">  
  </div>

  <div id="definition_idiom">
    <h2 align="center">Definition of idiom</h2>
    <p>
      Twitter Idiom is a special category of Twitter topic, which regularly becomes trending. Examples of typical idioms are #FourWordsBeforeBreakup, #WhenIGetAngry etc. They are not related to any real life events like a sports match or music album or political news. 
      Romero, Meeder, and Kleinberg in an earlier paper defined
      Idiom as a tag representing a conversational theme on twitter, consisting of a concatenation of at least two common
      words. 
      The concatenation can’t include names of people or
      places, and the full phrase can’t be a proper noun in itself
      (e.g. a title of a song/movie/organization). 
      Names of days are allowed in the concatenation, because of the the Twitter convention of forming hashtags involving names of days
      (e.g. #MusicMonday, #FollowbackFriday).
    </p>

  </div>

  </center>
  </body>
  </html>
