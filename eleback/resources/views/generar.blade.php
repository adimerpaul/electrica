<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        * {
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
            width: 80px;
            height: 20px;

        }
        .page_break { page-break-before: always; }
        @page { size: 8cm 10cm; }
    </style>
</head>
<body>
@foreach($inventarios as $inv)
<div class='wrapper '>
<img src="data:image/png;base64, {!! $inv['codigo'] !!}" style="background : white ; border:2px solid white;width: 50px;height: 50px"></div>
{{--<div style="color: #a0aec0;position: absolute;top: 350px;left: 915px;width: 10px;height: 10px">{{$credencial['id']}}</div>--}}
<!--<div class="page_break"></div>-->
@endforeach
</body>
</html>