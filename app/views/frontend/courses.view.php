<?php layout('partials/head.php', compact('settings')) ?>

    <div class="all-title-box">
        <div class="container text-center">
            <h1>Courses<span class="m_1">Lorem Ipsum dolroin gravida nibh vel velit.</span></h1>
        </div>
    </div>

    <div id="overviews" class="section wb">
        <div class="container">
            <div class="section-title row text-center">
                <div class="col-md-8 offset-md-2">
                    <p class="lead">Lorem Ipsum dolroin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem!</p>
                </div>
            </div><!-- end title -->

            <hr class="invis">

            <div class="row">
                <?php foreach ($courses as $course):?>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="course-item">
                        <div class="course-br" style="width:540px; height:376px; ">
                            <a href="/course/<?= $course->slug ?>"><img src="/files/images/<?= $course->photo ?>" alt=""  class="img-fluid"></a>
                        </div>
                        <div class="course-br">
                            <div class="course-title">
                                <h2><a href="/course/<?= $course->slug ?>"><?= $course->name ?></a></h2>
                            </div>
                            <div class="course-desc">
                                <p><?= $course->short ?></p>
                            </div>

                        </div>
                        <div class="course-meta-bot">
                            <ul>
                                <li><i class="fa fa-calendar" aria-hidden="true"></i>Duration: <?= $course->duration ?> months</li>

                                <li><i class="fa fa-book" aria-hidden="true"></i>Total lectures: <?= $course->lectures ?> lectures</li>
                            </ul>
                        </div>
                    </div><br>
                </div><!-- end col -->

                <?php endforeach; ?>

            </div><!-- end row -->

            <hr class="hr3">


            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->
    <span id="page_name" class="hidden_text">Courses</span>
<?php layout('partials/footer.php', compact('settings'));