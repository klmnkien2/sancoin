<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Notification of Registration</h2>
        <p>You have created an account in https://sancoin.vn</p>
        <p>Please click link bellow to complete register</p>
        <a href="{{ route('pb.reg_activate', ['id' => $id, 'code' => $activate_code]) }}">{{ route('pb.reg_activate', ['id' => $id, 'code' => $activate_code]) }}</a>.
        <p>Best regard,<br> Sancoin.vn</p>
    </body>
</html>