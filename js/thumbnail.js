jQuery(function($) {
    var CT = {
        $wrapper: $("#clippp-thumbnail-wrapper"),

        init: function() {
            this.$container = ($.proxy(function() {
                var a = this.$wrapper.parents(".container");
                if (a.get(0)) return a;
                return $(window);
            }, this))();
            this.$header = this.$wrapper.children("header");
            this.$radios = this.$header.children("[type=radio]");
            this.$labels = this.$header.children("label");
            this.$main = this.$wrapper.children("ul");
            this.$boxes = this.$main.children("li");
            this.box_per_row = this.$main.data("per-row");
            $("head").append($("<link>", {href: this.$wrapper.data("style-src"), rel: "stylesheet", type: "text/css"}));

            this.$radios
            .on("change", $.proxy(this.cat_change, this))
            .first()
            .prop("checked", true)
            .trigger("change");
            
            this.$boxes.each($.proxy(this.box_init, this)).on("click", $.proxy(this.box_preview, this));
            
            $(window).on("resize", $.proxy(function() {
                this.$wrapper.css({
                    'margin-left': -parseInt(this.$container.css("margin-left")),
                    'margin-right': -parseInt(this.$container.css("margin-right")),
                })
                this.$radios.siblings(":checked").trigger("change");
            }, this)).trigger("resize");
            
            this.P = CTP.init();
            return this;
        },
        
        box_init: function(i, v) {
            var $l = $(v).children("div");
            
            if ($l.data("image-url")) this.image_box_init($l);
            //if ($l.data("m-image-url")) this.moving_image_box_init($l);
        },
        
        image_box_init: function($b) {
            var b = $b.data("image-url");
            b = b.split(",")
            $b.removeClass("loading");
            for (var i in b) $b.append($("<img>", {src: b[i]}));
            if (b.length > 1) {
                $b.addClass("multi-image");
            }
        },
        
        moving_image_box_init: function($b) {
            // moving image init
        },
        
        cat_change: function(e) {
            var $t = $(e.currentTarget),
                t = $t.attr("id").match(/clippp-thumbnail-cat-(.*)$/)[1],
                c = 0,
                w = this.$main.width() / this.box_per_row,
                h = w * 9 / 16;
            
            $t.next().addClass("border").siblings("label").removeClass("border");
            this.$boxes.each($.proxy(function(i, v) {
                $(v).toggleClass("transition", $(v).hasClass("show"));
                $(v).toggleClass("show", $t.val() === "all" || $(v).hasClass(t));
                if ($(v).hasClass("show")) {
                    $(v).css({
                        top: h * (Math.floor(c / this.box_per_row)),
                        left: 100 * ((++c - 1) % this.box_per_row) / this.box_per_row + "%",
                    })
                }
            }, this));
            
            this.$main.height(h * Math.ceil(c / this.box_per_row));
            
        },
        
        box_preview: function(e) {
            this.P.show($(e.currentTarget));
        }

    },
    
    CTP = {
        
        init: function() {
            $("body").append(
                $("<section>", {id: "clippp-preview-wrapper"}).append(
                    $("<div>", {id: "clippp-preview"})
                )
            )
            this.$wrapper = $("#clippp-preview-wrapper").on("click", $.proxy(this.backdrop, this));
            this.$preview = this.$wrapper.children("#clippp-preview");
            
            return this;
        },
        
        show: function($t) {
            var u = $t.data("video-url"), m;
            this.$wrapper.addClass("show");
            m = u.split("/");
            switch (m[2]) {
                case "www.youtube.com":
                    if (!$("#youtube-api").get(0)) {
                        $("body").append($("<script>", {src: "https://www.youtube.com/iframe_api", id: "youtube-api"}));
                        window.onYouTubeIframeAPIReady = function() { $(window).trigger("youtubeReady"); };
                    }
                    if (typeof YT === "undefined")
                        $(window).on("youtubeReady", $.proxy(function() {this.load_youbute(m[3].match(/v=([^&]*)/)[1])}, this));
                    else
                        this.load_youbute(m[3].match(/v=([^&]*)/)[1]);
                    break;
                    
                case "vimeo.com":
                    this.load_vimeo(m[3] === "channels" ? m[5] : m[3] === "groups" ? m[6] : m[3]);
                    break;
                    
                default: break;
            }
        },
        
        load_youbute: function(i) {
            var t = this.$preview.attr("id") + "-youtube";
            this.$preview
            .append($("<div>", {id: t}))
            .children("#" + t)
            .data({
                player: new YT.Player(t, {
                    width: this.$preview.width(),
                    height: this.$preview.height(),
                    videoId: i,
                    events: {
                        onReady: function(e) {
                            e.target.playVideo();
                        }
                    }
                })
            })

        },
        
        load_vimeo: function(i) {
            this.$preview.append(
                $("<iframe>", {
                    id: this.$preview.attr("id") + "-vimeo",
                    src: "https://player.vimeo.com/video/" + i + "?api=1&autoplay=1&player_id=" + this.$preview.attr("id") + "-vimeo&badge=0",
                    width: this.$preview.width(),
                    height: this.$preview.height(),
                    frameborder: 0,
                    mozallowfullscreen: "mozallowfullscreen",
                    webkitallowfullscreen: "webkitallowfullscreen",
                    allowfullscreen: "allowfullscreen"
                })
            )
        },
        
        backdrop: function(e) {
            if ($.contains(e.target, this.$preview[0])) {
                this.$preview.children().remove();
                this.$wrapper.removeClass("show");
            }
        }
        
    }
    
    if (CT.$wrapper.get(0)) {
        CT.init();
        CT.$wrapper.find(".multi-image").each(function(i, v) {
            var c = $(this).children().last();
            setInterval($.proxy(function() {
                if (c.prev().get(0)) {
                    c.fadeOut(300);
                    c = c.prev();
                } else {
                    c = $(this).children().fadeIn(300).last();
                }
            }, this), 2000);
        });
    }
});

