var c = document.getElementById("wiresModule");
elemLeft = c.offsetLeft + c.clientLeft;
elemTop = c.offsetTop + c.clientTop;
wires = [];
nb_wires = 0;

// Add event listener for `click` events.
c.addEventListener('click', function(event) {
    var x = event.pageX - elemLeft,
        y = event.pageY - elemTop;

    // Collision detection between clicked offset and element.
    wires.forEach(function(wire) {
        if (y > wire.top && y < wire.top + wire.height 
            && x > wire.left && x < wire.left + wire.width) {
            $.ajax({
                url: 'index.php?page=wires',
                method: 'post',
                dataType: 'json',
                data: {
                    wire_selected: 1,
                    wires: wires,
                    wire: wire,
                    serial_number: $('#serial_number').attr('value'),
                    nb_wires: nb_wires
                },
                success: function(r) {
                    if(r.resultat) {
                        $.ajax({
                           url: 'index.php?page=wires', 
                           method: 'post',
                           dataType: 'json',
                           data: {
                               moduleComplete: 1
                           },
                           complete: function(r) {
                                $('.fa-circle').css('color', 'green');
                                $('.fa-water').css('color', 'green');
                           }
                           
                        });
                    }
                },
                complete: function(r) {

                },
                error: function() {

                }
            });
        }
    });

}, false);

$.ajax({
   url: 'index.php?page=wires',
   method: 'post',
   dataType: 'json',
   data: {
       wires: 1
   },
   complete: function(r) {
        nb_wires = r.responseJSON.wires_number;
        var c = document.getElementById("wiresModule");
        switch(r.responseJSON.wires_number) {
            case 3:
                c.height = 125;
                break;
            case 4:
                c.height = 150;
                break;
            case 5:
                c.height = 175;
                break;
            case 6:
                c.height = 200;
                break;
        }
        var ctx = c.getContext("2d");
        ctx.strokeRect(60, 15, 30, 30*r.responseJSON.wires_number);
        ctx.strokeRect(285, 15, 30, 30*r.responseJSON.wires_number);
        for(i = 1 ; i < r.responseJSON.wires_number + 1 ; i++) {
            console.log(r.responseJSON.wires_colors[i-1]);
            var c = document.getElementById("wiresModule");
            var ctx = c.getContext("2d");
            ctx.beginPath();
            ctx.moveTo(75, 30*i);
            ctx.lineTo(300, 30*i);
            ctx.strokeStyle = r.responseJSON.wires_colors[i-1];
            ctx.lineWidth = 12;
            ctx.stroke();
            wires.push({
                id: i,
                colour: r.responseJSON.wires_colors[i-1],
                width: 350,
                height: 18,
                top: 30*i-10,
                left: 75
            });
        }
        console.log(wires);
   },
   error: function() {
       
   }
});