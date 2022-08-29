
var navbarNav = $('.desktop.navbar-nav'),
    navbarBrand = navbarNav.find('.navbar-brand'),
    products = navbarNav.find('.products'),
    customlabs = navbarNav.find('.customlabs'),
    contactus = navbarNav.find('.contactus'),
    newslink = navbarNav.find('.newslink'),
    navDropdowns = $('.nav-dropdowns'),
    leftNav1 = navDropdowns.find('.menu-1'),
    leftNav2;

var wallcovering_1 = function(){
  // desktop
  leftNav1.removeClass('fade');
  navDropdowns.find('.menu-2').addClass('fade');
  navDropdowns.find('.menu-3').addClass('fade');

  navDropdowns.find('ul.menu-11').addClass('fade');
  navDropdowns.find('ul.menu-22').addClass('fade');
  navDropdowns.find('ul.menu-33').addClass('fade');

  var rightNav = navDropdowns.find('ul.wallcovering').removeClass('fade');
  navDropdowns.removeClass('fade hide');
  
  navbarNav.find('.nav-item').removeClass('active');
  products.parent().addClass('active');

  // var hh = Math.ceil(rightNav.outerHeight(true));
  var hh = Math.floor(rightNav.outerHeight(true));
  navDropdowns.css('height', hh+'px');
  
}

var wallcovering_2 = function(){
  // mobile
  var mobileHamburger = $('#mobile-hamburger'),
      navbarToggler1 = $('#navbarToggler1'),
      mobileNavbar = navbarToggler1.find('ul.mobile.navbar-nav');

  mobileHamburger.click();

  setTimeout(function() {  
    mobileNavbar.find('li').eq(0).find('a').click();
  }, 500);
}

var checkMediaWidth = function(){
  var platform = 'desktop';
  // @media screen and (max-width: 992px) and (min-device-width: 320px) 
  if (window.matchMedia('(max-width: 992px)').matches && window.matchMedia('(min-width: 320px)').matches) {
    platform = 'mobile';
  }
  
  return platform;
}


