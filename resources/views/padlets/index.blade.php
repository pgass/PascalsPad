<!DOCTYPE html>
<html>
<head>

    <title>KWM lernt Laravel</title>

</head>
<body>
<h1>Hello World!</h1>

<ul>
    @foreach ($padlets as $padlet)
        <li><a href="padlets/{{$padlet->id}}">
                {{$padlet->name}}</a></li>
    @endforeach
</ul>
</body>
</html>
