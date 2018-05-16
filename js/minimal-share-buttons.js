svg4everybody();

domready(function (){

  if (document.querySelectorAll) {

    var els = document.querySelectorAll('a.minimal-share-button');
    for (var i=0; i<els.length; i++) {
        els[i].onclick = function(){
          var w = 640, h = 380;
          var x = (window.screen.width-w)/2;
          var y = (window.screen.height-h)/2;
          var w = window.open('about:blank', 'sharewin', 'width='+w+',height='+h+',left='+x+',top='+y+',menubar=no,location=no,resizable=yes,status=no');
          w.opener = null;
          w.location = this.href;
          return false;
        }
    }

  }

});