function navInit(){
  
  products.on('mouseenter', function(){
    wallcovering_1();
  });

  customlabs.on('mouseenter', function(){
    navDropdowns.addClass('fade');
    
    navbarNav.find('.nav-item').removeClass('active');
    $(this).parent().addClass('active');

    navDropdowns.css('height', '');
  }).on('mouseleave', function(){
    $(this).parent().removeClass('active');
  });

  contactus.on('mouseenter', function(){
    leftNav2 = navDropdowns.find('.menu-2').removeClass('fade');
    navDropdowns.find('.menu-1').addClass('fade');
    navDropdowns.find('.menu-3').addClass('fade');

    navDropdowns.find('ul.menu-11').addClass('fade');
    navDropdowns.find('ul.menu-22').addClass('fade');
    navDropdowns.find('ul.menu-33').addClass('fade');
    navDropdowns.find('ul.contact').removeClass('fade');
    navDropdowns.removeClass('fade hide');    

    navbarNav.find('.nav-item').removeClass('active');
    $(this).parent().addClass('active');

    var rightNav = navDropdowns.find('ul.wallcovering');
    
    var hh = Math.floor(rightNav.outerHeight(true));
    navDropdowns.css('height', hh+'px');

    // var hh = Math.ceil(rightNav.outerHeight(true));
    //var hh = Math.floor(rightNav.outerHeight(true));
    //leftNav2.css('height', hh+'px');
  });

  newslink.on('mouseenter', function(){

    navDropdowns.find('.menu-3').removeClass('fade');    
    navDropdowns.find('.menu-1').addClass('fade');
    navDropdowns.find('.menu-2').addClass('fade');

    navDropdowns.find('ul.menu-11').addClass('fade');
    navDropdowns.find('ul.menu-22').addClass('fade');
    navDropdowns.find('ul.menu-33').addClass('fade');
    navDropdowns.find('ul.news').removeClass('fade');
    navDropdowns.removeClass('fade hide');    

    navbarNav.find('.nav-item').removeClass('active');
    $(this).parent().addClass('active');

    var rightNav = navDropdowns.find('ul.wallcovering');
    
    var hh = Math.floor(rightNav.outerHeight(true));
    navDropdowns.css('height', hh+'px');

    // var hh = Math.ceil(rightNav.outerHeight(true));
    //var hh = Math.floor(rightNav.outerHeight(true));
    //leftNav2.css('height', hh+'px');
  });

  navbarBrand.mouseenter(function() {
    navDropdowns.addClass('fade');
    
    navDropdowns.css('height', '');
    //console.log(111);
  });
  navDropdowns.mouseleave(function() {
    navDropdowns.addClass('fade');
   // leftNav1.removeAttr('style');
    // leftNav1.css('height', '');
    
    navDropdowns.css('height', '');
    navbarNav.find('.nav-item').removeClass('active');
  });

  $(document).mouseleave(function () {
    navDropdowns.addClass('fade');
    
    navDropdowns.css('height', '');
    navbarNav.find('.nav-item').removeClass('active');
  });


  // view side nav
  navDropdowns.find('a.wallcovering').on('mouseenter', function(){
    navDropdowns.find('ul.menu-11').addClass('fade');
    navDropdowns.find('ul.wallcovering').removeClass('fade');
  });
  navDropdowns.find('a.faux_leather').on('mouseenter', function(){
    navDropdowns.find('ul.menu-11').addClass('fade');
    navDropdowns.find('ul.faux_leather').removeClass('fade');
  });
  navDropdowns.find('a.sheers_drapery').on('mouseenter', function(){
    navDropdowns.find('ul.menu-11').addClass('fade');
    navDropdowns.find('ul.sheers_drapery').removeClass('fade');
  });

  // https://stackoverflow.com/questions/43707615/bootstrap-css-dropdown-menu-animation


  // footer
  setTimeout ( function () {
    $('footer.page-footer').removeClass('hide');
  }, 250);


  var mobile = $('nav.navbar .mobile');
  mobile.find('.dropdown-menu a.dropdown-toggle').on('click tap', function(e) {
    if (!$(this).next().hasClass('show')) {
      $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
      //console.log(111);
    }
    var $subMenu = $(this).next(".dropdown-menu");
    $subMenu.toggleClass('show');


    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
      $('.dropdown-submenu .show').removeClass("show");
      //console.log(222);
    });


    return false;
  });
}

function welcomePage(){
  var carasoul_1 = $('.slider-nav-welcome .owl-carousel').owlCarousel({
      margin: 0,
      dots: true,
      nav: false,
      //navText:["<div class='nav-btn prev-slide'></div>",
      //"<div class='nav-btn next-slide'></div>"],
      responsive: {
          0: {
            items: 1,
            autoplay: true,
            autoplaySpeed: 1000,
            autoplayTimeout: 7000,
            autoplayHoverPause: true,
            loop: true,
          }
      }
  });
}

function PLPpage(){
  // close modal
  var modalContainer = $('#modal-product-container').on('hide.bs.modal', function () {
      $('#plp-modal-img1').attr('src', '');
      modalContainer.find('.container-fluid ul').addClass('hide');
      modalContainer.find('#plp-sample-btn button').prop("disabled", false);
  });
  
  var bc = $('ol.breadcrumb');
  var bcA = bc.find('.breadcrumb-item.wallcovering a');

  bcA.on('click tap', function(){
    var platform = checkMediaWidth();

    if(platform=='desktop'){
      wallcovering_1();
    }else{
      // mobile
      wallcovering_2();
    }

    return false;
  });
}

