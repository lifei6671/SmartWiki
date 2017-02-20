;(function ($) {
    var _selTree = 'selTree';

    $.fn[_selTree] = function(options, fire) {

        var stack = $(this);

        stack.find(".input-group-btn>.btn").on("click",function (e) {
            e.stopPropagation();
            stack.find(".dropdown-select-menu").show();
        });
        stack.find("input:first").on("click",function (e) {
            e.stopPropagation();
            stack.find(".dropdown-select-menu").show();
        });
        $(document).on("click",function () {
            stack.find(".dropdown-select-menu").hide();
        });
        stack.find(".dropdown-select-menu a").on("click",function (e) {
            e.stopPropagation();
            var next = $(this).next("ul");
            var parent =  $(this).closest("li");

           $(this).closest(".dropdown-select-menu").find("a").removeClass("selected");
            $(this).addClass("selected");

            //如果有子节点
            if(next.length >0 && !parent.hasClass('selected')){
                parent.addClass("selected");
                next.addClass("active");
            }
            var value = $(this).attr("data-value");
            var text = $(this).attr("data-title");
            if(text === undefined || text === ""){
                text = $(this).text();
            }

            if(value !== undefined){
                stack.find("input:hidden").val(value);
            }
           stack.find("input:first").val(text);


        }).on("keydown",function (e) {
            if(e.keyCode === 13){
                e.stopPropagation();
                stack.find(".dropdown-select-menu").hide();
            }
            return true;
        });
    };

})(jQuery);