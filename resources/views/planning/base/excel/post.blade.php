@extends('layouts.mirror')
@section('title', '@')
@section('head')
    <?php include(resource_path('views/auth.php')); ?>
@endsection
@section('content')

@if($userLevel == 'SU')
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col">
				<?php
					echo '<table class="table table-bordered">';
					for ($s=0; $s < sizeof($newSpreadSheet); $s++) { 
						echo "<tr>";
						for ($t=0; $t < sizeof($newSpreadSheet[$s]); $t++) { 
							echo "<td>".$newSpreadSheet[$s][$column[$t]]."</td>";
						}
						echo "</tr>";
					}
					echo '</table>';
				?>
			</div>
		</div>
	</div>
@else
@endif


<script type="text/javascript">
	$(document).ready(function(){
		$('#PedingStuffByRegions').click( function() {

		    var tableToCheck = $('#tableToCheck').val();

		    ajaxSetup();
		    $.ajax({
                url:"/checkElements/PedingStuffByRegions",
                method:"POST",
                data:{tableToCheck},
                  success: function(output){
                    $('#vlau').html(output);
                  },
                  error: function(xhr, ajaxOptions,thrownError){
                    alert(xhr.status+" "+thrownError);
                }
            });
		});
	});
</script>

@endsection
