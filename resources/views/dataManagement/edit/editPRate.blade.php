@extends('layouts.mirror')

@section('title', '@')

@section('content')

	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-sm-6">
				<div class="card" style="margin-bottom:15%;">
					<div class="card-header">
						<center><h4> Data Management - <b> P-Rate / Currency </b> </h4></center>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							@if(session('response'))
								<div class="alert alert-info">
									{{ session('response') }}
								</div>
							@endif
							
							@if($region)
								@if($currency)
									@if($pRate)
										<form method="POST" action="{{route('dataManagementEditPRatePost')}}">
											@csrf
											<input type="hidden" name="">
        									{{ $render->editPRate2($pRate) }}
        									<div class="row justify-content-end mt-2">
        										<div class="col-sm-3">
        											<input type="submit" class="btn btn-primary" value="Edit" style="width: 100%;">
        										</div>
        									</div>   								
										</form>
									@else
										<div class="alert alert-warning">
  											There is no <strong> P-Rate </strong> to manage yet.
										</div>
									@endif
								@else
									<div class="alert alert-warning">
  										There is no <strong> Currency </strong> to manage yet.
									</div>
								@endif
							@else
								<div class="alert alert-warning">
  									There is no <strong> Region </strong> created yet, please first create a Region to relate with a currency.
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
