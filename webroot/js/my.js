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
  url = "/Transactions/getAdhsById/"+id;
  $.get(url)
    .done(function( data ) {
      console.log(data);
      if (data == "0") {
        $("#adh_id").prop('class', 'invalid');
        $("#btn_add").prop('disabled', true);
      } else {
        $("#btn_add").prop('disabled', false);
        $("#adh_name").val(data);
      }
      M.updateTextFields();
  });
}

/*function searchAdhByName() {
  console.log("searchAdhByName");
  var name = $("#adh_name").val();
  console.log(name);
  url = "/Transactions/getAdhsByName/"+name;
  $.get(url)
    .done(function( data ) {
      console.log(data);
      if (data == "0") {
        $("#adh_id").prop('class', 'invalid');
        $("#btn_add").prop('disabled', true);
      } else {
        $("#btn_add").prop('disabled', false);
        //$("#adh_name").val(data);
        $('#adh_name').autocomplete({
          data: {
            "Apple": null,
            "Microsoft": null,
            "Microsoft1": null,
            "Microsoft2": null,
            "Microsoft3": null,
            "Google": 'https://placehold.it/250x250'
          },
        });
      }
      M.updateTextFields();
  });
}*/

/*$(document).ready(function(){
  $('#adh_name').autocomplete({
    data: {
      "Apple": null,
      "Microsoft": null,
      "Google": 'https://placehold.it/250x250'
    },
  });
});*/

$( "#adh_name" ).change(function() {
  var name = $("#adh_name").val();
  console.log("change adh_name "+name);
  //let tag = name.match(/([A-Z]+)\s([A-Z]\w+)/);
  let tag = name.match(/^(.+)\s(.+)$/);
  var lastname=tag[1];
  var firstname=tag[2];
  url = "/Transactions/getAdhsByName?lastname="+lastname+"&firstname="+firstname;
    $.get(url)
      .done(function( data ) {
        const obj = JSON.parse(data);
        var arr = new Array();
        $.each(obj, function(k, v) {
          //console.log(Object.values(v));
          $("#adh_id").val(Object.values(v)[0]);
          $("#statusadh").show();
          console.log(Object.values(v)[1]);
          if (Object.values(v)[1] == "paid") {
            $("#statusadh").html("Adhérent à jour de cotisation");
          }
          if (Object.values(v)[1] == "none") {
            $("#statusadh").html("Adhérent non à jour de cotisation");
          }
          
        });
      })
      .fail(function( data ) {
        $("#statusadh").html("Erreur");
    });
});

$( "#adh_name" ).keypress(function() {
  var name = $("#adh_name").val();
  if (name.length > 1) {
    console.log( "Handler for .keypress() "+name+" called." );
    url = "/Transactions/getAdhsByName?lastname="+name;
    $.get(url)
      .done(function( data ) {
        const obj = JSON.parse(data);
        var arr = new Array();
        $.each(obj, function(k, v) {
          console.log(obj);
          //arr[Object.keys(v)[0]] = null;
          arr[k] = null;
        });
        $('#adh_name').autocomplete({
          data: arr,
        });
        /*if (data == "0") {
          $("#adh_id").prop('class', 'invalid');
          $("#btn_add").prop('disabled', true);
        } else {
          $("#btn_add").prop('disabled', false);
          //$("#adh_name").val(data);
          $('#adh_name').autocomplete({
            data: {
              "Apple": null,
              "Microsoft": null,
              "Microsoft1": null,
              "Microsoft2": null,
              "Microsoft3": null,
              "Google": 'https://placehold.it/250x250'
            },
          });
        }
        M.updateTextFields();*/
    });
  }
});