<?php
// https://stackoverflow.com/questions/34154370/bootstrap-3-x-how-to-have-url-change-upon-clicking-modal-trigger
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modal_1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_1">Title</h5>
      </div>
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            {!! file_get_contents('images/icons/x.svg') !!}
        </button>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>

