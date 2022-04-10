<?php layout('partials/head.php', compact('settings')) ?>

    <div class="all-title-box">
        <div class="container text-center">
            <h1>Teachers<span class="m_1">Lorem Ipsum dolroin gravida nibh vel velit.</span></h1>
        </div>
    </div>


    <div id="teachers" class="section wb">
        <div class="container">
            <div class="row">
                <?php foreach ($teachersWithCourses as $teacher):?>
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="our-team">
                        <div class="team-img">
                            <img src="/files/images/<?= $teacher->photo ?>">

                        </div>
                        <div class="team-content">
                            <h3 class="title"><?= $teacher->name ?></h3>
                            <?php foreach($teacher->courses as $course): ?>
                            <p class="post"><?= $course->name ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>

            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->
    <span id="page_name" class="hidden_text">Teachers</span>
   

<?php layout('partials/footer.php', compact('settings'));
