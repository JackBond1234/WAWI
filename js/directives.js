var app = angular.module('directives', []);

app.directive('clickOff', function($parse, $document) {
    return {
        compile: function($element, attr) {
            // Parse the expression to be executed
            // whenever someone clicks _off_ this element.
            var fn = $parse(attr["clickOff"]);
            return function(scope, element, attr) {
                // add a click handler to the element that
                // stops the event propagation.
                element.bind("click", function(event) {
                    console.log("Stopping propagation");
                    event.stopPropagation();
                });
                angular.element($document[0].body).bind("click", function(event) {
                    scope.$apply(function() {
                        fn(scope, {$event:event});
                    });
                });
            };
        }
    };
});

app.directive('ngVisible', function($parse){
    return {
        compile: function ($element, attr) {
            var fn = $parse(attr["ngVisible"]);
            return function (scope, element, attr) {
                scope.$watch(fn, function(visibility) {
                    element.css("visibility", visibility ? "visible" : "hidden");
                });
            }
        }
    };
});