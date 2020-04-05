<?php

require_once APP_ROOT."/views/header.php";

?>

    <div class="col-12">

        <?php
        if(isset($_SESSION['messages']) and is_array($_SESSION['messages'])){
            foreach ($_SESSION['messages'] as $message){
                echo $message;
            }
            unset($_SESSION['messages']);
        }
        ?>

        <form method="post" action="/login">
            <div class="form-group">
                <label for="user_name">User name</label>
                <input type="text" name="user_name" class="form-control" id="user_name" placeholder="User name">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="********">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>

        </form>

    </div>

<?php

require_once APP_ROOT."/views/footer.php";
