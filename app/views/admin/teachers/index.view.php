<?php layout('admin/admin_head.php'); ?>
<?php
$errors_arr=\App\Core\Session::has('errors') ? \App\Core\Session::flash('errors') : null;
$success_msg=\App\Core\Session::has('success') ? \App\Core\Session::flash('success') : null;

?>

<div class="col-md-12">
    <div class="card card-nav-tabs p-2">
        <div class="card-header card-header-primary">
            <h4 class="card-title float-left">All Teachers</h4>
            <h4 class="card-title float-right"><a class="btn btn-default" href="/admin-create-teacher" >Add new teacher</a></h4>
        </div>
<?php $success_msg ? showSuccess($success_msg, 'div', 'card-header card-header-success'): null ?>
        <?php
        if($teachers) { ?>
            <div class="row">
            <?php

            foreach ($teachers as $teacher) {
                ?>
                <div class="col-md-6">
                <div class="card p-1">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="/files/images/<?php echo $teacher->photo?>" class="card-img" alt="photo of a <?= $teacher->name?>">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title"><?= $teacher->name?></h5>
                                <p class="card-text"><?= $teacher->about ?></p>
                                <div class="card-text pull-left">
                                    <ul >
                                        <?php foreach($teacher->courses as $course){ ?>

                                            <li data-id="<?= $course->id ?>"><?= $course->name ?></li>

                                       <?php } ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-2">

                            <a class="pull-left btn btn-warning" href="/admin-edit-teacher/<?php echo $teacher->id ?>">Edit</a>
                            <form class="pull-left" method="POST" action="/admin-delete-teacher/<?php echo $teacher->id ?>">
                                <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>

                        </div>
                    </div>
                </div>
                </div>
            <?php }
            ?>
    </div>
            <?php
        }else{ ?>
            <div class="card  p-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <h5 class="card-title">There are no teachers in Database</h5>
                        </div>
                    </div>
                </div>
            </div>
       <?php }

            ?>

    </div>
</div>





<?php layout('admin/admin_footer.php'); ?>
