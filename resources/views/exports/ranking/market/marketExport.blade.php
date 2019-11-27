<table>
	@if(is_string($data))
		<tr>
			<td style="color: red">{{$data}}</td>
		</tr>
	@else
		<tr>
			<th colspan="{{sizeof($data)}}">
				<span>
					<b>
						{{$names['region']}} - Market ({{$names['val']}} - {{$dataMarket}}) Ranking (BKGS) : ({{$names['currency'][0]['name']}}/{{strtoupper($names['value'])}})
					</b>
				</span>
			</th>
		</tr>

		@for($m = 0; $m < sizeof($data[0]); $m++)
			<tr>
				@for($n = 0; $n < sizeof($data); $n++)
					@if(is_numeric($data[$n][$m]))
						@if($data[$n][0] == "Var (%)" || $data[$n][0] == "Share Bookings ".$names['years'][0] || $data[$n][0] == "Share Bookings ".$names['years'][1] || $data[$n][0] == "% YoY")
							<td>{{$data[$n][$m]/100}}</td>
						@elseif($data[$n][0] == "Ranking")
							<td>{{$data[$n][$m]}}º</td>
						@else
							@if($data[$n][$m] == 0)
								<td> - </td>
							@else
								<td>{{$data[$n][$m]}}</td>
							@endif
						@endif
					@else
						<td>{{$data[$n][$m]}}</td>
					@endif
				@endfor
			</tr>
		@endfor

		@if(!is_null($dataTotal))
			<tr>
				@for($t=0; $t < sizeof($dataTotal); $t++)
					@if($t == $pos[0] || $t == $pos[1] || $t == $pos[2])
						<td>{{$dataTotal[$t]/100}}</td>
					@else
						<td>{{$dataTotal[$t]}}</td>
					@endif
				@endfor
			</tr>
		@endif

	@endif
</table>