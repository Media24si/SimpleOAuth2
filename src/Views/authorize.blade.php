<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Authorize</title>

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc= sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
          crossorigin="anonymous">
</head>
<body>

<div class="container">
    <form method="post" action="">
        {!! csrf_field() !!}
        @foreach ($auth_params as $key => $value)
            <input type="hidden" name="{{$key }}" value="{{ $value }}">
        @endforeach

        <h1>Do you authorize {{ $client->name }}?</h1>
        <p>
            <input type="submit" name="accept" value="Yep" class="btn btn-primary">
            <input type="submit" name="accept" value="Nope" class="btn btn-danger">
        </p>
    </form>
</div>

</body>

</html>


