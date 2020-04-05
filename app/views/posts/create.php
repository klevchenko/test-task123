<?php

require_once APP_ROOT."/views/header.php";

?>

    <div class="col-12">

        <?php \TodoList\Controllers\Tools::flash_message(); ?>

        <form method="post" action="/post/create">
            <div class="form-group">
                <label for="user_name">User name</label>
                <input type="text" name="user_name" class="form-control" id="user_name" placeholder="User name">
            </div>

            <div class="form-group">
                <label for="user_email">Email address</label>
                <input type="text" name="user_email" class="form-control" id="user_email" placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label for="text">Text</label>
                <textarea class="form-control" name="text" id="text" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add task</button>

        </form>

    </div>

<?php

require_once APP_ROOT."/views/footer.php";
