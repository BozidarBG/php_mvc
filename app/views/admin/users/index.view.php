<?php layout('admin/admin_head.php'); ?>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title float-left">All users/students</h4>
            </div>
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
                                Joined
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($users as $user){
                            ?>
                            <tr>
                                <td>
                                    <?php echo $user->id ?>
                                </td>
                                <td>
                                    <?php echo $user->name ?>
                                </td>
                                <td>
                                    <?php echo $user->email ?>
                                </td>

                                <th>
                                    <?php echo (new DateTime($user->created_at))->format('d.m.Y @ H:i')  ?>
                                </th>
                                <td >

                                    <form class="pull-left" method="POST" action="/admin-delete-user/<?php echo $user->id ?>">
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
        <?php
        foreach($paginationObj->links as $obj){
            echo $obj;
        }
        ?>
    </div>
<?php layout('admin/admin_footer.php'); ?>