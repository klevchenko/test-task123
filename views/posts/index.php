<?php

require_once ROOT."/views/header.php";

$PostController = new PostController();

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
    </div>

    <div class="col">
        <div class="btn-group pb-4 d-flex flex-wrap">

            <span class="p-2">Sorting: </span>

            <a class="btn btn-link" href="<?php echo $PostController::createPaginateLink($page, 'user_name','ASC') ?>">Username A-Z</a>
            <a class="btn btn-link" href="<?php echo $PostController::createPaginateLink($page, 'user_name', 'DESC' ) ?>">Username Z-A</a>

            <span  class="btn btn-link">|</span>

            <a class="btn btn-link" href="<?php echo $PostController::createPaginateLink($page, 'user_email','ASC') ?>">Email address A-Z</a>
            <a class="btn btn-link" href="<?php echo $PostController::createPaginateLink($page, 'user_email','DESC' ) ?>">Email address Z-A</a>

            <span  class="btn btn-link">|</span>

            <a class="btn btn-link" href="<?php echo $PostController::createPaginateLink($page, 'status','DESC') ?>">Status A-Z</a>
            <a class="btn btn-link" href="<?php echo $PostController::createPaginateLink($page, 'status','ASC' ) ?>">Status Z-A</a>

        </div>
    </div>



    <div class="col-12">
        <?php foreach ($posts as $post) : ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-subtitle mb-2">User name: <?=$post['user_name']?></h5>
                    <h5 class="card-subtitle mb-2">Email address: <?=$post['user_email']?></h5>

                    <?php if($post['status']) : ?>
                        <span class="badge badge-success">Task completed</span>
                    <?php else: ?>
                        <span class="badge badge-danger">The task is unfinished</span>
                    <?php endif; ?>

                    <?php if($post['admin_edit']) : ?>
                        <span class="badge badge-info">Task edited by administrator</span>
                    <?php endif; ?>


                    <p class="card-text mt-2"><?=$post['text']?></p>

                    <?php if($_SESSION and isset($_SESSION['ifLogedIn']) and $_SESSION['ifLogedIn']) : ?>
                        <a href="/post/edit/<?=$post['id']?>" class=" btn btn-secondary">Edit</a>
                    <?php endif; ?>

                </div>
            </div>
        
        <?php endforeach; ?>

        <?php if($total_pages > 1) : ?>
            <nav>
                <ul class="pagination">
                    <?php
                        foreach (range(1, $total_pages) as $page_num){
                            ?>
                            <li class="page-item <?php echo $page == $page_num ? 'active' : '' ?>">
                                <a class="page-link" href="<?php echo $PostController::createPaginateLink($page_num) ?>">
                                    <?php echo $page_num ?>
                                </a>
                            </li>
                            <?php
                        }
                    ?>
                </ul>
            </nav>
        <?php endif; ?>

    </div>

<?php

require_once ROOT."/views/footer.php";
