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
      const obj = JSON.parse(data);
      var arr = new Array();
      if (data=="[]") {
        $("#adh_id").prop('class', 'invalid');
        $("#btn_add").prop('disabled', true);
      } else {
        $("#btn_add").prop('disabled', false);
        $("#adh_id").prop('class', 'valid');
        $("#adh_lastname").val(obj['lastname']);
        $("#adh_firstname").val(obj['firstname']);
        var strname = obj['ref']+" / "+obj['lastname']+" / "+obj['firstname'];
          $('#adh_fullname').append($('<option>', {
            value: strname,
            text: strname,
            selected: "selected"
          }));
        $('#adh_fullname').change();
        var subcatSelectElem = document.querySelectorAll('#adh_fullname');
        var subcatSelectInstance = M.FormSelect.init(subcatSelectElem, {});
        /*$('#adh_firstname').append($('<option>', {
          value: obj['firstname'],
          text: obj['firstname'],
        }));
        $("#adh_firstname").filter(function() {
          return $(this).text() == obj['firstname'];
        }).prop('selected', true);*/
        //$('#adh_firstname option[value="'++'"]').prop('selected', 'selected');
      }
      M.updateTextFields();
  });
}

function searchAdhByName() {
  console.log("searchAdhByName");
  var lastname = $("#adh_lastname").val();
  url = "/Transactions/getAdhsByName?lastname="+lastname;
  $.get(url)
    .done(function( data ) {
      const obj = JSON.parse(data);
      var arr = new Array();
      if (data=="[]") {
        $("#adh_id").prop('class', 'invalid');
        $("#btn_add").prop('disabled', true);
      } else {
        $("#adh_fullname").empty();
        $.each(obj, function(k, v) {
          var strname = v['ref']+" / "+v['lastname']+" / "+v['firstname'];
          $('#adh_fullname').append($('<option>', {
            value: strname,
            text: strname,
            selected: "selected"
          }));
        });
        $('#adh_fullname').change();
        var subcatSelectElem = document.querySelectorAll('#adh_fullname');
        var subcatSelectInstance = M.FormSelect.init(subcatSelectElem, {});
        /*$("#btn_add").prop('disabled', false);
        $("#adh_id").prop('class', 'valid');
        $("#adh_lastname").val(obj['lastname']);
        $('#adh_firstname').append($('<option>', {
          value: obj['firstname'],
          text: obj['firstname'],
        }));
        $("#adh_firstname").filter(function() {
          return $(this).text() == obj['firstname'];
        }).prop('selected', true);*/
        //$('#adh_firstname option[value="'++'"]').prop('selected', 'selected');
      }
      M.updateTextFields();
  });
}

/*$(document).ready(function(){
  $('#adh_name').autocomplete({
    data: {
      "Apple": null,
      "Microsoft": null,
      "Google": 'https://placehold.it/250x250'
    },
  });
});*/
/*
$( "#adh_lastname" ).change(function() {
  var name = $("#adh_lastname").val();
  console.log("change adh_lastname "+name);
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
          console.log(Object.values(v)[0]);
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
*/

/*
$("#adh_firstname").on("click mousedown mouseup focus blur keydown change",function(e){
  console.log(e);
});

$("#adh_fullname").on("click mousedown mouseup focus blur keydown change",function(e){
  console.log(e);
});*/

$("#date").on("click mousedown mouseup focus blur keydown change",function(e){
  console.log("date");
});

$("#date").on("change",function(e){
  console.log("date change");
  strdate=$("#date").val();
  var pattern =/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;
  if (pattern.test(strdate)) {
    $("#date").prop('class', 'valid');
  } else {
    $("#date").prop('class', 'invalid');
  }
});

/*$( "#adh_firstname" ).select(function() {
  var firstname = $("#adh_firstname").val();
  var lastname = $("#adh_lastname").val();
  console.log("change adh_firstname "+firstname+" "+lastname);
  setAdhStatus(lastname, firstname);
});*/

function testFormAdd() {
  console.log("testFormAdd");
  var myVar = $(".container").find('.invalid');
  if (myVar.length > 0) {
    return false;
  } else {
    return true;
  }
}

