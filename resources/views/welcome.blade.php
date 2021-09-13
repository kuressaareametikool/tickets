<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="antialiased font-sans flex flex-col items-center justify-center bg-gray-light">
    @if ($errors->any())
        <div class="bg-red-500 font-bold mb-4 p-4 rounded text-red-200">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(Session::has('status'))
        <div class="bg-green-500 text-green-200 font-bold p-4 mb-4 rounded">
            <span >{{ Session::get('status') }}</span>
        </div>
    @endif
    <form action="/file-upload" method="post" enctype="multipart/form-data" class="flex flex-col gap-4">
        <div class="flex flex-col gap-4">
            @csrf
            <label for="filename">Vali fail</label>
            <input id="filename" name="filename" type="file">
        </div>
        <button class="py-4 px-8 bg-green-500 text-white font-bold">Lae üles</button>
    </form>
</body>
</html>
