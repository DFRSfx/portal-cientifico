<!-- Note: class .show is used for demo purposes. Remove it when using it in the real project. -->
<div class="alert alert-dismissible alert-absolute fade show" id="alertExample" role="alert" data-mdb-color="{{ $color }}" data-mdb-width="600px" data-mdb-appendToBody="true" data-mdb-position="bottom-right" data-mdb-autohide="true" data-mdb-delay="2000"> 
    <i class="{{ $iconClass }}"></i>
    {{ __("" . $message) }}
    <button type="button" class="btn-close ms-2" data-mdb-dismiss="alert" aria-label="Close"></button>
</div>