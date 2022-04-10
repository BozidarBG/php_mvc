<?php layout('admin/admin_head.php'); ?>
<?php
$errors_arr=\App\Core\Session::has('errors') ? \App\Core\Session::flash('errors') : null;
$success_msg=\App\Core\Session::has('success') ? \App\Core\Session::flash('success') : null;

?>

<div class="col-md-12">

    <div class="card card-nav-tabs">
        <div class="card-header card-header-primary">
            <h4 class="card-title float-left">Add New</h4>
        </div>
        <div class="card-body">
            <?php
            if($success_msg){
                showSuccess($success_msg, 'div', 'card-header card-header-success');
            }
            ?>
            <form method="POST" action="/admin-create-teacher" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo old('name') ?>">
                            <?php showError('name', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">About</label>
                            <textarea class="form-control" name="about" rows="5"><?php echo old('about')?></textarea>
                            <?php showError('about', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="exampleFormControlSelect2">Select course(s)</label>
                            <select name="courses[]" multiple class="form-control" id="exampleFormControlSelect2">
                                <?php foreach ($courses as $course){ ?>
                                    <option value="<?php echo $course->id ?>"><?php echo $course->name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row ">
                    <div class="col-md-12">
                        <div class="">
                            <label class="bmd-label-floating">Photo</label>
                            <input type="file" class="form-control-file" name="photo">
                            <?php showError('photo', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right">Create</button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>

</div>



<?php layout('admin/admin_footer.php'); ?>