function setAdhStatus(lastname, firstname) {
  console.log("setAdhStatus "+firstname+" "+lastname);
  url = "/Transactions/getAdhsByName?lastname="+lastname+"&firstname="+firstname;
    $.get(url)
      .done(function( data ) {
        if (data!="[]") {
          const obj = JSON.parse(data);
          var arr = new Array();
          $.each(obj, function(k, v) {
            $("#adh_id").val(Object.values(v)[2]);
            $("#statusadh").show();
            if (Object.values(v)[3] == "paid") {
              $("#statusadh").html("Adhérent à jour de cotisation");
              $("#statusadh").prop('class', 'green-text');
            }
            if (Object.values(v)[3] == "none") {
              $("#statusadh").html("Adhérent non à jour de cotisation");
              $("#statusadh").prop('class', 'red-text');
            }          
          });
        }
      })
      .fail(function( data ) {
        $("#statusadh").html("Erreur");
    });
}

/*function setAdhStatus(id) {
  console.log("setAdhStatus "+id);
  url = "/Transactions/getAdhsById/"+id;
    $.get(url)
      .done(function( data ) {
        if (data!="[]") {
          const obj = JSON.parse(data);
          var arr = new Array();
          $.each(obj, function(k, v) {
            //console.log(Object.values(v));
            $("#adh_id").val(Object.values(v)[1]);
            $("#statusadh").show();
            if (Object.values(v)[3] == "paid") {
              $("#statusadh").html("Adhérent à jour de cotisation");
              $("#statusadh").prop('class', 'green-text');
            }
            if (Object.values(v)[3] == "none") {
              $("#statusadh").html("Adhérent non à jour de cotisation");
              $("#statusadh").prop('class', 'red-text');
            }          
          });
        }
      })
      .fail(function( data ) {
        $("#statusadh").html("Erreur");
    });
}*/

$( "#adh_fullname" ).change(function() {
  console.log("adh fullname change");
  var fullname = $("#adh_fullname").val();
  console.log(fullname);
  const pattern = /(\d+)\s+\/\s+(.+)\s+\/\s+(.+)/;
  const match = fullname.match(pattern);
  console.log(match);
  $("#adh_firstname").val(match[3]);
  $("#adh_firstname").prop('class', 'valid');
  $("#adh_lastname").val(match[2]);
  $("#adh_lastname").prop('class', 'valid');
  console.log(match[1]);
  $("#adh_id").val(match[1]);
  $("#adh_id").prop('class', 'valid');
  setAdhStatus(match[2], match[3]);
});

/*
$( "#adh_lastname" ).change(function() {
  var lastname = $("#adh_lastname").val();
  console.log("change adh_lastname "+lastname);
  url = "/Transactions/getAdhsByName?lastname="+lastname;
    $.get(url)
      .done(function( data ) {
        const obj = JSON.parse(data);
        $.each(obj, function(k, v) {
          console.log(obj);
          $('#adh_firstname').append($('<option>', {
            value: v['firstname'],
            text: v['firstname']
          }));
          //$('#adh_firstname').val(v['firstname']);
        });
        //var firstname = $("#adh_firstname").val();
        //setAdhStatus(lastname, firstname);
    });
});*/

/*
$( "#adh_lastname" ).keypress(function() {
  var name = $("#adh_lastname").val();
  if (name.length > 1) {
    console.log( "Handler for .keypress() "+name+" called." );
    url = "/Transactions/getAdhsByName?lastname="+name;
    $.get(url)
      .done(function( data ) {
        const obj = JSON.parse(data);
        var arr = new Array();
        $.each(obj, function(k, v) {
          console.log(k);
          $('#adh_fullname').append($('<option>', {
            value: v['lastname']+' '+v['firstname'],
            text: v['lastname']+' '+v['firstname']
          }));
          $('#adh_fullname').append(new Option(v['lastname'], v['lastname'], true, true));
          //console.log(v['firstname']);
          //arr[Object.keys(v)[0]] = null;
          arr[k] = null;
        });
        $('#adh_lastname').autocomplete({
          data: arr,
        });
    });
  }
});
*/

