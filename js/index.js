$(document).ready(function(){
    var app = angular.module('index', ['dndLists', 'ngAnimate']);

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

    app.directive('clickOff', function($parse, $document) {
        var dir = {
            compile: function($element, attr) {
                // Parse the expression to be executed
                // whenever someone clicks _off_ this element.
                var fn = $parse(attr["clickOff"]);
                return function(scope, element, attr) {
                    // add a click handler to the element that
                    // stops the event propagation.
                    element.bind("click", function(event) {
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
        return dir;
    });

    app.directive('ngVisible', function($parse){
        var dir = {
            compile: function ($element, attr) {
                var fn = $parse(attr["ngVisible"]);
                return function (scope, element, attr) {
                    scope.$watch(attr["ngVisible"], function(visibility) {
                        element.css("visibility", visibility ? "visible" : "hidden");
                    });
                }
            }
        };
        return dir;
    });

    app.controller('categoryController', function($scope, $log, $window) {
        $scope.Mobile = $window.innerWidth < 599;
        $(window).resize(function(){
            $scope.$apply(function(){
                $scope.Mobile = $window.innerWidth < 599;
            });
        });
        $scope.categories = {loading: true};
        $scope.$log = $log;
        refresh();

        $scope.categoryClickHandler = function(elementid, elementdata){
            for(var index in $scope.categories){
                $scope.categories[index].Selected = false;
            }
            $scope.$apply(function(){
                $scope.categories[elementid].Selected = true;
                $scope.root.SelectedCategory = elementdata.ID;
            });
            //var DB = $("#DetailBubble");
            //var EL = $(".categories-list-item").eq(elementid);
            //
            //var body = document.body,
            //    html = document.documentElement;
            //var docheight = Math.max(body.scrollHeight, body.offsetHeight,
            //    html.clientHeight, html.scrollHeight, html.offsetHeight);
            //
            //DB.html(DB.html() + "<br>TEST");
            //
            //var proposedTop = EL.offset().top + EL.outerHeight()/2 - DB.outerHeight()/2;
            //
            //if(proposedTop + DB.outerHeight() > docheight) {
            //    proposedTop = docheight - DB.outerHeight();
            //}
            //DB.css({top: proposedTop});
        };

        $scope.dndDropCallback = function(list, index, originalindex, item, external){
            if (!external) {
                console.log("Invoked");
                console.log("Invokation index: " + index);
                console.log("Original index: " + originalindex);

                $scope.$apply(function(){
                    var newlist = [];
                    for(var listindex in list){
                        for (var catindex in $scope.categories){
                            if (list[listindex].ID == $scope.categories[catindex].ID){
                                list[listindex].Selected = $scope.categories[catindex].Selected;
                                break;
                            }
                        }
                    }

                    //var newlist = list;
                    $scope.categories = list;
                });

            }
            return true;
        };

        function refresh() {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    method: "refresh_index"
                },
                success: function(data){
                    $scope.$apply(function() {
                        console.log(data);
                        if (data.success == true) {
                            var curcat = data.firstcategory;
                            $scope.categories = [];
                            var unallocatedBalance = data.globals.TotalBalance;
                            while (curcat != null && typeof curcat != "undefined"){
                                unallocatedBalance -= data.categories[curcat].Balance;
                                $scope.categories.push({
                                    ID: curcat,
                                    Name: data.categories[curcat].Name,
                                    Balance: data.categories[curcat].Balance,
                                    Selected: false,
                                    Disabled: false
                                });
                                curcat = data.categories[curcat].LowerPriorityCategory;
                            }
                            $scope.categories.push({Name:"Unallocated", Balance: unallocatedBalance, Disabled: true});
                        }
                        else {
                            console.log(data);
                            $scope.categories.error = true;
                        }
                    });
                },
                error: function(data){
                    $scope.$apply(function(){
                        console.log(data);
                        $scope.categories.loading = false;
                        $scope.categories.error = true;
                    });
                }
            });
        }
    });
    app.controller('applicationController', function($scope, $log, $window){
        $scope.Mobile = $window.innerWidth < 599;
        $(window).resize(function(){
            $scope.$apply(function(){
                $scope.Mobile = $window.innerWidth < 599;
                if (!$scope.Mobile && $scope.root.ApplicationView == "Categories") {$scope.root.ApplicationView = "Reports";}
            });
        });
    });
    app.controller('detailController', function($scope, $log, $window){
        $scope.Mobile = $window.innerWidth < 599;
        $(window).resize(function(){
            $scope.$apply(function(){
                $scope.Mobile = $window.innerWidth < 599;
            });
        });

        $scope.$watch("root.SelectedCategory", function(SelectedCategory) {
            $.ajax({
                method: "POST",
                url: "ajax.php",
                data: {
                    method: "get_category",
                    catid: SelectedCategory
                },
                success: function (data) {
                    console.log(data);
                    if (data.Success) {
                        $scope.$apply(function () {
                            $scope.Details = [];
                            $.each(data, function(elem){
                                $scope.Details.push(elem + " : " + data[elem]);
                            });
                        });
                    }
                }
            });
        });
    });
    app.controller('rootController', function($scope, $log, $window){
        $scope.root = {ApplicationView: $window.innerWidth < 599 ? "Categories" : "Reports",
                       ShowAppMenu: false,
                       ShowDetailMenu: false,
                       ShowUserMenu: false,
                       SelectedCategory: -1};
    });
});