<?php layout('partials/head.php') ?>


    <div class="all-title-box">
        <div class="container text-center">
            <h1>Contact<span class="m_1">Lorem Ipsum dolroin gravida nibh vel velit.</span></h1>
        </div>
    </div>

    <div id="contact" class="section wb">
        <div class="container">
            <div class="section-title text-center">
                <h3>Email confirmation</h3>
                <p class="lead"><?= $message ?></p>

            </div><!-- end title -->

        </div><!-- end container -->
    </div><!-- end section -->



<?php layout('partials/footer.php', compact('settings')); ?>

