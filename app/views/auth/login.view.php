<?php layout('partials/head.php');

?>

<?php
    $errors_arr=flashSessionMessage('login_errors');
?>

<div class="row">
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="contact_form">
        <div class="section-title row text-center">
            <div class="col-md-8 offset-md-2">
                <h3>Login </h3>
            </div>
        </div>
            <form action="/login" method="POST">
            <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <input type="email" name="email" value="<?=old('email') ?>" class="form-control" placeholder="Your Email">
                        <?php showError('email', $errors_arr);?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <input type="password" name="password" value="" class="form-control" placeholder="Your Password">
                        <?php showError('password', $errors_arr);?>
                    </div>
                </div>
                <div class="row">
                    <div class="form-check w-50 mb-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <input type="checkbox" name="remember" class="form-check-input">
                            <label class="form-check-label" >Remember me</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="text-center pd">
                        <button type="submit" class="btn btn-light btn-radius btn-brd grd1">Login</button>
                    </div>
                </div>
                </div>
            </form>
        </div>
    </div><!-- end col -->

</div>


<?php layout('partials/footer.php', compact('settings')); ?>
