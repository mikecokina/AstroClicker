/* responsive media 768 px is same as 753 jQuery window width */
$(window, document, undefined).ready(function () {
    function tableHighlightReset() {
        var b = $("body");
        b.find("\.crosshairs").removeClass("active-crosshair");
        b.find("\.table-line").removeClass("active-line");
    }

    function setCurrentHeightCrosshair(selector) {
        var crosshairW = parseInt(selector.css("width"));
        selector.css({ "height": crosshairW + "px" });
    }

    var HOME = window.HTTP_HOST + window.HTTP_SELF;
    var PREFIX = "resources/do/";

    $('\#position-table').on('mouseenter', '.del', function () {
        $(this).removeClass("fa-trash-o");
        $(this).addClass("fa-trash")
    }).on('mouseleave', '.del', function () {
        $(this).removeClass("fa-trash");
        $(this).addClass("fa-trash-o");
    }).on('click', '.del', function () {
        var id = $(this).attr("val");
        if (id == 1) {

            $("body").find("\.crosshairs").remove();

            $("\#tbody-content").html("");
            $("\#position-table").hide();
            clickCounter = 0;

        } else {
            $("\#crosshair-" + String(id) + ", \#line-" + String(id)).remove();
        }


    }).hide();

    $("\#position-label").on('click', '.table-line', function () {
        var id = $(this).find("span").attr("val");
        tableHighlightReset();
        $(this).addClass("active-line");
        $("\#crosshair-" + String(id)).addClass("active-crosshair");

    });

//    $("\#position-table").hide();

    $("\#header-logout").find("button").click(function () {
        alert("Sign Out");
        window.location.href = HOME + "/" + PREFIX + "/logout.php";
    });

    var currentWidth = 0;
    var currentHeight = 0;
    var currentCss = "";
    var selector = $('\#click-container');
    var img_selector = $("\#click-img");
    var clickCounter = 0;

    if (!empty(window.IMG_WIDTH) && !empty(window.IMG_HEIGHT)) {
        var imgWidth = window.IMG_WIDTH;
        var imgHeight = window.IMG_HEIGHT;

        currentCss = img_selector.css("height", "width");
        currentWidth = currentCss.width();
        currentHeight = currentCss.height();

        var fontSize = (currentWidth / parseInt(screen.width)) * 300;

        $("\.crosshairs").css({"fontSize": String(fontSize) + "%"});

        $(window).resize(function () {
            currentCss = img_selector.css("height", "width");
            currentWidth = img_selector.width();
            currentHeight = currentCss.height();

            fontSize = (currentWidth / parseInt(screen.width)) * 300;
            var crosshairs = $("\.crosshairs");
            crosshairs.css({"fontSize": String(fontSize) + "%"});

            setCurrentHeightCrosshair(crosshairs);

        });

        selector.click(function (e) {
            tableHighlightReset();
            clickCounter += 1;
            var posX = e.pageX - $(this).offset().left,
                posY = e.pageY - $(this).offset().top;

            var posX_r = parseFloat(posX * (imgWidth / currentWidth)),
                posY_r = parseFloat(posY * (imgHeight / currentHeight));


            // 1.2 & 1.8 % are bulgarian constants :)
            var xRatio = ((posX / currentWidth) * 100),
                yRatio = ((posY / currentHeight) * 100);

            if ((posX_r <= imgWidth && posX_r >= 0) && (posY_r <= imgHeight && posY_r >= 0)) {
                // If crosshair is out of main div element, than is possible to click on it, and position is
                // gathered as well
                $("\#position-table").fadeIn("100");

                var thisCrosshair = null;
                if (clickCounter == 1) {
                    // Append first crosshair
                    $(this).append("<div id=\"crosshair-" + String(clickCounter) + "\" class=\"absolute crosshairs\" aria-hidden=\"true\" style=\"top:" + String(yRatio) + "%;left:" + String(xRatio) + "%; font-size:" + String(fontSize) + "% ; color:#5cb85c;\">" +
                        "<div class=\"crosshair-inside\">" +
                        "<span class=\"fa fa-crosshairs\"></span>" +
                        "</div>" +
                        "</div>");

                    // Set height of crosshair element (problem with height in percentage unit)
                    thisCrosshair = $("\#crosshair-" + String(clickCounter));
                    setCurrentHeightCrosshair(thisCrosshair);

                    // Append table line
                    $("\#tbody-content").append("<tr class=\"table-line\" id=\"line-" + String(clickCounter) + "\">" +
                        "<td>" + String(clickCounter) + "</td>" +
                        "<td>" + String(Math.round(posX_r *100) / 100) + "</td>" +
                        "<td>" + String(Math.round(posY_r * 100) / 100) + "</td>" +
                        "<td>" +
                            "<span val=\"" + String(clickCounter) + "\" class=\"fa fa-trash-o del\" aria-hidden=\"true\" style=\"font-weight:bold;\"></span>" +
                        "</td>" +
                        "</tr>");

                } else {
                    $(this).append("<div id=\"crosshair-" + String(clickCounter) + "\" class=\"absolute crosshairs\" aria-hidden=\"true\" style=\"top:" + String(yRatio) + "%;left:" + String(xRatio) + "%; font-size:" + String(fontSize) + "%; color:#d43f3a\">" +
                        "<div class=\"crosshair-inside\">" +
                        "<span class=\"fa fa-crosshairs\"></span>" +
                        "</div>" +
                        "</div>");

                    // Set height of crosshair element (problem with height in percentage unit)
                    thisCrosshair = $("\#crosshair-" + String(clickCounter));
                    setCurrentHeightCrosshair(thisCrosshair);

                    $("\#tbody-content").append("<tr class=\"table-line\" id=\"line-" + String(clickCounter) + "\">" +
                        "<td>" + String(clickCounter) + "</td>" +
                        "<td>" + String(Math.round(posX_r *100) / 100) + "</td>" +
                        "<td>" + String(Math.round(posY_r * 100) / 100) + "</td>" +
                        "<td>" +
                            "<span val=\"" + String(clickCounter) + "\" class=\"fa fa-trash-o del\" aria-hidden=\"true\"></span>" +
                        "</td>" +
                        "</tr>");
                }
            }

        });
    }




    // Ajax - Data download
    $("#\download-position").click(function(){
        var data = [],
            x = null,
            y = null;

        $("\#tbody-content tr").each(function(){
            x = $(this).find("td:nth-child(2)").text();
            y = $(this).find("td:nth-child(3)").text();
            data.push([x,y]);
        });

        if(!empty(data)) {
            $.ajax({
                url: HOME + "/" + PREFIX + "download.php",
                type: "GET",
                dataType: "JSON",
                data: {data: data},
                success: function(response) {
                    if(!empty(response.link)) {
                        redirect(response.link);
                    }
                }
            });
        }

    });

});

// File upload form
$(document).on('click', '.browse', function () {
    var file = $(this).parent().parent().parent().find('.file');
    file.trigger('click');
    // console.log(file.attr("name"));
});
$(document).on('change', '.file', function () {
    $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});
