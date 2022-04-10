<?php layout('admin/admin_head.php') ?>


                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-warning card-header-icon">
                                <div class="card-icon">
                                    <i class="material-icons">people</i>
                                </div>
                                <p class="card-category">Total no. of users</p>
                                <h3 class="card-title"><?= $users_count ?>
                                    <small></small>
                                </h3>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-success card-header-icon">
                                <div class="card-icon">
                                    <i class="material-icons">euro_symbol</i>
                                </div>
                                <p class="card-category">New applications</p>
                                <h3 class="card-title"><?= $unanswered_applications_count ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-danger card-header-icon">
                                <div class="card-icon">
                                    <i class="material-icons">contact_support</i>
                                </div>
                                <p class="card-category">Unanswered questions</p>
                                <h3 class="card-title"><?= $unanswered_questions_count ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                            <div class="card-header card-header-info card-header-icon">
                                <div class="card-icon">
                                    <i class="fa fa-twitter"></i>
                                </div>
                                <p class="card-category">Followers</p>
                                <h3 class="card-title">+245</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title float-left">Last 5 Applications</h4>
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
                                                Course
                                            </th>
                                            <th>
                                                Received
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

                                            </tr>

                                        <?php }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <h4 class="card-title float-left">Last 5 New Users/Students</h4>
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

                                            </tr>

                                        <?php }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



<?php layout('admin/admin_footer.php') ?>