/**
  * Fonction énormissime qui permet de bind l'évènement 'blur'
  * à la fonction jQuery 'live' (pas supporté dans jQuery 1.3).
  */
(function(){
    var special = jQuery.event.special,
        uid1 = 'D' + (+new Date()),
        uid2 = 'D' + (+new Date() + 1);

    jQuery.event.special.focus = {
        setup: function() {
            var _self = this,
                handler = function(e) {
                    e = jQuery.event.fix(e);
                    e.type = 'focus';
                    if (_self === document) {
                        jQuery.event.handle.call(_self, e);
                    }
                };

            jQuery(this).data(uid1, handler);

            if (_self === document) {
                /* Must be live() */
                if (_self.addEventListener) {
                    _self.addEventListener('focus', handler, true);
                } else {
                    _self.attachEvent('onfocusin', handler);
                }
            } else {
                return false;
            }

        },
        teardown: function() {
            var handler = jQuery(this).data(uid1);
            if (this === document) {
                if (this.removeEventListener) {
                    this.removeEventListener('focus', handler, true);
                } else {
                    this.detachEvent('onfocusin', handler);
                }
            }
        }
    };

    jQuery.event.special.blur = {
        setup: function() {
            var _self = this,
                handler = function(e) {
                    e = jQuery.event.fix(e);
                    e.type = 'blur';
                    if (_self === document) {
                        jQuery.event.handle.call(_self, e);
                    }
                };

            jQuery(this).data(uid2, handler);

            if (_self === document) {
                /* Must be live() */
                if (_self.addEventListener) {
                    _self.addEventListener('blur', handler, true);
                } else {
                    _self.attachEvent('onfocusout', handler);
                }
            } else {
                return false;
            }

        },
        teardown: function() {
            var handler = jQuery(this).data(uid2);
            if (this === document) {
                if (this.removeEventListener) {
                    this.removeEventListener('blur', handler, true);
                } else {
                    this.detachEvent('onfocusout', handler);
                }
            }
        }
    };
})();

var my_dictionary = {
  "Ce mail ne semble pas valide" : "This mail doesn't seem to be valid"
}
$.i18n.setDictionary(my_dictionary);

var elementState = 'ready';
var countMail = $('.dynamic_mail').length ;
var countDate = $('.dynamic_date').length ;
var currentMaxId = new Array() ;
currentMaxId['Mail'] = 1 ;
currentMaxId['Date'] = 0 ;
//var loaderAr = new Array() ;
function deleteWidget(w,count)
{
  $('#meeting_input_'+w.toLowerCase()+'_'+count).parent().parent().remove() ;
  if (w == 'Mail') 
    --countMail ;
  else if (w == 'Date')
    --countDate ;
}


var getInputWidget = function(elem,count,w,val)  {
  if(elementState == 'ready'){
    elementState = 'building';

    jQuery.ajax({
      type: "GET",
      url: '/meeting/render'+w+'Input',
      data: { current_id: count, value: val },
      dataType: 'html',
      success: function(result){

       var html = '<tr><th><a href="#" onclick="deleteWidget(\''+w+'\','+count+')"><img src="/images/close_16.png" class="mail_icon" alt="Supprimer" /></a></th><td>';
           html += result;
      
      html += ' </td>' ;

      
      if(countDate == 1)
        $('.dynamic_mail:last').parent().parent().after(html) ;
      else
        $('.dynamic_'+w.toLowerCase()+':last').parent().parent().after(html) ;

       jQuery('#meeting_input_'+w.toLowerCase()+'_'+count).ready(function()
       {
           elementState = 'ready';
       });
      }
    });
  }
};


$(document).ready(function()
{
  countMail = $('.dynamic_mail').length ;
/*
  $('.ui-state-default').live('click', function()
  {
    getInputWidget(this,++countDate,'Date') ;
  }) ;

  $('.dynamic_date').livequery('change', function()
  {
    getInputWidget(this,++countDate,'Date') ;
  }) ;
*/
  $('.dynamic_mail').live('keyup',function()
  {
    if(jQuery(this).parent().parent().nextAll().length == countDate)
    {
      currentMaxId['Mail'] = $('.dynamic_mail:last').attr('id').split('_')[3]*1+1 ;
      getInputWidget(this,currentMaxId['Mail'],'Mail','') ;
      ++countMail ;
    }
  });

  $('.dynamic_mail').live('blur',function()
  {
//    var loader = " <img src='/images/ajax-loader.gif' alt='loader' />" ;
    var not_mail = " <span class='mail_error'><img src='/images/invalid.png' alt='invalid' class='mail_icon' /> "+$.i18n._('Ce mail ne semble pas valide')+".</span>" ;
    var known_mail = " <span class='mail_ok'><img src='/images/valid.png' alt='valid' class='mail_icon' /></span>" ;
//    var unknown_mail = " <span class='mail_unk'><img src='/images/unk.png' alt='unknown' class='mail_icon' /> Ce mail est valide mais n'est pas répertorié dans la base de données.</span>" ;
    var mail_regex = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (mail_regex.test(this.value) == false)
    {
      // c'pas un vrai mail !
      if ($(this).nextAll('span').length == 1)
        $(this).nextAll('span').remove() ;

      $(this).parent().append(not_mail) ;
    }
    else 
    {
      if ($(this).nextAll('span').length == 1)
        $(this).nextAll('span').remove() ;

      $(this).parent().append(known_mail) ; 
    }
  });
  
//  $(window).bind("beforeunload", function(e){alert('on save tout !') ; });

  $('.dynamic_mail').blur() ;
});

$(document).ready(function()
{
  countDate = $('.dynamic_date').length ;

  $('#datee').datepicker(
  { 
      /*dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
      monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Decembre'],
      dayNamesMin: ['Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
      dayNamesShort: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
      monthNamesShort: ['Jan','Fev','Mar','Avr','Mai','Jun','Jul','Aou','Sep','Oct','Nov','Dec'],
      firstDay: 1,*/
      dateFormat: 'dd-mm-yy',
      onSelect: function(dateText, inst) 
      { 
        var max = 0 ;
        for (var i = 0 ; i < $('.dynamic_date').length ; i++)
          if ($('.dynamic_date')[i].id.split('_')[3]*1 > max) max = $('.dynamic_date')[i].id.split('_')[3]*1 ;

        //if (countDate > 0) currentMaxId['Date'] = $('.dynamic_date:last').attr('id').split('_')[3]*1+1 ;
        //else currentMaxId['Date'] = 1 ;
        currentMaxId['Date'] = max+1 ;
        getInputWidget(this,currentMaxId['Date'],'Date',dateText) ;
        ++countDate ;
          //$('#dates_container').append('<div id="date_'+(++dates)+'"><input type="text" name="meeting[input_date_'+dates+']" value="'+dateText+'" /></div>') ; 
      },
      minDate: 0,
      hideIfNoPrevNext: true
   }) ;

  $('.dynamic_mail').blur() ;
  
  $('.help').mouseover(function(e)
  {
    var height = $("#mail_help").height();
    var width = $("#mail_help").width();
    //calculating offset for displaying popup message
    leftVal=e.pageX+150-(width/2)+"px";
    topVal=e.pageY-50-(height/2)+"px";
    $("#mail_help").css({left:leftVal,top:topVal}).show() ;
  }).mouseout(function()
  {
    $('#mail_help').hide() ;
  });

}) ;
