$(document).ready(function(){
  $('#region').change(function(){
    var regionID = $(this).val();
    ajaxSetup();
    if (regionID != "") {
        
      $.ajax({
        url:"/ajax/adsales/currencyByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#currency').html(output);
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      });

      $.ajax({
        url:"/ajax/salesRepByRegion",
        method:"POST",
        data:{regionID},
        success: function(output){
          $('#salesRep').html(output);
        },
        error: function(xhr, ajaxOptions,thrownError){
          alert(xhr.status+" "+thrownError);
        }
      })
      
    }else{
      var option = "<option> Select Region </option>";
      $('#currency').empty().append(option);
      $('#salesRep').empty().append(option);
    }

  });

});