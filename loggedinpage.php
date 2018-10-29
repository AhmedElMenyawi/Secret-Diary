<?php
    
    session_start();
    $diarycontent = "";
    if(array_key_exists("id",$_COOKIE) && $_COOKIE['id'])
    {
        $_SESSION['id'] = $_COOKIE['id'];
    }

    if(array_key_exists("id",$_SESSION ))
    {
       
        $link = mysqli_connect("localhost","root","","diary_users") or die("Unable to connect");
        
        $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link,$_SESSION['id'])." LIMIT 1";
        
        $row = mysqli_fetch_array(mysqli_query($link,$query));
        
        $diarycontent = $row['diary'];
        
    }
    else
    {
        header("Location: http://localhost/SecretDiary.php");
    }


 include("header.php");
?>
<nav class="navbar navbar-light bg-light ">
  <a class="navbar-brand" href="#">Secret Diary</a>
  
    <div class="pull-xs-right ">
      <a href="SecretDiary.php?logout=1"><button class="btn btn-outline-success" type="submit">Logout</button></a>
    </div>
 
</nav>
<div class="container-fluid">
    <textarea id="diary" class="form-control">
    <?php echo $diarycontent; ?>
    </textarea>
</div>

<?php 
include("footer.php");
?>


