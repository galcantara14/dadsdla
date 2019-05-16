@extends('layouts.mirror')
@section('title', 'Share')
@section('head')	
<script src="/js/performance.js"></script>
    <?php include(resource_path('views/auth.php')); 
    ?>
@endsection
@section('content')
	<div class="container-fluid">		
		<div class="row">
			<div class="col">
				

				<form method="POST" action="{{ route('corePerformancePost') }}">
					@csrf
					<div class="row justify-content-center">
						<div class="col">	
							<label class='labelLeft'><span class="bold">Region:</span></label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($region)}}							
							@else
								{{$render->regionFiltered($region, $regionID )}}
							@endif
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Year:</span></label>
							@if($errors->has('year'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->year()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Brands:</span></label>
							@if($errors->has('brand'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Source:</span></label>
							@if($errors->has('source'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->source()}}
						</div>
						
					</div>
					<div class="row justify-content-center">
						<div class="col">
							<label class='labelLeft'><span class="bold">Sales Rep Group:</span></label>
							@if($errors->has('salesRepGroup'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->salesRepGroup($salesRepGroup)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Currency:</span></label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency($currency)}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Months:</span></label>
							@if($errors->has('month'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->months()}}
						</div>
						<div class="col">
							<label class='labelLeft'><span class="bold">Value:</span></label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value()}}
						</div>
						<div class="col">
							<label class='labelLeft'> &nbsp; </label>
							<input style="width: 100%;" type="submit" value="Generate" class="btn btn-primary">		
						</div>
					</div>
				</form>
				<div class="row justify-content-end">
					<div class="col col-3"  style="text-align: center; margin-top: 2%;">
						<span class="reportsTitle">Core Performance</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="vlau"></div>


@endsection