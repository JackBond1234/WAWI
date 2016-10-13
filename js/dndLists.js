angular.module('dndLists', [])
    .directive('dndDraggable', ['$timeout', "$parse", function($timeout, $parse){
        return function(scope, element, attr){
            element.find(".category-grip").mousedown(function(event){
                event.preventDefault();
                handleMouseDown(event);
            });

            element.data("dndData", angular.toJson(scope.$eval(attr.dndDraggable)));

            // If the dnd-disable-if attribute is set, we have to watch that
            if (attr.dndDisableIf) {
                scope.$watch(attr.dndDisableIf, function(disabled) {
                    //element.attr("draggable", !disabled);
                });
            }

            element.on("mousedown touchstart", handleMouseDown);

            function handleMouseDown(event){
                if (!$parse(attr.dndDisableIf)(scope, {event: event})) {
                    event = event.originalEvent || event;
                    console.log(event.type);
                    var touchedelement;
                    if (event.type == "touchstart") {
                        console.log(event.touches[0].pageY - $(window).scrollTop());
                        touchedelement = $(document.elementFromPoint(event.touches[0].pageX, event.touches[0].pageY - $(window).scrollTop()));
                    }
                    if (event.which == 1 || (event.type == "touchstart" && (touchedelement.is(".category-grip") || (touchedelement.is(element) && event.touches[0].pageX - touchedelement.offset().left < 40)))) {
                        if (!$(".dndDragging").is(".dndAnimating")) {
                            $(".dndDragging").removeClass("dndDragging");
                            console.log(element);
                            element.addClass("dndDragging");
                            console.log($(".categories-list-item").index(element));
                            element.data("originalindex", $(".categories-list-item").index(element));
                        } else {
                            console.log("Queueing unclick because dndDragging already exists, even though we have a click down");
                            $(".dndDragging").addClass("dndQueueUnclick");
                        }
                    }
                }
            }

            element.on("mouseenter", mouseEnterHandler);

            element.on("touchmove", function(event){
                event = event.originalEvent || event;
                if ($(".dndDragging").length > 0){
                    event.customtarget = document.elementFromPoint(event.touches[0].pageX, event.touches[0].pageY - $(window).scrollTop());
                    if (!$(event.customtarget).is(".dndDraggable")) {
                        mouseEnterHandler(event);
                    }
                }
            });

            function mouseEnterHandler(event) {
                console.log(event.type);
                event = event.originalEvent || event;
                event.preventDefault();
                if (!$(event.customtarget).is(".dndDisabled") && !$(event.target).is(".dndDisabled")) {
                    element.addClass("dndDragover");
                    var listItemNode = event.customtarget || event.target;
                    if (!$(".dndDragging").is(".dndQueueUnclick")) {
                        if ($(".dndDragging").prevAll().is($(listItemNode))) {
                            console.log("Mouse enter says move up (trigger move up)");
                            moveDraggingObject(listItemNode, "up");
                        } else if ($(".dndDragging").nextAll().is($(listItemNode))) {
                            console.log("Mouse enter says move down (trigger move down)");
                            moveDraggingObject(listItemNode, "down");
                        }
                    }
                }
            }

            function getAdjacent(source, direction){
                if (direction == "up"){
                    return source.prev();
                } else if (direction == "down"){
                    return source.next();
                }
            }

            function insertAdjacent(source, content, direction){
                if (direction == "up"){
                    source.before(content);
                } else if (direction == "down"){
                    source.after(content);
                }
            }

            function moveDraggingObject(listItemNode, direction){
                console.log(direction);
                if (!$(listItemNode).is(".dndAnimating") && !$(".dndDragging").is(".dndAnimating") && !getAdjacent($(listItemNode), direction).is(".dndDragging")) {
                    console.log("Nothing animating and draggable is not "+direction+" enough yet");
                    templistitemnode = getAdjacent($(".dndDragging"), direction);
                    var speed = 100;
                    if (!templistitemnode.is(listItemNode)){
                        speed = 0;
                    }
                    templistitemnode.addClass("dndAnimating");
                    $(".dndDragging").addClass("dndAnimating");
                    var distance = templistitemnode[0].offsetTop - $(".dndDragging")[0].offsetTop;
                    $.when(templistitemnode.animate({
                            top: -distance
                        }, speed),
                        $(".dndDragging").animate({
                            top: distance
                        }, speed)).done(function () {
                        console.log("Done animating");
                        templistitemnode.removeClass("dndAnimating").css("top", "");
                        $(".dndDragging").removeClass("dndAnimating").css("top", "");
                        insertAdjacent(templistitemnode, $(".dndDragging"), direction);
                        if (!getAdjacent($(listItemNode), direction).is(".dndDragging")){
                            console.log("Draggable still isn't "+direction+" enough yet (trigger move "+direction+")");
                            moveDraggingObject(listItemNode, direction);
                        } else if ($(".dndDelayedTarget").length > 0) {
                            console.log("Draggable is "+direction+" enough, but there is a delayed target");
                            if ($(".dndDragging").nextAll().is(".dndDelayedTarget")){
                                console.log("Delayed target is below (trigger move down)");
                                moveDraggingObject($(".dndDelayedTarget")[0], "down");
                            } else if ($(".dndDragging").prevAll().is(".dndDelayedTarget")){
                                console.log("Delayed target is above (trigger move up)");
                                moveDraggingObject($(".dndDelayedTarget")[0], "up");
                            }
                            $(".dndDelayedTarget").removeClass("dndDelayedTarget");
                        } else if ($(".dndDragging").is(".dndQueueUnclick")){
                            console.log("Button was released so a queued unclick takes effect");
                            var data = $(".dndDragging").data("dndData");
                            var transferredObject;
                            try {
                                transferredObject = JSON.parse(data);
                            } catch(e) {
                                console.error("Error with transferred object: " + data);
                                transferredObject = null;
                            }
                            var dnddragging = $(".dndDragging");
                            var originalindex = dnddragging.data("originalindex");
                            $(".dndDragging").removeData("originalindex");
                            $(".dndDragging").removeClass("dndQueueUnclick").removeClass("dndDragging");
                            $parse($(".dndDragging").data("dndDrop"))(scope, {
                                event: event,
                                index: $(".categories-list-item").index(dnddragging),
                                originalindex: originalindex,
                                item: transferredObject || undefined
                            });
                        }
                    });
                } else if ($(listItemNode).is(".dndAnimating") || $(".dndDragging").is(".dndAnimating")){
                    console.log("We were busy animating, so we'll queue one up for later");
                    if ($(listItemNode).is(".dndAnimating")){
                        console.log("Or so we would, but the queued target is already the one moving");
                    } else {
                        $(".dndDelayedTarget").removeClass("dndDelayedTarget");
                        $(listItemNode).addClass("dndDelayedTarget");
                    }
                }
            }
        }
    }])
    .directive('dndList', ["$parse", function($parse){
        return function(scope, element, attr){

            element.on("mouseup touchend", function(){
                console.log(event.type);
                if ($(".dndDragging").length > 0) {
                    if (!$(".dndDragging").is(".dndAnimating")) {
                        var data = $(".dndDragging").data("dndData");
                        var transferredObject;
                        try {
                            transferredObject = JSON.parse(data);
                        } catch (e) {
                            console.error("Error with transferred object: " + data);
                            transferredObject = null;
                        }
                        allData = [];
                        $(".categories-list-item").each(function(){
                            var data = undefined;
                            try{
                                data = JSON.parse($(this).data("dndData"));
                            } catch (e) {
                                data = undefined;
                            }
                            allData.push(data);
                        });
                        var dnddragging = $(".dndDragging");
                        var originalindex = dnddragging.data("originalindex");
                        $(".dndDragging").removeData("originalindex");
                        $(".dndDragging").removeClass("dndDragging");
                        if ($(".categories-list-item").index(dnddragging) != originalindex) {
                            $parse(attr.dndDrop)(scope, {
                                list: allData,
                                event: event,
                                index: $(".categories-list-item").index(dnddragging),
                                originalindex: originalindex,
                                item: transferredObject || undefined
                            });
                        } else {
                            $parse(attr.dndOnClick)(scope, {
                                event: event,
                                elementid: $(".categories-list-item").index(dnddragging),
                                elementdata: transferredObject
                            });
                        }
                    } else {
                        console.log("Queueing unclick because dndDragging is currently animating");
                        $(".dndDragging").addClass("dndQueueUnclick");
                        $(".dndDragging").data("dndDrop", attr.dndDrop);
                    }
                }
            });
        }
    }]);









