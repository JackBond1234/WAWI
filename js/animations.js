var app = angular.module('directives', []);

app.animation('.slide-toggle-if', [function(){
    return {
        enter: function(element, doneFn){
            var height = $(element).height();
            $(element).height(0);
            $(element).animate({height: height + "px"}, 200, 0, function(){$(element).css('height', 'auto'); doneFn();});
        },
        leave: function(element, doneFn){
            $(element).slideUp(200, doneFn);
        }
    };
}]);