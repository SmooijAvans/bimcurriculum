/**
 * Created by Sebastiaan on 06-Apr-16.
 */
$(document).ready(function(){

    $(this).tooltip({
      position: {
        my: "center bottom-20",
        at: "center top",
        track: true,
      }
    });

    $(".ld-toggle-btn").click(function(){
        $(".leerdoelen").toggle("fast");
    })

    $(".draggable" ).draggable({
      revert: "invalid",
      snap: true,
      snapMode: "inner"
    });
    $(".sortable" ).sortable({
      connectWith: ".sortable-list"
    }).disableSelection();;
    $(".droppable" ).droppable({
      accept: "li.draggable"
    });
    $(".accordion" ).accordion({
      heightStyle: "content",
      collapsible: true,
      active: false
    });

    $("[class*=koppelbtn-]").click(function() {
      if($(this).attr("data-warning") == "true") {
        if(!confirm("Dit leerdoel is gekoppeld aan een course. De ontwikkelaar kan voor dit leerdoel al leeruitkomsten gedefinieerd hebben. Deze gaan verloren als u doorgaat (en moeten opnieuw worden aangemaakt). Wenst u dit leerdoel toch aan deze course toe te voegen?")) {
          return;
        }
      }
      var leerdoel = $(this).attr("data-ld");
      var course = $(this).attr("data-course");

      var _context = $(this); //binnen de callback verwijst this niet meer naar het html element maar naar het ajax object, zie regel 32
      $.get(rest_base+"/rest/leerdoel/"+leerdoel+"/redirect/course/"+course, function( data ) {
          if(data == "1") {
            $(".lds-binnen-course").prepend($("<li class='green'>"+_context.parent().prev().html()+"</li>"));
            _context.parent().parent("li").remove();
          }
          else {
            $( ".info" ).html( data );
          }
      });
    });

    //leerdoelen opslaan als op save gedrukt is
    $("[id*='leerdoel_save_knop']").click(function(){
      var postvars = {
        'leerdoelen': new Array(),
      };


      $("[data-toets-id]").each(function(){
        var toetsid = $(this).attr("data-toets-id");
        $(this).children().find("[data-leerdoel-id]").each(function(){
          //console.log(toetsid + " " + $(this).attr("data-leerdoel-id"));
          var leerdoel_arr = {};
          leerdoel_arr[0] = toetsid;
          leerdoel_arr[1] = $(this).attr("data-leerdoel-id");
          postvars['leerdoelen'].push(leerdoel_arr);
          //console.log($(this).attr("data-course-id") + " " + $(this).attr("data-leerdoel-id"));
        });

      });
      $.ajax({
        type: "POST",
        url: rest_base + '/rest/action/savetoetsenmetleerdoelen',
        data: postvars,
        success: function(response) {
          if(response != "1")
            alert (response);
          else {
            alert("Verschoven leerdoelen zijn succesvol opgeslagen.");
            location.reload();
          }
        }
      });
    });

    //eindtermen bij de leerdoelen opslaan
    $("[id*='leerdoel_eindterm_save_knop']").click(function(){
      var postvars = {
        'leerdoelen': new Array(),
      };


      $("[data-eindterm-id]").each(function(){
        var eindtermid = $(this).attr("data-eindterm-id");
        $(this).children().find("[data-leerdoel-id]").each(function(){
          console.log(eindtermid + " " + $(this).attr("data-leerdoel-id"));
          var leerdoel_arr = {};
          leerdoel_arr[0] = eindtermid;
          leerdoel_arr[1] = $(this).attr("data-leerdoel-id");
          postvars['leerdoelen'].push(leerdoel_arr);
        });

      });
      $.ajax({
        type: "POST",
        url: rest_base + '/rest/action/saveeindtermenmetleerdoelen',
        data: postvars,
        success: function(response) {
          if(response != "1")
            alert (response);
          else {
            alert("Verschoven leerdoelen zijn succesvol opgeslagen.");
            location.reload();
          }
        }
      });
    });
});