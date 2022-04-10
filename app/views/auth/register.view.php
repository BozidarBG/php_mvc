<?php layout('partials/head.php');

?>

<?php
    $errors_arr=flashSessionMessage('register_errors_arr');
    $server_error=flashSessionMessage('server_register');
?>

<div class="row">
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="contact_form">
        <div class="section-title row text-center">
            <div class="col-md-8 offset-md-2">
                <h3>Register </h3>
                <?php $server_error ? showError('p', $server_error) : null; ?>
            </div>
        </div>
            <form action="/register" method="POST">
            <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                        <?php showError('name', $errors_arr);?>
                        <input type="text" name="name" value="<?=old('name') ?>" class="form-control" placeholder="Your Name">
                        
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php showError('email', $errors_arr);?>
                        <input type="email" name="email" value="<?=old('email') ?>" class="form-control" placeholder="Your Email">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php showError('password', $errors_arr);?>
                        <input type="password" name="password" value="" class="form-control" placeholder="Your Password">
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php showError('password_confirmation', $errors_arr);?>
                        <input type="password" name="password_confirmation" value="" class="form-control" placeholder="Please Re-type Your Password">
                        
                    </div>
                </div>
                
                <div class="row">
                <div class="text-center pd">
                        <button type="submit" class="btn btn-light btn-radius btn-brd grd1">Register</button>
                    </div>
                </div>
                </div>
            </form>
        </div>
    </div><!-- end col -->

</div>


<?php layout('partials/footer.php', ['settings'=>$settings]); ?>
