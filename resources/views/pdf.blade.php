<html>
<head>
    <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }
    
    th, td {
      text-align: left;
      padding: 8px;
    }
    
    tr:nth-child(even){background-color: #f2f2f2}
    
    h1{
        text-align: center
    }
 
    </style>
</head>
<body>
<h1> Welcome To Our Hotel  </h1>

<table id="dataTable" class="table table-hover">
    <tr>
        <th>name</th>
        <td>{{$final_invoice['fname']}} </td>
    </tr>
<tr>
    <th>room_num</th>
    <td>{{$final_invoice['room_id']}} </td>
</tr>
<tr>
    <th>room price</th>
    <td>{{$final_invoice['room_price']}} </td>
</tr>
<tr>
    <th>duration</th>
    <td>{{$final_invoice['duration']}}</td>
</tr>

<tr>
    <th>Services</th>

    @foreach ($cnt as $item)
    <tr> 
        <th>Count: {{$item['count']}}</th>                
        <td>
          {{$item['title']}}  , price :{{$item['price']}}
        </td>
        
    </tr>
    @endforeach
</tr>

<tr>
    <th></th>
@foreach ($service_invoice as $ser)

    <td>
        {{$ser['title']}} ,{{$ser['price']}}
    </td>
    @endforeach

</tr>
    <tr>
    <th>Total</th>

    <td>{{$final_invoice['Total']}} </td>
    </tr>
</table>

</body>
<html>