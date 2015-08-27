jQuery( document ).ready(function() {
  jQuery(function(){
    jQuery('dfn').each(function(){
      var timer;
      var thisTooltip = jQuery(this);
      var theTerm = thisTooltip.html();
      var theDefinition = thisTooltip.attr('title');
      var tooltipTemplate =
          '<span class="dfnTooltip">' +
            '<span class="dfnTooltipTerm">' + theTerm + '</span>' +
            '<span class="dfnTooltipDefinition">' + theDefinition + '</span>' +
          '</span>';
      
      thisTooltip.attr('title', '')
        .addClass('hasTooltip')
        .append(tooltipTemplate)
        .hover(
          function(e){
            if (timer) { clearTimeout(timer); timer = null; }
            
            timer = setTimeout(function(){
              var position = thisTooltip.position();
              console.log(position.top);
              console.log(position.left);
              thisTooltip
                .children('.dfnTooltip')
                .css({ 'top': position.top + 22, 'left': position.left + 22 })
                .fadeIn(200)
            }, 200);
          },
          function(){
            if (timer) { clearTimeout(timer); timer = null; }
            
            timer = setTimeout(function(){
              thisTooltip.children('.dfnTooltip').fadeOut();
            }, 200);
          }
        )
        .children('.dfnTooltip').hide();
    });
  });
});