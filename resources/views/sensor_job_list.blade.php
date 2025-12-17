@extends('flowerberrypi')
 
@section('title', 'Hourly Jobs')
@section('submenu')
@include ('include_dummy_menu')  
@endsection

@section('content')
@include('include_time_horizon_menu')
        <h1>Hourly Jobs</h1>

	<form action="/trigger_job" target="_top" method="post">
	        @csrf
			<table>
			 <tbody><tr><td>Adhoc:<input type="checkbox" id="adhoc" name="adhoc" value="false"></td><td><button type="submit">Start</button></td><td><button type="submit" formaction="/jobs" formmethod="GET">Refresh</button></td></tr>
            </tbody></table>
		</form>

		<div class="data-container">
            <div class="grid-item">
        	<h2>List</h2>
        	
        	<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Status</th>
                        <th>Started</th>
                        <th>Ended</th>
                    </tr>
                </thead>
                <tbody>
        	
            @foreach($history as $job) 
               @php
               		$hour = \Carbon\Carbon::parse($job->created_at)->hour;
                    if (\App\Helpers\GlobalStuff::is_first_hour_of_tod($hour)) 
                    {
                        $highlighted = ' class="highlighted" ';
                    } 
                    else 
                    {
                        $highlighted = ' ';
                    } 
                @endphp            
            
               <tr {!! $highlighted !!}>
           			<td><a {!! $highlighted !!} href="/job_details/{{$job->id}}">{{ $job->id }}</a></td>
	                <td><a {!!$highlighted!!} href="/job_details/{{$job->id}}">{{ $job->status }}</a></td>
               <td> {{ $job->created_at }} </td>
				 <td> {{ $job->updated_at  }} </td>
                </tr>
             
            @endforeach
                </tbody>
            </table>        
        	</div>
        	

@endsection