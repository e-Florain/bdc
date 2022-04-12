document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.datepicker');
    var options;
    var instances = M.Datepicker.init(elems, options);
    
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.datepicker').datepicker(
      {'format': 'yyyy-mm-dd'}
    );
  });

  $(document).ready(function(){
    $('select').formSelect();
  });

  $(document).ready(function(){
    $('.tooltipped').tooltip();
  });

  $(document).ready(function(){
    $('.collapsible').collapsible();
  });

  $(".dropdown-trigger").dropdown();

  $(document).ready(function(){
    $('.modal').modal();
  });

function filter(controller) {
  var reg = /\/trash\:(\w+)/;
  var resultat = reg.test(window.location.href);
  var url = "/"+controller+"/index_ajax/";
  if (resultat) {
    var trasharg = RegExp.$1;
    url = url+"trash:"+trasharg; 
  } else {
    url = url+"trash:false"; 
  }
  var str = $("#filter_"+controller+"_text").val();
  url = url+"/str:"+str;

  /*url=url+"/years:";
  $('.check'+controller+'Years').each(function(i, obj) {
    if (this.checked) {
      url=url+this.name+";";
    }
  });*/
  console.log(url);

  $.get(url)
    .done(function( data ) {
      //console.log( "Data Loaded: " + data );
      var reg = /(Adhérents[ pros]*);(true|false);(\d+);(.*)/s;
      //console.log(data)
      var resultat = reg.test(data);
      console.log(resultat);
      var strhtml = "";
      if (RegExp.$2 == "false") {
        strhtml = RegExp.$1+" ("+RegExp.$3+")";
      }
      if (RegExp.$2 == "true") {
        strhtml = RegExp.$1+" effacés ("+RegExp.$3+")";
      }
      var strhtml2=RegExp.$4;
      //console.log(strhtml2);
      $("#nbadhs").html(strhtml);
      $("#results").html(strhtml2);
      //window.location.href = "index.php";
  });
}


$("#filter_bdcs_text").keyup(function() {
  filter("bdcs");
});

$('#selectAll').click(function(e){
  console.log("test");
  //var table= $(e.target).closest('table');
  //console.log(table);
  //$('td input:checkbox',table).prop('checked',this.checked);
  $('table [type="checkbox"]').each(function(i, chk) {
    console.log(i);
    $(this).attr('checked',true);
  });
});

function searchAdhById() {
  console.log("searchAdhById");
  var id = $("#adh_id").val();
  url = "/Transactions/getAdhs/"+id;
  $.get(url)
    .done(function( data ) {
      console.log(data);
      $("#adh_name").val(data);
      M.updateTextFields();
  });
}
