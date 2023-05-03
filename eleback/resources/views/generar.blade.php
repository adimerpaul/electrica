<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        *{
            /*font-family: Arial, Helvetica, sans-serif;*/
            padding: 0px;
            margin: 0px;
            border: 0px;
        }
        div.wrapper {
            width: 100%; /* or whatever */
            overflow: hidden;
            white-space: nowrap;

        }

        div.inline {
            display: inline-block;
            width: 60px;
            height: 60px;
        }
        .page_break { page-break-before: always; }
    </style>
</head>
<body >
    <br>
    <div class='wrapper' >
        @foreach($inventarios as $inv)
    <div class="inline" style="padding:8px 0px 0px 8px; margin-top:20px;">
        <img src="data:image/png;base64, {!! $inv['qr'] !!}" style="height: 50px;width: 50px;">
        <div style="font-size: 6px; text-align: center">{!! $inv['codigo'] !!}</div>
    </div>
{{--<div style="color: #a0aec0;position: absolute;top: 350px;left: 915px;width: 10px;height: 10px">{{$credencial['id']}}</div>--}}
<!--<div class="page_break"></div>-->
@endforeach
</div>
</body>
</html>
