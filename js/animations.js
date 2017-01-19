var app = angular.module('animations', []);

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

app.animation('.slide-toggle-right-if', [function(){
    return {
        enter: function(element, doneFn){
            var width = $(element).width();
            $(element).width(0);
            $(element).animate({width: width + "px"}, 200, 0, function(){$(element).css('width', 'auto'); doneFn();});
        },
        leave: function(element, doneFn){
            $(element).animate({width:0},200,undefined,doneFn);
        }
    };
}]);