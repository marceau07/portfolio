/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(document.getElementsByName('avis')[0].value !== '') {
    checkTextAreaMaxLength(document.getElementsByName('avis')[0]);
}

$(".char-textarea").on("keyup", function() {
    checkTextAreaMaxLength(this);
});

/*
 Checks the MaxLength of the Textarea
 -----------------------------------------------------
 @prerequisite:	textBox = textarea dom element
 e = textarea event
 length = Max length of characters
 */
function checkTextAreaMaxLength(textBox, e) {

    var maxLength = parseInt($(textBox).data("length"));


//    if (!checkSpecialKeys(e)) {
        if (textBox.value.length > maxLength - 1)
            textBox.value = textBox.value.substring(0, maxLength);
//    }
    $(".char-count").html(maxLength - textBox.value.length);

    return true;
}
/*
 Checks if the keyCode pressed is inside special chars
 -------------------------------------------------------
 @prerequisite:	e = e.keyCode object for the key pressed
 */
//function checkSpecialKeys(e) {
//    if (e.keyCode != 8 && e.keyCode != 46 && e.keyCode != 37 && e.keyCode != 38 && e.keyCode != 39 && e.keyCode != 40)
//        return false;
//    else
//        return true;
//}
