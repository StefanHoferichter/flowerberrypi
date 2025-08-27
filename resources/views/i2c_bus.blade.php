@extends('flowerberrypi')
 
@section('title', 'Sensoren')
@section('url', 'https://www.rezeptexperte.de/show_categories') 

@section('content')
@include('submenu')

        <h1>I2C Bus</h1>


		<div class="data-container">
            <div class="grid-item">
        	
        	<h2>Current</h2>
            <table border="0" cellpadding="5">
                @foreach ($output as $rowIndex => $row)
                    <tr>
                        @foreach ($row as $cell)
                            @if ($rowIndex === 0)
                                <th>{{ htmlspecialchars($cell) }}</th>
                            @else
                                <td>{{ htmlspecialchars($cell) }}</td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </table>     

@endsection