<?php layout('admin/admin_head.php'); ?>
<?php
$errors_arr=\App\Core\Session::has('errors') ? \App\Core\Session::flash('errors') : null;
$success_msg=\App\Core\Session::has('success') ? \App\Core\Session::flash('success') : null;

?>



<div class="col-md-8">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title float-left">All Settings</h4>

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
                            Value
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr></thead>
                    <tbody>
                    <?php
                    foreach($settings as $setting){
                        ?>
                        <tr>
                            <td class="td-id">
                                <?php echo $setting->id ?>
                            </td>
                            <td class="td-key">
                                <?php echo $setting->prop_name ?>
                            </td>
                            <td class="td-value">
                                <?php echo $setting->prop_value?>
                            </td>
                            <td >
                                <a class="pull-left btn btn-warning" href="/admin-edit-settings/<?php echo $setting->id ?>">Edit</a>
                                <form class="pull-left" method="POST" action="/admin-delete-settings/<?php echo $setting->id ?>">
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

<div class="col-md-4">
    <div class="card">
        <div class="card-header card-header-primary">
            <h4 class="card-title float-left">Create New</h4>
         </div>
        <div class="card-body">
            <form method="POST" action="/admin-create-settings">
                <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Property Name</label>
                            <input type="text" class="form-control" name="prop_name" value="<?php echo old('prop_name') ?>">
                            <?php showError('prop_name', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="bmd-label-floating">Value</label>
                            <input type="text" class="form-control" name="prop_value" value="<?php echo old('prop_value')?>">
                            <?php showError('prop_value', $errors_arr);?>
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

    <div class="card">
        <div class="card-header card-header-warning">
            <h4 class="card-title float-left">Edit</h4>
        </div>
        <div class="card-body">
            <form method="POST" id="edit-form" action="" >
                <input type="hidden" name="_token" value="<?php echo getToken() ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">

                            <input type="text" class="form-control" name="prop_name" id="edit-key" value="" >
                            <?php showError('key', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">

                            <input type="text" class="form-control" name="prop_value" id="edit-value" value="" >
                            <?php showError('value', $errors_arr);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary pull-right" id="disabled-button" disabled>Update</button>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>
<script>
    window.onload =function () {
        let edit_buttons=document.getElementsByClassName('btn-warning');
        for(let i=0; i<edit_buttons.length; i++){
            edit_buttons[i].addEventListener('click', function (event){
                event.preventDefault();
                //remove disabled property form button
                document.getElementById('disabled-button').removeAttribute('disabled');
                //take values of href, key and value and put them in form id=edit-form
                let href=event.target.getAttribute('href');
                let event_parent=event.target.parentElement.parentElement;

                let key=event_parent.querySelector('.td-key').innerText;
                let value=event_parent.querySelector('.td-value').innerText;

                //put values in the form
                let edit_form=document.getElementById('edit-form').setAttribute('action', href);
                let edit_key=document.getElementById('edit-key').setAttribute('value', key);
                let edit_value=document.getElementById('edit-value').setAttribute('value', value);
            });
        }
    };

</script>


<?php layout('admin/admin_footer.php'); ?>
