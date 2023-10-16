/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('body').delegate('.c-faq', 'click', function () {
    $('.c-faq').removeClass('c-faq--active');
    $(this).addClass('c-faq--active');
});