function PDPpage(){
  
  var detailSpecDiv  = $('#detailSpecDiv');
  var skuThumbDiv = $('.skuThumbDiv');
  var thumbnailContainer  = $('.thumbnailContainer');
  var seenin  = $('.seenin');

  var carasoul = $('.similar-product .owl-carousel').owlCarousel({        
    dots: false,
    autoplay: false,    
    loop: false,        
    nav: false,
    slideBy: 1,
    responsive: {
      0: {
        items: 1,
      },
      576: {
        items: 2,
      },
      768: {
        items: 4
      }
  }
});

  var showMoreColors = function(){
    thumbnailContainer.toggleClass('reveal');
    $('.skuStatDiv').toggleClass('reveal');
  }

  $('#showMoreColorBtn').on('click', function(){
    showMoreColors();
  });

  $('a.pinterest').on('click tap', function(){
    
    var skuTitle = $('.skuTitle'), //.text(),
        descript;

    if(skuTitle.find('a').length > 0){
      // sku
      descript = 'Innovations | ' + skuTitle.find('a').text() + ' | ' + $('.skuUL.row .active .itemNum').text();
    }else{
      // main pdp
      descript = 'Innovations | ' + skuTitle.text();
    }

    var mediaUrl = $('#download-image-2').data('downimage'),
        shareUrl = $(this).data('url');

    var url_1 = '',
        url_2 = 'https://pinterest.com/pin/create/button/?url=',
        shareTxt = '&description=' + encodeURIComponent(descript) + '&media=' + encodeURIComponent(mediaUrl);

    url_1 = url_2 + encodeURIComponent(shareUrl) + shareTxt;

    popitup(url_1, 600, 600);
    return false;
  });

  // gallery - desktop/mobile
  var modalPdpContainer = $('#modal-pdp-gallery-container');
  var myOwl = modalPdpContainer.find('.owl-carousel');
  var carasoul_1 = $('.slider-nav-pdp .owl-carousel').owlCarousel({
      margin: 10,
      dots: false,
      nav: true,
      slideBy: 5,
      navText:['<img src="/images/icons/arrow1.svg"/>','<img src="/images/icons/arrow2.svg"/>'],
      responsive: {
          0: {
            items: 5
          }
      }
  });
  carasoul_1.find('.owl-item').unbind('click').bind('click', function() {
    var ii = $(this).index();

      myOwl.owlCarousel({
        margin: 10,
        dots: false,
        nav: true,
        navText:['<img src="/images/icons/arrow1.svg"/>','<img src="/images/icons/arrow2.svg"/>'],
        responsive: {
            0: {
              items: 1
            } 
        },
        startPosition: ii,
      });
  });
  


  // sku - mobile
  var myOwl_2 = skuThumbDiv.find('.owl-carousel');
  var carasoul_2 = skuThumbDiv.find('.owl-carousel').owlCarousel({
      margin: 10,
      nav: false,
      dots: false,
      responsive: {
          0: {
            items: 5
          }
      }
  });
  carasoul_2.find('.owl-item').unbind('click').bind('click', function() {
    var ii = $(this).index();

      myOwl_2.owlCarousel({
        margin: 10,
        dots: false,
        nav: true,
        navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
        responsive: {
            0: {
              items: 1
            } 
        },
        startPosition: ii,
      });
  });


  modalPdpContainer.on('hide.bs.modal', function () {
    
    modalPdpContainer.find('.owl-carousel').trigger('destroy.owl.carousel');
    
    /*var owlItems = modalPdpContainer.find('.owl-item');
    owlItems.each(function(item) {
      $(this).removeClass('active');
      $(this).removeClass('current');
    });*/
  });

  var modalPdpYardageContainer = $('#modal-pdp-yardage-container');
  modalPdpYardageContainer.on('hide.bs.modal', function () {
    $('#length-of-room').val('');
    $('#width-of-room').val('');
    $('#sq-of-room').val('');
    modalPdpYardageContainer.find('.result').addClass('hide');
  });
  

  // load sequence
  var mainImg = detailSpecDiv.find('.imgContainer img');
    //mainImg.on('load', function(){
    //setTimeout ( function () {
    //console.log('load-1');

    setTimeout ( function () {
      //console.log('load-2');
      skuThumbDiv.removeClass('hide');//.addClass('fade');
    }, 250);

    setTimeout ( function () {
      detailSpecDiv.find('.slider-nav-pdp.desktop').removeClass('hide'); //.addClass('fade');
      seenin.removeClass('hide');
    }, 300);
    setTimeout ( function () {
      $('.copy-container').removeClass('hide'); //.addClass('fade');
    }, 500);

    // check if color skus > 10, then max-height:270px; 
    /*if(thumbnailContainer.hasClass('cover')){
      
        // var li_0 = thumbnailContainer.find('li').eq(0).outerHeight(true);
        // var li_5 = thumbnailContainer.find('li').eq(5).outerHeight(true);
        // var ul = thumbnailContainer.find('ul').offset().top;
        // var tc = thumbnailContainer.outerHeight(true);
        // var liParent = thumbnailContainer.find('ul').offset().top;
        
        var imgX = thumbnailContainer.find('li.more-colors').find('img');//.outerHeight(true);

        imgX.one("load", function(){

          setTimeout ( function () {
            var h1 = thumbnailContainer.find('h1').outerHeight(true);
            var myLi = thumbnailContainer.find('li.more-colors').offset().top;
            //var imgH = $(this).outerHeight(true);
            var pad = 9;

            var hh = myLi - h1 -pad;
            hh = Math.floor(hh);
            //console.log(imgH);

            thumbnailContainer.css('max-height',hh);
            // http://innovations/item/alchemy+
            // http://innovations/item/barcelona
            // http://innovations/item/district
          }, 10);

        });

    }*/

  //}, 5);
  //});
}

