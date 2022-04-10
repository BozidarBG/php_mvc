<?php layout('partials/head.php') ?>
<?php   $errors_arr=\App\Core\Session::has('errors_course') ? \App\Core\Session::flash('errors_course') : null; ?>

    <div class="all-title-box">
        <div class="container text-center">
            <h1><?= $courseWithTeachers->name ?><span class="m_1">Lorem Ipsum dolroin gravida nibh vel velit.</span></h1>
        </div>
    </div>

    <div id="overviews" class="section wb">
        <div class="container">
            <div class="row">
                <?php if($errors_arr){ ?>
                <div class="col-lg-12 blog-post-single">
                    <div class="alert alert-danger">
                        <?php showError('course', $errors_arr); ?>
                    </div>

                </div>
                 <?php } ?>
                <div class="col-lg-12 blog-post-single">
                    <div class="blog-item">
                        <div class="image-blog">
                            <img src="/files/images/<?= $courseWithTeachers->photo ?>" alt="" class="img-fluid">
                        </div>
                        <div class="post-content">
                            <div class="pull-left  post-date">
                                <span class="day">Price</span>
                                <span class="month"><?=$courseWithTeachers->price ?></span>
                            </div>
                        </div>
                        <div class="post-content">



                            <div class="blog-title">
                                <h2><?= $courseWithTeachers->short ?></h2>
                            </div>
                            <div class="blog-desc">
                                <p><?= $courseWithTeachers->content ?></p>

                            </div>
                        </div>
                        <div class="post-content">

                            <?php if(!isLoggedIn()){?>
                                <div class="blog-title">
                                    <h2>You will need to register or log in in order to apply for this course. Please click book now</h2>
                                </div>
                                <div class="d-flex">
                                    <a class="btn btn-light btn-radius btn-brd grd1" href="/login" ><span>Login</span></a>
                                    <a class="btn btn-light btn-radius btn-brd grd1 ml-3" ><span>Register</span></a>
                                </div>
                            <?php }else{?>
                                <div class="pull-left blog-title">
                                    <div class="pull-left post-date">
                                        <form action="/apply" method="POST">
                                            <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                                            <input type="hidden" name="course_id" value="<?php echo $courseWithTeachers->id ?>">
                                            <div class="post-btn">
                                                <button type="submit" class="hover-btn-new orange"><span>Sign in for this course</span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            <?php }?>
                        </div>

                    </div>
                    <br>
                    <h3>Teachers for this course</h3>
                    <?php foreach ($courseWithTeachers->teachers as $teacher):?>
                    <div class="blog-author">
                        <div class="author-bio">
                            <h3 class="author_name"><?= $teacher->name ?></h3>

                            <p class="author_det">
                                <?= $teacher->about ?>
                            </p>
                        </div>
                        <div class="author-desc">
                            <img src="/files/images/<?= $teacher->photo ?>" alt="teacher">

                        </div>
                    </div>
                    <?php endforeach; ?>

                </div><!-- end col -->

            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->
    <span id="page_name" class="hidden_text">Course</span>
<?php layout('partials/footer.php', compact('settings'));