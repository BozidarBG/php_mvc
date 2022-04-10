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
                            Phone
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
                    foreach($results as $question){
                        ?>
                        <tr>
                            <td>
                                <?php echo $question->id ?>
                            </td>
                            <td>
                                <?php echo $question->first_name.' '.$question->last_name ?>
                            </td>
                            <td>
                                <?php echo $question->email ?>
                            </td>
                            <td>
                                <?php echo $question->phone ?>
                            </td>
                            <th>
                                <?php echo (new DateTime($question->created_at))->format('d.m.Y @ H:i')  ?>
                            </th>
                            <td >
                                <form class="pull-left" method="POST" action="/admin-edit-question/<?php echo $question->id ?>">
                                    <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                                    <button class="btn btn-warning" type="submit">Mark as <?php echo $page_name== "Unanswered Questions" ? 'answered' : 'unanswered'; ?></button>
                                </form>
                                <form class="pull-left" method="POST" action="/admin-delete-question/<?php echo $question->id ?>">
                                    <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>Message:</td>
                            <td colspan="5">
                                <?php echo $question->question ?>
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
