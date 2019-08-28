@extends('layouts.mirror')
@section('title', 'Quarter Results')
@section('head')	
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

		<div class="container-fluid">
			<div class="row">
				<div class="col">
					<form method="POST" action="{{ route('resultsQuarterPost') }}" runat="server"  onsubmit="ShowLoading()">
						@csrf
						<div class="row">
							<!-- Region Area -->
							<div class="col-sm">
								<label>Sales Region</label>
								@if($userLevel == 'L0' || $userLevel == 'SU')
									{{$render->region($region)}}							
								@elseif($userLevel == '1B')
									{{$render->regionFilteredReps($region, $regionID)}}
								@else
									{{$render->regionFiltered($region, $regionID)}}
								@endif
							</div>
							
							<div class="col-sm">
								<label>Year</label>
								{{$qRender->year()}}
							</div>

							<!-- Brand Area -->
							<div class="col-sm">
								<label>Brand</label>
								{{$qRender->brand($brand)}}
							</div>				

							<!-- 1st Pos Area -->
							<div class="col-sm">
								<label> 1st Pos </label>
								{{$qRender->position("second")}}
							</div>				

							<!-- 2st Pos Area -->
							<div class="col-sm">
								<label> 2st Pos </label>
								{{$qRender->position("third")}}
							</div>
							
							<div class="col-sm">
								<label> Currency </label>
								{{$qRender->currency()}}
							</div>
							<div class="col-sm-2">
								<label> Value </label>
								{{$qRender->value()}}									
							</div>
							<div class="col-sm-2">
								<label> &nbsp; </label>
								<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">		
							</div>	
						</div>
					</form>
				</div>
			</div>

			<div class="row justify-content-end mt-2">
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm"></div>
				<div class="col-sm-2" style="color: #0070c0;font-size: 22px;">
					<span style="float: right;"> {{$rName}} - Quarter : {{$form}} - {{$year}} </span>
				</div>
				<div class="col-sm-2">
					<button type="button" class="btn btn-primary" style="width: 100%">
						Generate Excel
					</button>				
				</div>
			</div>	

		</div>
		
	<div class="container-fluid" style="margin-right: 0.5%; margin-left: 0.5%; font-size: 12px">
		<div class="row mt-2">
			<div class="col-sm table-responsive-sm">
				{{$qRender->assemble($matrix, $pRate, $value, $year, $form, $region)}}
			</div>
		</div>
	</div>

	<script type="text/javascript">
		ajaxSetup();
	</script>

@endsection