window.jQuery(function($) {
    var LCT = {
        
        $wrapper: $(".like-clippp-thumbnail-wrapper"),
        
        init: function() {
            this.$container = ($.proxy(function() {
                var a = this.$wrapper.parents(".container");
                if (a.get(0)) return a;
                return $(window);
            }, this))();
            this.$header = this.$wrapper.children("header");
            this.$radios = this.$header.children("[type=radio]");
            this.$labels = this.$header.children("label");
            this.$main = this.$wrapper.children("ul");
            this.$boxes = this.$main.children("li");
            this.box_per_row = this.$main.data("per-row");
            
            this.$radios
            .on("change", $.proxy(this.cat_change, this))
            .first()
            .prop("checked", true)
            .trigger("change");
            
            this.$boxes.on("click", $.proxy(this.box_preview, this));
            
            $(window).on("resize", $.proxy(function() {
                
                this.$wrapper.css({
                    'margin-left': -(
                        parseInt(this.$container.css("margin-left")) +
                        parseInt(this.$container.css("padding-left")) + 
                        parseInt(this.$container.children().css("padding-left"))
                    ),
                    'margin-right': -parseInt(
                        parseInt(this.$container.css("margin-right")) +
                        parseInt(this.$container.css("padding-right")) + 
                        parseInt(this.$container.children().css("padding-right"))
                    ),
                })
                this.$radios.siblings(":checked").trigger("change");
            }, this)).trigger("resize");
        },
    
        cat_change: function(e) {
            var $t = $(e.currentTarget),
                t = $t.attr("id").match(/clippp-([^-]+)-cat-(.*)$/)[2],
                c = 0,
                w = this.$main.width() / this.box_per_row,
                h = w * 9 / 16;
            
            $t.next().addClass("border").siblings("label").removeClass("border");
            this.$boxes.each($.proxy(function(i, v) {
                $(v).toggleClass("transition", $(v).hasClass("show"));
                $(v).toggleClass("show", $t.val() === "all" || $(v).hasClass(t));
                if ($(v).hasClass("show")) {
                    $(v).css({
                        top: h * (Math.floor(c / this.box_per_row)),
                        left: 100 * ((++c - 1) % this.box_per_row) / this.box_per_row + "%",
                    })
                }
            }, this));
            
            this.$main.height(h * Math.ceil(c / this.box_per_row));
            
        },
        
        box_preview: function(e) {
            var a = $(e.currentTarget);
            location.href = a.data("href");
        }
    };
    
    LCT.init();
});