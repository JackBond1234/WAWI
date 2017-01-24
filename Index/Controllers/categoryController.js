angular.module('index').controller('categoryController', function($scope, $log, $window) {
    $scope.Mobile = $window.innerWidth < 599;
    $(window).resize(function(){
        $scope.$apply(function(){
            $scope.Mobile = $window.innerWidth < 599;
        });
    });
    $scope.categories = {loading: true};
    $scope.$log = $log;
    $scope.newCategory = {Name: ""};
    refresh();

    $scope.dragAnimationComplete = function(){
        console.log("Drag Animation Complete!!!");
        var DB = $("#DetailBubble");
        var EL = $(".categories-list-item.ActiveCategory");

        if (DB.length > 0 && EL.length > 0) {
            var body = document.body,
                html = document.documentElement;
            var docheight = Math.max(body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight);

            var proposedTop = EL.offset().top + EL.outerHeight() / 2 - DB.outerHeight() / 2;

            if (proposedTop + DB.outerHeight() > docheight) {
                proposedTop = docheight - DB.outerHeight();
            }
            if (proposedTop < 0) {
                proposedTop = 0;
            }
            DB.clearQueue().animate({top: proposedTop}, 200);
        }
    };

    $scope.categoryClickHandler = function(elementid, elementdata){
        var oldIndex = $scope.root.SelectedCategory;
        for(var index in $scope.categories){
            $scope.categories[index].Selected = false;
        }
        $scope.$apply(function(){
            $scope.categories[elementid].Selected = true;
            $scope.root.SelectedCategory = elementdata.ID;
        });

        //THIS IS UGLY. SEE IF POSSIBLE TO CONVERT THIS TO A CLASS ANIMATION
        if (!$scope.Mobile) {
            setTimeout(function () {
                if (oldIndex != elementdata.ID) {
                    var element = $("#DetailBubble");
                    var width = $(element).width();
                    $(element).width(0);
                    $(element).animate({width: width + "px"}, 200, 0, function () {
                        $(element).css('width', 'auto');
                    });
                }
            }, 0);
        }
    };

    $scope.dndDropCallback = function(list, index, originalindex, item, external){
        if (!external) {
            console.log("Invoked");
            console.log("Invokation index: " + index);
            console.log("Original index: " + originalindex);

            for(var listindex in list){
                for (var catindex in $scope.categories){
                    if (list[listindex].ID == $scope.categories[catindex].ID){
                        list[listindex].Selected = $scope.categories[catindex].Selected;
                        break;
                    }
                }
            }

            //This garbage is necessary because if you simply overwrite the list,
            //there will be an angular digest in which this variable contains the
            //old list and the new list concatenated together for no discernable
            //reason. This causes watchers like the detailbubble positioner to go
            //insane. If there is a better solution, I would love to use it...
            $scope.$apply(function(){
                $scope.categories = [];
            });

            $scope.$apply(function(){
                $scope.categories = list;
            });

        }
        return true;
    };

    $scope.addNewCategory = function(){
        console.log($scope.newCategory.Name);
        if ($scope.newCategory.Name == ""){
            $scope.newCategory.Name = "New Category";
        }

        $.ajax({
            method: "POST",
            url: "../../../ajax.php",
            data: {
                method: "create_category",
                newcategoryname: $scope.newCategory.Name
            },
            complete: function(){
                $scope.root.ShowSecondMenu = false;
                refresh();
            }
        });
    };

    function refresh() {
        $.ajax({
            method: "POST",
            url: "../../../ajax.php",
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
                                Color: data.categories[curcat].Color,
                                Selected: false,
                                Disabled: false
                            });
                            curcat = data.categories[curcat].LowerPriorityCategory;
                        }
                        $scope.categories.push({Name:"Unallocated", Balance: unallocatedBalance, Disabled: true});
                        console.log($scope.categories);
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