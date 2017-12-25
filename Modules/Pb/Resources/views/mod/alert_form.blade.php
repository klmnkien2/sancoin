<?php
$alertClass = "";
if ($type == "error") {
    $alertClass = "alert-danger";
} else if ($type == "success") {
    $alertClass = "alert-success";
}
?>
@foreach($messages as $messageFields)
@foreach($messageFields as $message)
<div class="alert {{$alertClass}} alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <span class="content">{{$message}}</span>
</div>
@endforeach
@endforeach