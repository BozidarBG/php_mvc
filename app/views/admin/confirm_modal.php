<!-- Button trigger modal 
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal">
  Launch demo modal
</button>
-->
<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Please, confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalTitle">
        Are you sure that you want to delete this?<br>This action cannot be undone!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmModalAction">Confirm</button>
      </div>
    </div>
  </div>
</div>