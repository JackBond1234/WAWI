angular.module('index').controller('categoryController', function($scope, $log, $window) {
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