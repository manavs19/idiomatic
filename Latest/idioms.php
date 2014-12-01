    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />

      <title>Idiomatic - Find tweets of idiom lovers!</title>

      <link href="style.css" rel="stylesheet" media="screen">
      
      <script src="js/jquery-1.8.2.min.js"></script>
      <script src="js/bootstrap.min.js"></script>

      <script type="text/javascript">

        function idiom_feedback() {
          var idiom = "<?php echo $_GET["idiom"]; ?>";
          form=document.getElementById("input");
          var group = form.opinion;
          for (var i=0; i<group.length; i++) {
            if (group[i].checked)
            break;
          }

          var feedback = group[i].value;
          //alert(feedback);
         $.ajax(
          {
            url: 'idiom_feedback.php',
            data: {'idiom':idiom, 'feedback': feedback},
            type: 'POST',
            success: function (output) {
              document.getElementById("feedback").innerHTML = output;
            }
          }
          ); 
        }

      </script>

    </head>
    <body>


      <center>
        <div class="container">

          <h1 id="header">
            <a href=index.php>Idiomatic</a> &mdash;
            <i>Find trending idioms!</i>
          </h1>       

          <table id="content">
            <tr><td>
              <div id = "results">
                <?php include "read_tweets.php"; ?>              
              </div>
            </tr></td>
          </table>

          <div id="users">            
            <?php include "read_users.php"; ?>
            
          </div>

<!--
          <div id="feedback">
            <h2>Feedback :</h2>
            <form id="input" >
              <input id="id_str" value="<?php echo $_GET["idiom"]; ?>" type="hidden" />
              <h3>Is this hashtag actually an idiom?</h3>
                <input type="radio" name="opinion" value="1" checked="true" />&nbsp;Yes&nbsp;&nbsp;
                <input type="radio" name="opinion" value="-1" />&nbsp;No&nbsp;&nbsp;
                <input type="radio" name="opinion" value="0" />&nbsp;Can't tell<br /><br />
                <input type="button" value="Submit" onclick="idiom_feedback()"/>
            </form>
          </div>
    
-->


          <div id="tweet_button">
	<h3>Interested in writing a tweet yourself?</h3>
          <a class="twitter-hashtag-button"
            href="https://twitter.com/intent/tweet?button_hashtag=<?php echo $_GET['idiom']; ?>"
            data-related="twitter"
            data-size="large">
            Tweet #<?php echo $_GET["idiom"]; ?>
          </a>
          <script type="text/javascript">
          window.twttr=(function(d,s,id){var t,js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)){return}js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);return window.twttr||(t={_e:[],ready:function(f){t._e.push(f)}})}(document,"script","twitter-wjs"));
          </script>
          </div>
	

      
        </div>

      </center>      

    </body>

    </html>
