<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Test</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/">Home</a>

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/post/create">New item</a>
            </li>

            <?php if($_SESSION and isset($_SESSION['ifLogedIn']) and $_SESSION['ifLogedIn']) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">Log out</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/login">Login</a>
                </li>
            <?php endif; ?>
        </ul>

    </nav>

    <div class="container py-5">
        <div class="row">