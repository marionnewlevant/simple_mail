// basic js from http://www.implementingresponsivedesign.com/ex/ch3/
window.onload = function() {
  var nav = document.getElementById('nav'); 
  var navItem = nav.getElementsByTagName('li');
  //check the display (inline-block = no toggle business)
  var navDisplay = navItem[0].currentStyle ? el.currentStyle['display'] : document.defaultView.getComputedStyle(navItem[0],null).getPropertyValue('display');
  
  if (navDisplay != 'inline-block') {
    var collapse = document.getElementById('nav-collapse');
    
    //toggle class utility function
    var classToggle = function( element, tclass ) {
      var classes = element.className,
      pattern = new RegExp( tclass );
      hasClass = pattern.test( classes );
      //toggle the class
      classes = hasClass ? classes.replace( pattern, '' ) : classes + ' ' + tclass;
      element.className = classes.trim();
    };
    
    classToggle(nav, 'hide');
    classToggle(collapse, 'active');
    collapse.onclick = function() {
      classToggle(nav, 'hide');
      return false;
    }
  }
}
