/**
 * Created by Sebastiaan on 06-Apr-16.
 */
$(document).ready(function(){

    //var rest_base = "http://localhost:8090/curriculum/app/web/app_dev.php";

    $(".ld-toggle-btn").click(function(){
        $(".leerdoelen").toggle("fast");
    })

    $(".draggable" ).draggable(
      snap: true
    );
    $(".droppable" ).droppable();
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
      alert('alive');
      var postvars = {
        'leerdoelen': new Array(),
      };

      $("[data-course-id]").each(function(){
        var courseid = $(this).attr("data-course-id");
        $(this).children().find("[data-leerdoel-id]").each(function(){
          console.log(courseid + " " + $(this).attr("data-leerdoel-id"));
          var leerdoel_arr = {};
          leerdoel_arr[0] = courseid;
          leerdoel_arr[1] = $(this).attr("data-leerdoel-id");
          postvars['leerdoelen'].push(leerdoel_arr);
          //console.log($(this).attr("data-course-id") + " " + $(this).attr("data-leerdoel-id"));
        });

      });
      //data['bar'] = "foo";
      $.ajax({
        type: "POST",
        url: rest_base + '/rest/action/savecoursesmetleerdoelen',
        data: postvars,
        success: function(response) {
          alert (response);
        }
      });
    });
});
