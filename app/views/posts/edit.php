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

        <form method="post" action="/post/update">

            <div class="form-group">
                <label for="user_name">User name</label>
                <input type="text" name="user_name" disabled="disabled" class="form-control" value="<?php echo $post['user_name'] ?>" id="user_name" placeholder="User name">
            </div>

            <div class="form-group">
                <label for="user_email">Email address</label>
                <input type="text" name="user_email" disabled="disabled" class="form-control" value="<?php echo $post['user_email'] ?>" id="user_email" placeholder="name@example.com">
            </div>

            <div class="form-group">
                <label for="text">Text</label>
                <textarea class="form-control" name="text" id="text" rows="3"><?php echo $post['text'] ?></textarea>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="task_completed" id="task_completed" <?php echo $post['status'] ? 'checked="checked"' : '' ?> />
                <label class="form-check-label" for="task_completed">Task completed</label>
            </div>

            <button type="submit" class="btn btn-primary">Update task</button>

        </form>

    </div>

<?php

require_once APP_ROOT."/views/footer.php";
