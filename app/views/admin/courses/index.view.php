<?php layout('admin/admin_head.php'); ?>
<?php
$errors_arr=flashSessionMessage('errors');
$success_msg=flashSessionMessage('course_deleted_success');

?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title float-left">All Courses</h4>
                <h4 class="card-title float-right"><a class="btn btn-default" href="/admin-create-course" >Create Course</a></h4>
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
                                Name
                            </th>
                            <th>
                                Teachers
                            </th>

                            <th>
                                Price
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr></thead>
                        <tbody>
                            <?php
                            foreach($coursesWithTeachers as $course){
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $course->id ?>
                                    </td>
                                    <td>
                                        <?php echo $course->name ?>
                                    </td>
                                    <td>
                                        <?php foreach ($course->teachers as $teacher){
                                            echo $teacher->name."<br>";
                                        }?>
                                    </td>

                                    <td class="text-primary">
                                        <?php echo $course->price ?>
                                    </td>
                                    <td >
                                        <a class="btn btn-warning" href="/admin-edit-course/<?php echo $course->id ?>">Edit</a>
                                    </td>
                                </tr>
                            <?php }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php layout('admin/admin_footer.php'); ?>