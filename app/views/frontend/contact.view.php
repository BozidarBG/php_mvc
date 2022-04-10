<?php layout('partials/head.php', compact('settings')) ?>
<?php
$errors_arr=\App\Core\Session::has('question_errors') ? \App\Core\Session::flash('question_errors') : null;
$success_msg=\App\Core\Session::has('question_success') ? \App\Core\Session::flash('question_success') : null;

?>

    <div class="all-title-box">
        <div class="container text-center">
            <h1>Contact<span class="m_1">Lorem Ipsum dolroin gravida nibh vel velit.</span></h1>
        </div>
    </div>

    <div id="contact" class="section wb">
        <div class="container">
            <div class="section-title text-center">
                <h3>Need Help? Sure we are Online!</h3>
                <p class="lead">Let us give you more details about our courses. Please fill out the form below. <br>We have thousands of satisfied students!</p>
                <?php
                if($success_msg){
                    echo "<br>";
                    showSuccess($success_msg, 'div', 'lead alert alert-success');

                }
                ?>
            </div><!-- end title -->

            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="contact_form">
                        <form   action="/store-question"  method="POST">

                            <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                            <div class="row row-fluid">


                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="text" name="first_name" value="<?php echo old('first_name'); ?>"  class="form-control" placeholder="First Name">
                                    <?php showError('first_name', $errors_arr);?>

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="text" name="last_name" value="<?php echo old('last_name'); ?>"  class="form-control" placeholder="Last Name">
                                    <?php showError('last_name', $errors_arr); ?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="email" name="email" value="<?php echo old('email'); ?>" class="form-control" placeholder="Your Email">
                                    <?php showError('email', $errors_arr);?>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="text" name="phone"  value="<?php echo old('phone'); ?>" class="form-control" placeholder="Your Phone">
                                    <?php showError('phone', $errors_arr);?>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <textarea class="form-control" name="question"  rows="6" placeholder="Give us more details.."><?php echo old('question'); ?></textarea>
                                    <?php showError('question', $errors_arr);?>
                                </div>
                                <div class="text-center pd">
                                    <button type="submit" value="SEND" class="btn btn-light btn-radius btn-brd grd1">Ask Us</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- end col -->

            </div><!-- end row -->
        </div><!-- end container -->
    </div><!-- end section -->

    <span id="page_name" class="hidden_text">Contact</span>

<?php layout('partials/footer.php', compact('settings'));