function findArep(){
  $(document).on('change','#country',function(){
    var cc = $(this).val();
    console.log(cc);
    if(cc=='USA'){
      $('#state').prop('required',true).show();
      $("label[for='state']").show();
    }else{
      $('#state').prop('required',false).hide().val('');
      $("label[for='state']").hide();
    }

  });
}

function showroomsInit(){

  var nav = $('#showrooms-container').find('.nav');
  nav.find('li a').on('click tap', function(e) {


    var topNavH = 10; //$('nav.navbar').outerHeight();
    var myId = $(this).attr('href');

    scrollIt(topNavH, myId);

    //return false;
  });
}

function customlabsInit(){
  //console.log(123);

  // past projects
  var fabricName = $('.fabricName');
  var modalCustomlabsContainer = $('#modal-customlabs-container');
  var myOwl = modalCustomlabsContainer.find('.owl-carousel');

  fabricName.on('click tap', function(){
    var ii = $(this).parents('li').index();

    myOwl.owlCarousel({
      margin: 10,
      dots: false,
      nav: false,
      //navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
      responsive: {
          0: {
            items: 1
          } 
      },
      startPosition: ii,
    });
    
    //return false;
  });

  modalCustomlabsContainer.on('hide.bs.modal', function () {
    modalCustomlabsContainer.find('.owl-carousel').trigger('destroy.owl.carousel'); 
  });

}

function homeInit() {  
  var payBtn = $('.active.payBtn');
  payBtn.on('click tap', function(){
    var payUrl = "/pay/" + $(this).attr('href');
    var dataObj = $("meta[name=csrf-token]").attr("content");    

    $.ajax({ type: "POST",
        //async: false,
        data: {  _token: dataObj },
        url: payUrl ,
        //dataType: "json", // << IF jSON
        success: function(data){
          //console.log(data);
          window.open(data, '_blank');
        }
    });

    return false;
  });  
}

$(document).ready(function(){
  // main top, footer nav
  navInit(); 

  var welcome = document.getElementById("welcomecontainer");
  var pdp = document.getElementById("itemcontainer");
  var plp = document.getElementById("productcontainer");
  var findrep = document.getElementById("find-a-rep-container");
  var showrooms = document.getElementById("showrooms-container");
  var customlabs = document.getElementById("customlabs");
  var home = document.getElementById("home");

  if(welcome){
    welcomePage();
  }else if(plp){
    PLPpage();
  }else if(pdp){
    PDPpage();
  }else if(findrep){
    findArep();
  }else if(showrooms){
    showroomsInit();
  }else if(customlabs){
    customlabsInit();
  }else if(home){
    homeInit();
  }

});


function scrollIt(topNavH,myId){

  var topInt = $(myId).offset().top - topNavH + 2; // add small xtra pad

  $('html, body').animate({
      scrollTop: topInt
  }, 300);
}

function popitup(url, ww, hh) {

  var newwindow = window.open(url,'myPopup','width='+ ww +',height=' + hh);
  if (window.focus) {newwindow.focus()}
  
  return false;
};