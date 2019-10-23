@extends('layouts.mirror')
@section('title', 'YoY Results')
@section('head')	
	<script src="/js/resultsYoY.js"></script>
	<script src="/js/results.js"></script>
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

	<form method="POST" action="{{ route('yoyMonthExcel') }}">
		@csrf
		<input type="hidden" name="firstPosExcel" value="<?php echo $firstPosExcel; ?>">
		<input type="hidden" name="secondPosExcel" value="<?php echo $secondPosExcel; ?>">
		<input type="hidden" name="thirdPosExcel" value="<?php echo $thirdPosExcel; ?>">
		<input type="hidden" name="regionExcel" value="<?php echo $regionExcel; ?>">
		<input type="hidden" name="valueExcel" value="<?php echo $valueExcel; ?>">
		<input type="hidden" name="yearExcel" value="<?php echo base64_encode(json_encode($yearExcel)); ?>">
		<input type="hidden" name="currencyExcel" value="<?php echo base64_encode(json_encode($currencyExcel)); ?>">
		<input type="hidden" name="title" value="<?php echo $title; ?>">
							<input type="submit" value="Generate" class="btn btn-primary" style="width: 100%">
		
		
	</form>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm">
				<form method="POST" action="{{ route('resultsYoYGet') }}" runat="server"  onsubmit="ShowLoading()">
					@csrf
					<div class="row">
						<div class="col-sm">
							<label>Sales Region</label>
							@if($userLevel == 'L0' || $userLevel == 'SU')
								{{$render->region($salesRegion)}}							
							@else
								{{$render->regionFiltered($salesRegion, $regionID, $special)}}
							@endif
						</div>

						<div class="col-sm">
							<label>Year</label>
							{{ $render->year() }}
						</div>

						<div class="col-sm">
							<label>Brand</label>
							{{ $render->brand($brand) }}
						</div>	

						<div class="col-sm">
							<label> 1st Pos </label>
							{{$render->position("first")}}
						</div>	

						<div class="col-sm">
							<label> 2st Pos </label>
							{{$render->position("second")}}
						</div>	

						<div class="col-sm">
							<label> 3rd Pos </label>
							{{$render->position("third")}}
						</div>	

						<div class="col-sm">
							<label> Currency </label>
							{{$render->currency()}}
						</div>	

						<div class="col-sm">
							<label> Value </label>
							{{ $render->value() }}
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
			<div class="col-sm"></div>
			<div class="col-sm"></div>
			<div class="col-sm-3" style="color: #0070c0;font-size: 22px;">
				<span style="float: right;"> {{$rName}} - Year Over Year : {{$form}} - {{$year}} </span>
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
				{{$render->assemble($matrix,$form,$pRate,$value,$year,$region)}}
			</div>
		</div>
	</div>



	<div id="vlau"></div>

	<script type="text/javascript">

		$(document).ready(function() {

			ajaxSetup();

			$("#excel").click(function(event){

				var firstPosExcel = "<?php echo $firstPosExcel; ?>";
				var secondPosExcel = "<?php echo $secondPosExcel; ?>";
				var thirdPosExcel = "<?php echo $thirdPosExcel; ?>";
				var regionExcel = "<?php echo $regionExcel; ?>";
				var valueExcel = "<?php echo $valueExcel; ?>";
				var yearExcel = "<?php echo base64_encode(json_encode($yearExcel)); ?>";
				var currencyExcel = "<?php echo base64_encode(json_encode($currencyExcel)); ?>";
				var title = "<?php echo $title; ?>";

				var div = document.createElement('div');
				var img = document.createElement('img');
				img.src = '/loading_excel.gif';
				div.innerHTML = "Generating Excel...<br/>";
				div.style.cssText = 'position: absolute; left: 0px; top:0px;  margin:0px;        width: 100%;        height: 100%;        display:block;        z-index: 99999;        opacity: 0.9;        -moz-opacity: 0;        filter: alpha(opacity = 45);        background: white;    background-repeat: no-repeat;        background-position:50% 50%;        text-align: center;        overflow: hidden;   font-size:30px;     font-weight: bold;        color: black;        padding-top: 20%';
				div.appendChild(img);
				document.body.appendChild(div);

				$.ajax({
					xhrFields: {
						responseType: 'blob',
					},
					url: "/generate/excel/yoyMonth",
					type: "POST",
					data: {regionExcel, valueExcel, yearExcel, currencyExcel, title, firstPosExcel, secondPosExcel, thirdPosExcel},
					success: function(result, status, xhr){
						alert("foi");
						/*var disposition = xhr.getResponseHeader('content-disposition');
				        var matches = /"([^"]*)"/.exec(disposition);
				        var filename = (matches != null && matches[1] ? matches[1] : title);

						// The actual download
				        var blob = new Blob([result], {
				            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
				        });
				        var link = document.createElement('a');
				        link.href = window.URL.createObjectURL(blob);
				        link.download = filename;

				        document.body.appendChild(link);

				        link.click();
				        document.body.removeChild(link);*/
				        document.body.removeChild(div);
					},
					error: function(xhr, ajaxOptions,thrownError){
                        alert(xhr.status+" "+thrownError);
				        document.body.removeChild(div);
                    }
				});
			});

		});
	</script>
@endsection