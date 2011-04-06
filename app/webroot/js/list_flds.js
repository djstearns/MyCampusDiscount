
$(document).ready(function(){
    
  $('#model-name').live('change', function() {
      var num = document.getElementById('theValue').value;
    if($(this).val().length != 0) {
        $.getJSON('get_flds_ajax',{mids: $(this).val()},
            function(testme) {                      
                        var options = '';
                        $.each(testme, function(index, arr) {
                        options += '<option value="' + index + '">' + index + '</option>';
                            });

                        
                        if(num==0){
                            $('#flds-name').html(options);
                        }else{
                            $('#flds-name'+num).html(options);
                        }
                        
                        $('#addfld').show();
        });
      }
    });
});

function dofunction(arrs) {
  var options = '';
  var num = document.getElementById('theValue').value;
  $.each(arrs, function(index, arr) {
    options += '<option value="' + index + '">' + arr + '</option>';
  });
  $('#flds-name'+num).html(options);
  

}
