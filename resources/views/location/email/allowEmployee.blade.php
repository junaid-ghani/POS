<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request For Approval</title>
</head>
<body>
  
    {{-- <p> < {{ $approve_emp['employee'] }} > has requested your approval for the sale in the amount of < {{ $approve_emp['total'] }} > . Click here to approve(<a href="{{ $approve_emp['approve_Url'] }}">{{ $approve_emp['approve_Url'] }}</a>)</p> --}}
    <p>{{ $approve_emp['employee'] }} has submitted a request for a sale approval. <a href="{{ $approve_emp['approve_Url'] }}">Click here to view it.</a></p>
    
</body>
</html>