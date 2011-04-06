$(document).ready( function() {
      $("#link_id").click( 'add_field' );
      var field_count = 1;
    } );

    function add_field()
    {
      var f = $("#div_addfield");
      f.append( '<input type="text" name="data[User][field][' + field_count + ']" />' );
      field_count++;
    }
