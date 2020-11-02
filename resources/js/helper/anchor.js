// anchor method for components
export function anchorsPage(id){

    // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
    var time = setTimeout(function(){
        var elmnt = document.getElementById(id);
          if(elmnt !== null){
              console.log('offset ' + elmnt.offsetTop);
              var scrollPos = elmnt.offsetTop;
            }
        // scroll to position with scrollPos variable
          var scrollPos = scrollPos - 100;
          window.scroll({
                    top: scrollPos,
                    left: 0,
                    behavior: 'smooth'
                  });
        }, 100);
  }
  // anchor method for components
  export function anchorsPageAddFriend(id){

      // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
      var time = setTimeout(function(){
          var elmnt = document.getElementById(id);
            if(elmnt !== null){
                console.log('offset ' + elmnt.offsetTop);
                var scrollPos = elmnt.offsetTop;
              }
          // scroll to position with scrollPos variable
            var scrollPos = scrollPos + 600;
            window.scroll({
                      top: scrollPos,
                      left: 0,
                      behavior: 'smooth'
                    });
          }, 100);
    }
  // anchor method for components
  export function anchorsPageDashboardRequest(id){

      // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
      var time = setTimeout(function(){
          var elmnt = document.getElementById(id);
            if(elmnt !== null){
                console.log('offset ' + elmnt.offsetTop);
                var scrollPos = elmnt.offsetTop;
              }
          // scroll to position with scrollPos variable
            var scrollPos = scrollPos + 725;
            window.scroll({
                      top: scrollPos,
                      left: 0,
                      behavior: 'smooth'
                    });
          }, 100);
    }
    // anchor method for components
    export function anchorsPageDashboardResultsFriend(id){
        // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
        var time = setTimeout(function(){
            var elmnt = document.getElementById(id);
              if(elmnt !== null){
                  console.log('offset ' + elmnt.offsetTop);
                  var scrollPos = elmnt.offsetTop;
                }
            // scroll to position with scrollPos variable
              var scrollPos = scrollPos + 1000;
              window.scroll({
                        top: scrollPos,
                        left: 0,
                        behavior: 'smooth'
                      });
            }, 100);
      }
  // anchor method for components
  export function anchorsPageNoScroll(id){

      // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
      var time = setTimeout(function(){
          var elmnt = document.getElementById(id);
            if(elmnt !== null){
                console.log(elmnt.offsetTop);
                var scrollPos = elmnt.offsetTop;
              }
          // scroll to position with scrollPos variable
            var scrollPos = scrollPos - 100;
            window.scroll({
                      top: scrollPos,
                      left: 0,
                      behavior: 'auto'
                    });
          }, 100);
    }
    // anchor method for components
    export function anchorsPageChat(id){

        // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
        var time = setTimeout(function(){
            var elmnt = document.getElementById(id);
              if(elmnt !== null){
                  var scrollPos = elmnt.offsetTop;
                }
            // scroll to position with scrollPos variable
              var scrollPos = scrollPos + 350;
              window.scroll({
                        top: scrollPos,
                        left: 0,
                        behavior: 'smooth'
                      });
            }, 100);
      }
      // anchor method for components
      export function anchorsPageNews(id){

          // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
          var time = setTimeout(function(){
              var elmnt = document.getElementById(id);
              if(elmnt !== null){
                console.log(elmnt);
                    var scrollPos = elmnt.offsetTop;
                      // scroll to position with scrollPos variable
                        var scrollPos = scrollPos + 900;
                        window.scroll({
                                  top: scrollPos,
                                  left: 0,
                                  behavior: 'smooth'
                                });
                    }

            }, 100);
        }
  // anchor method for components
  export function anchorsPageInvoiceView(id){

      // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
      var time = setTimeout(function(){
          var elmnt = document.getElementById(id);
          if(elmnt !== null){
                var scrollPos = elmnt.offsetTop;
                  // scroll to position with scrollPos variable
                    var scrollPos = scrollPos + 475;
                    window.scroll({
                              top: scrollPos,
                              left: 0,
                              behavior: 'smooth'
                            });
                }

        }, 100);
    }
  // anchor method for invoice components
  export function anchorsPageInvoice(id){

      // set inpage anchor at #anchor element when getVehicles and finishJob messages are loaded
      var time = setTimeout(function(){
          var elmnt = document.getElementById(id);
            if(elmnt !== null){
                console.log(elmnt.offsetTop);
                var scrollPos = elmnt.offsetTop;
              }
          // scroll to position with scrollPos variable
            var scrollPos = scrollPos + 2000;
            window.scroll({
                      top: scrollPos,
                      left: 0,
                      behavior: 'smooth'
                    });
          }, 100);
    }
