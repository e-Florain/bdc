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
  
  /*$(document).ready(function(){
    $.getJSON( "/associations/search", function( data ) {
      var obj = {};
      for(var i in data)
      {
        for (var x in data[i]) {
          if (x == "name") {
            obj[data[i][x]] = null;
          }
        }
      }
      auto(obj)
      });
  });*/

function auto(data_arr){
  $("#asso").autocomplete({
      data: data_arr,
      minLength: 1
  });
}

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

/*$('.checkAdhsYears:checkbox').on('change', function() {
  filter("Adhs");
});*/

$("#filter_Adhpros_text").keyup(function() {
  filter("Adhpros");
});

/*$('.checkAdhprosYears:checkbox').on('change', function() {
  filter("Adhpros");
});*/

function checkchanges(type) {
  var x = document.getElementById("filenameadhpros");
  //console.log(x);
  var option = document.createElement("option");
  option.text = "Kiwi";
  /*option.value = "kee";
  option.innerHTML = "ddd";*/
  //x.appendChild(option);
  //x.add(new Option("age"));
  //var myNewOption = new Option("TheText", "TheValue");
  //document.formadhpros.filenameadhpros.options[0] = myNewOption;
  var url = "/cyclos/spinner";
  $.get(url)
    .done(function( data ) {
      $("#spinner").html(data);
  })
  url = "/cyclos/checkchanges/"+type;
  $.get(url)
    .done(function( data ) {
      $("#spinner").html("");
      option.text = data;
      x.add(option);
  });
}

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
  console.log("test");
  var id = $("#adh_id").val();
  url = "/Transactions/getAdhs/"+id;
  $.get(url)
    .done(function( data ) {
      console.log(data);
      $("#adh_name").val(data);
      M.updateTextFields();
  });
}
/*
function applyAdh(controller) {
  $('table [type="checkbox"]').each(function(i, chk) {
    if (chk.checked) {
      //console.log("Checked!", i, chk);
      console.log(chk.name);
      if (chk.name != "") {
        var str = $('#adh_years option:selected').text();
        var i=0;
        var years = "";
        for (i=0;i<(str.length / 4);i++) {
          years = years+str.substr(i*4, 4)+";";
        }
        var url = "/"+controller+"/apply_years/"+chk.name+"/"+years;
        console.log(url);
        $.get(url)
          .done(function( data ) {
            console.log(data);
            window.location.href = "/"+controller+"/index";
            //$("#spinner").html(data);
        });
      }
    }
  });
}*/