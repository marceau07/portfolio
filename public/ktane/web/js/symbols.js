const equals = (a, b) => JSON.stringify(a) === JSON.stringify(b);
var list = [];
var list2 = [];

function clickedSymbol(number, image) {
    list.push(number + ',');
    list2.push(number + ',');
    list2.sort();
//    console.log(equals(list, list2));
    if(list.length == 4 && equals(list, list2)) {
        $.ajax({
           url: 'index.php?page=symbols',
           method: 'post',
           data: {
             moduleComplete: 1  
           },
           success: function(r) {
               console.log(r);
               $('.fa-circle').css('color', 'green');
               $('.fa-icons').css('color', 'green');
           },
           error: function(r) {
               console.log(r);
           }
        });
    }
    
    $('#' + image).css('background-color', 'white');
    $('#' + image).css('cursor', 'not-allowed');
}