//angular.module('dndLists', [])
//.directive('dndDraggable', ['$timeout', function($timeout){
//return function(scope, element, attr){
//
//    // Set the HTML5 draggable attribute on the element
//    element.attr("draggable", "true");
//
//    // If the dnd-disable-if attribute is set, we have to watch that
//    if (attr.dndDisableIf) {
//        scope.$watch(attr.dndDisableIf, function(disabled) {
//            element.attr("draggable", !disabled);
//        });
//    }
//
//    element.on("dragstart", function(){
//        var placeholder = element.clone();
//        placeholder.html("&nbsp;");
//
//        var attrs = placeholder[0].attributes;
//        var toRemove = [];
//        for (attr in attrs) {
//            if (typeof attrs[attr] === 'object' &&
//                typeof attrs[attr].name === 'string' &&
//                !(/^class$/).test(attrs[attr].name) &&
//                !(/^style$/).test(attrs[attr].name)) {
//                toRemove.push(attrs[attr].name);
//            }
//        }
//
//        for (var i = 0; i < toRemove.length; i++) {
//            placeholder.removeAttr(toRemove[i]);
//        }
//
//        placeholder.addClass("dndPlaceholder");
//
//        $timeout(function() {
//            element.addClass("dndDraggingSource");
//            element.after(placeholder);
//        }, 0);
//    });
//}
//}])
//.directive('dndList', ["$timeout", function($timeout){
//    return function(scope, element, attr){
//
//        function movePlaceholderUp(listItemNode){
//            if (!$(listItemNode).is(".dndAnimating") && !$(".dndPlaceholder").is(".dndAnimating") && !$(listItemNode).prev().is(".dndPlaceholder")) {
//                templistitemnode = $(".dndPlaceholder").prev();
//                var speed = 100;
//                if (!templistitemnode.is(listItemNode)){
//                    speed = 0;
//                }
//                templistitemnode.addClass("dndAnimating");
//                $(".dndPlaceholder").addClass("dndAnimating");
//                var distance = templistitemnode[0].offsetTop - $(".dndPlaceholder")[0].offsetTop;
//                $.when(templistitemnode.animate({
//                        top: -distance
//                    }, speed),
//                    $(".dndPlaceholder").animate({
//                        top: distance
//                    }, speed)).done(function () {
//                    templistitemnode.removeClass("dndAnimating").css("top", "");
//                    $(".dndPlaceholder").removeClass("dndAnimating").css("top", "");
//                    templistitemnode.before($(".dndPlaceholder"));
//                    if (!$(listItemNode).prev().is(".dndPlaceholder")){
//                        movePlaceholderUp(listItemNode);
//                    }
//                });
//            }
//        }
//
//        function movePlaceholderDown(listItemNode){
//            if (!$(listItemNode).is(".dndAnimating") && !$(".dndPlaceholder").is(".dndAnimating") && !$(listItemNode).next().is(".dndPlaceholder")) {
//                templistitemnode = $(".dndPlaceholder").next();
//                var speed = 100;
//                if (!templistitemnode.is(listItemNode)){
//                    speed = 0;
//                }
//                templistitemnode.addClass("dndAnimating");
//                $(".dndPlaceholder").addClass("dndAnimating");
//                var distance = templistitemnode[0].offsetTop - $(".dndPlaceholder")[0].offsetTop;
//                $.when(templistitemnode.animate({
//                        top: -distance
//                    }, speed),
//                    $(".dndPlaceholder").animate({
//                        top: distance
//                    }, speed)).done(function () {
//                    templistitemnode.removeClass("dndAnimating").css("top", "");
//                    $(".dndPlaceholder").removeClass("dndAnimating").css("top", "");
//                    templistitemnode.after($(".dndPlaceholder"));
//                    if (!$(listItemNode).next().is(".dndPlaceholder")){
//                        movePlaceholderDown(listItemNode);
//                    }
//                });
//            }
//        }
//
//        element.on("dragover", function(event){
//            event = event.originalEvent || event;
//            event.preventDefault();
//            element.addClass("dndDragover");
//            var listItemNode = event.target;
//            if (!$(listItemNode).is(".dndPlaceholder")) {
//                if ($(".dndPlaceholder").prevAll().is($(listItemNode))) {
//                    movePlaceholderUp(listItemNode);
//                } else if ($(".dndPlaceholder").nextAll().is($(listItemNode))){
//                    movePlaceholderDown(listItemNode);
//                }
//            }
//        });
//
//        /**
//         * The dragenter event is fired when a dragged element or text selection enters a valid drop
//         * target. According to the spec, we either need to have a dropzone attribute or listen on
//         * dragenter events and call preventDefault(). It should be noted though that no browser seems
//         * to enforce this behaviour.
//         */
//        element.on('dragenter', function (event) {
//            //event = event.originalEvent || event;
//            event.preventDefault();
//        });
//
//        element.on("drop", function(){
//            $(".dndPlaceholder").after($(".dndDraggingSource"));
//            $(".dndPlaceholder").remove();
//            $(".dndDraggingSource").removeClass("dndDraggingSource");
//        });
//
//        element.on('dragleave', function(event) {
//            //event = event.originalEvent || event;
//            //
//            //console.log(event);
//            //element.removeClass("dndDragover");
//            //$timeout(function () {
//            //    if (!element.hasClass("dndDragover")) {
//            //        $(".dndPlaceholder").remove();
//            //    }
//            //}, 100);
//        });
//
//        function isMouseInFirstHalf(event, targetNode, relativeToParent) {
//            var mousePointer = (event.offsetY || event.layerY);
//            var targetSize = targetNode.offsetHeight + parseInt($(targetNode).css("border-top-width"),10) + parseInt($(targetNode).css("border-bottom-width"),10);
//            var targetPosition = targetNode.offsetTop - parseInt($(targetNode).css("border-top-width"),10);
//            targetPosition = relativeToParent ? targetPosition : 0;
//            return mousePointer < targetPosition + targetSize / 2;
//        }
//    }
//}]);