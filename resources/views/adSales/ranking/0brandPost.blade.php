@extends('layouts.mirror')
@section('title', 'Ranking Brand')
@section('head')	
	<script src="/js/rankingBrand.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col">
				<form method="POST" action="{{ route('brandPost') }}" runat="server" onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col">
							<label class="labelLeft bold"> Region: </label>
							@if($errors->has('region'))
								<label style="color: red;">* Required</label>
							@endif
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}
							@else
								{{$render->regionFiltered($region, $regionID)}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Type: </label>
							@if($errors->has('type'))
								<label style="color: red;">* Required</label>
							@else
								{{$render->type()}}
							@endif
						</div>
						<div class="col">
							<label class="labelLeft bold"> Brand: </label>
							@if($errors->has('brands'))
								<label style="color: red">* Required</label>
							@endif
							{{$render->brand($brand)}}
						</div>
						<div class="col">
							<label class="labelLeft bold">Months:</label>
							@if($errors->has('month'))
								<label style="color: red">* Required</label>
							@endif
							{{$render->months()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Currency: </label>
							@if($errors->has('currency'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->currency()}}
						</div>
						<div class="col">
							<label class="labelLeft bold"> Value: </label>
							@if($errors->has('value'))
								<label style="color: red;">* Required</label>
							@endif
							{{$render->value2()}}
						</div>
						<div class="col">
							<label class="labelLeft"><span class="bold"> &nbsp; </span> </label>
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row justify-content-end mt-2">
			<div class="col-sm" style="color: #0070c0;font-size: 22px;">
				<div style="float: right;"> {{$rName}} - {{ucfirst($type)}} Brand Ranking </div>
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row mt-2 justify-content-center">
			<div class="col">
				{{$render->assembler($mtx, $pRate, $value)}}
			</div>
		</div>
	</div>

	<div id="vlau"></div>

	<script type="text/javascript">
		$(document).ready(function(){
			
			var months = <?php echo json_encode($months); ?>;
            var type = "{{$type}}";
            var value = "{{$value}}";
            var currency = <?php echo json_encode($pRate); ?>;
            var region = "{{$region}}";

			ajaxSetup();

			@for($b = 0; $b < sizeof($brand); $b++)
				$(document).on('click', "#"+"{{$brand[$b]['name']}}", function(){

                    var name = $(this).text();

                    if ($("#sub"+"{{$brand[$b]['name']}}").css("display") == "none") {

                        $.ajax({
                            url: "/ajaxRanking/brandSubRanking",
                            method: "POST",
                            data: {name, months, type, value, currency, region},
                            success: function(output){
                                $("#sub"+"{{$brand[$b]['name']}}").html(output);
                                $("#sub"+"{{$brand[$b]['name']}}").css("display", "");
                            },
                            error: function(xhr, ajaxOptions,thrownError){
                                alert(xhr.status+" "+thrownError);
                            }
                        });
                    }else{
                    	$("#sub"+"{{$brand[$b]['name']}}").html(" ");
                        $("#sub"+"{{$brand[$b]['name']}}").css("display", "none");
                    }
                });
            @endfor
		});
	</script>


@endsection