<?php layout('admin/admin_head.php'); ?>
<?php
$errors_arr=\App\Core\Session::has('errors') ? \App\Core\Session::flash('errors') : null;
$success_msg=\App\Core\Session::has('success') ? \App\Core\Session::flash('success') : null;

?>
<div class="col-md-6">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title float-left">Welcome <?= $user->name ?></h4>

        </div>
        <?php
        if($success_msg){
            showSuccess($success_msg, 'div', 'card-header card-header-success');
        }
        ?>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class=" text-primary">
                    <tr><th>
                            ID
                        </th>
                        <th>
                            Course Name
                        </th>
                        <th>
                            Course Price
                        </th>

                    </tr></thead>
                    <tbody>
                    <?php
                    if($applicationsWithCourses){
                        $i=1;
                        foreach($applicationsWithCourses as $application){
                            ?>
                            <tr>
                                <td>
                                    <?php echo $i; $i++ ?>
                                </td>
                                <td >
                                    <?php echo $application->course->name ?>
                                </td>
                                <td >
                                    <?php echo $application->course->price ?>
                                </td>

                            </tr>
                        <?php }
                    }else{
                        ?>
                        <tr><td colspan="3">You haven't applied for any course yet!</td></tr>
                        <?php
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6">


    <div class="card">
        <div class="card-header card-header-warning">
            <h4 class="card-title float-left">Change Password</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="/change-password" >
                <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input class="form-control"  placeholder="Old Password" type="password" name="old_password">
                            <?php showError('old_password', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input class="form-control"  placeholder="Password" type="password" name="password">
                            <?php showError('password', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input class="form-control"  placeholder="Repeat Password" type="password" name="password_confirmation">
                            <?php showError('password_confirmation', $errors_arr);?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Update</button>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>



<?php layout('admin/admin_footer.php'); ?>

