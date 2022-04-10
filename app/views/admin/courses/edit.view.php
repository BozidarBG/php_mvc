<?php layout('admin/admin_head.php'); ?>
<?php
$errors_arr=\App\Core\Session::has('errors') ? \App\Core\Session::flash('errors') : null;
$success_msg=\App\Core\Session::has('success') ? \App\Core\Session::flash('success') : null;

?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Edit </h4>

            </div>
<?php
if($success_msg){
    showSuccess($success_msg, 'div', 'card-header card-header-success');
}
?>
            <div class="card-body">
                <form method="POST" action="/admin-edit-course/<?php echo $course->id ?>" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="bmd-label-floating">Course Name</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $course->name ?>">
                                <?php showError('name', $errors_arr);?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="bmd-label-floating">Number of lectures</label>
                                <input type="number" class="form-control" name="lectures" value="<?php echo $course->lectures ?>">
                                <?php showError('lectures', $errors_arr);?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="bmd-label-floating">Duration (weeks)</label>
                                <input type="number" class="form-control" name="duration" value="<?php echo $course->duration ?>">
                                <?php showError('duration', $errors_arr);?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="bmd-label-floating">Price</label>
                                <input type="number" class="form-control" name="price" value="<?php echo $course->price ?>">
                                <?php showError('price', $errors_arr);?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="bmd-label-floating">Short Description</label>
                                <input type="text" class="form-control" name="short" value="<?php echo $course->short ?>">
                                <?php showError('short', $errors_arr);?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="">
                                <label class="bmd-label-floating">Photo</label>
                                <input type="file" class="form-control-file" name="photo">
                                <img src="/files/images/<?php echo $course->photo ?>" width="100">
                                <?php showError('photo', $errors_arr);?>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <div class="form-group">
                                    <label class="bmd-label-floating"> About This Course</label>
                                    <textarea class="form-control" rows="6" name="content"><?php echo $course->content ?></textarea>
                                    <?php showError('content', $errors_arr);?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                    <div class="clearfix"></div>
                </form>
                <form method="POST" action="/admin-delete-course/<?php echo $course->id; ?>">
                    <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                    <button type="submit" class="btn btn-danger delete-course pull-right" data-toggle="modal" data-target="#confirmationModal" >Delete</button>
                </form>
                <?php layout('admin/confirm_modal.php'); ?>
            </div>
        </div>
    </div>
    <script>

        new ModalConfirmation();
    </script>
<?php layout('admin/admin_footer.php'); ?>