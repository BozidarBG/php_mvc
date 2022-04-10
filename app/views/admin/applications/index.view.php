<?php layout('admin/admin_head.php'); ?>
<?php
$success_msg=\App\Core\Session::has('success') ? \App\Core\Session::flash('success') : null;
?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title float-left"><?= $page_name ?></h4>
            </div>
            <?php $success_msg ? showSuccess($success_msg, 'div','card-header card-header-success' ) : null ?>
            <div class="card-body">
                <div class="table-responsive ">
                    <table class="table ">
                        <thead class=" text-primary">
                        <tr><th>
                                ID
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Course
                            </th>
                            <th>
                                Received
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr></thead>
                        <tbody>
                        <?php
                        foreach($applications as $application){
                            ?>
                            <tr>
                                <td>
                                    <?php echo $application->id ?>
                                </td>
                                <td>
                                    <?php echo $application->user->name ?>
                                </td>
                                <td>
                                    <?php echo $application->user->email ?>
                                </td>
                                <td>
                                    <?php echo $application->course->name ?>
                                </td>
                                <th>
                                    <?php echo (new DateTime($application->created_at))->format('d.m.Y @ H:i')  ?>
                                </th>
                                <td >

                                    <form class="pull-left" method="POST" action="/admin-edit-application/<?php echo $application->id ?>">
                                        <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                                        <button class="btn btn-warning" type="submit">Mark as <?php echo $page_name== "Unanswered Applications" ? 'answered' : 'unanswered'; ?></button>
                                    </form>
                                    <form class="pull-left" method="POST" action="/admin-delete-application/<?php echo $application->id ?>">
                                        <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
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