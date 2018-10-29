<?php
    session_start();
    $error = "";
    if (array_key_exists("logout",$_GET))
    {
        unset($_SESSION);
        setcookie("id",time()-60*60);
        $_COOKIE['id'] = "";
        
        session_destroy();
    }
    else if ( (array_key_exists('id',$_SESSION) AND $_SESSION['id']) OR (array_key_exists('id',$_COOKIE) AND $_COOKIE['id']))
    {
        header("Location: http://localhost/loggedinpage.php");
    }
    // Post variable test .. 
    if ( array_key_exists("submit",$_POST) )
    {
        $link = mysqli_connect("localhost","root","","diary_users") or die("Unable to connect");
         
        if (!$_POST["email"])
        {
           $error .= "Your email address is required<br>"; 
        }
        if (!$_POST["password"])
        {
            $error .= "Your password is required<br>";
        }
        if($error != "")
        {
            $error = "<p>There were errors in your submission</p>".$error;
        }
        else 
        {   //editing here nwww ..
            if ($_POST['signUp'] == '1')
            {
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1";
            $result = mysqli_query($link,$query);
            if (mysqli_num_rows($result) > 0)
            {
                echo "That email address is taken";
            }
            else 
            {
                $query = "INSERT INTO `users` (`email`,`password`) VALUES ('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password'])."')";  
                
                if (!mysqli_query($link,$query))
                {
                    $error = "<p>There was an error signing you up ! please try again later ..</p>";
                    
                }
                else 
                {
                    $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1 ";  // auto generation of id function
                    mysqli_query($link,$query);
                    $_SESSION['id'] = mysqli_insert_id($link);
                    echo $_SESSION['id'];
//                    if ($_POST['stayloggedin'] == '1')
//                    {
                        setcookie('id',mysqli_insert_id($link),time() + 60*60*24*365);
                    //}
                    header("Location: http://localhost/loggedinpage.php");
                }
            }
            }
            else 
            {
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."'";
                $result = mysqli_query($link,$query);
                $row = mysqli_fetch_array($result);
                if(isset($row))
                {
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);
                    
                    if( $hashedPassword == $row['password'])
                    {
                        $_SESSION['id'] = $row['id'];
                        print_r($_SESSION);
                       // if ($_POST['stayloggedin'] == '1')
                    //{
                        setcookie('id',$row['id'],time() + 60*60*24*365);
                    //}
                    header("Location: http://localhost/loggedinpage.php");
                    }
                    else
                    {
                        $error = "email or password is not valid";    
                    }
                }
                else
                {
                    $error = "email or password is not valid";
                }
            }
        }
    }

?>
<?php include("header.php"); ?>
        <div class="container" id="homepage">
            
            
            <h1>Secret Diary</h1>
            
            <p><strong>Keep a diary, and someday it'll keep you.</strong></p>
            
            <div id="error"><?php 
                
                if($error != ""){
                   echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                } 
                
                ?></div>
            
            <form method="post" id="signup-form">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Enter Email">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Enter Password">
                </div>
                <div class="form-group">
                    <input class="form-control" name="signUp" type="hidden" value="1">
                </div>        
                <div class="form-group">
                    <button name="submit" type="submit" value = "Sign Up" class="btn btn-success">Submit</button>
                </div>
                
                <p><a class="toggleclass" href="#">Log In</a></p>
            </form>
            
            <form method="post" id="login-form">
                <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="Enter Email">
                </div>
                <div class="form-group">
                    <input class="form-control" name="password" type="password" placeholder="Enter Password">
                </div>
                
                <div class="form-group">
                    <input class="form-control" name="signUp" type="hidden" value="0">
                </div>
                <div class="form-group">
                    <button name="submit" type="submit" value = "Log In" class="btn btn-success">Log In</button>
                </div>
                <p><a class="toggleclass" href="#">Sign Up</a></p>
            </form>
                
        </div>

    <?php include("footer.php"); ?>