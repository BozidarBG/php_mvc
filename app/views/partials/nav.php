<!-- Start header -->
<header class="top-navbar">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html">
                <img src="/images/logo.png" alt="logo" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-host" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbars-host">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item <?php echo active('')?>"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item <?php echo active('courses')?>"><a class="nav-link" href="/courses">Courses</a></li>
                    <li class="nav-item <?php echo active('teachers')?>"><a class="nav-link" href="/teachers">Teachers</a></li>
                    <li class="nav-item <?php echo active('contact')?>"><a class="nav-link" href="/contact">Contact</a></li>
                    <?php if(isLoggedIn()){ ?>
                        <li class="nav-item <?php echo active('profile')?>"><a class="nav-link" href="/profile">Profile</a></li>
                    <?php }?>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(isLoggedIn()){ ?>
                    <form method="post" action="/logout">
                        <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                        <li><button type="submit" class="btn btn-outline-warning font-weight-bold" ><span>LOGOUT</span></button></li>
                    </form>
                    <?php }else{?>
                        <li class="nav-item <?php echo active('login')?>"><a class="nav-link" href="/login">Login</a></li>
                        <li class="nav-item <?php echo active('register')?>"><a class="nav-link" href="/register">Register</a></li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- End header -->