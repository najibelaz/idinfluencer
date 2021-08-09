function Twitter(){
    var self= this;
    var _current_link = "";
    var _current_link_photos = [];
    var _started = false;
    this.init= function(){
        if($(".twitter-app").length > 0){
            self.optionTwitter();
            self.loadPreview();
        }
    };

    this.optionTwitter = function(){
        /*
        * Select type post
        */
        if($("[name='default_type']").val() != undefined){
            _default_type = $("[name='default_type']").val();
            console.log(_default_type);
            setTimeout(function(){
                _that = $(".schedule-twitter-type .item[data-type='"+_default_type+"']");
                self.load_form(_that, false);
            }, 50);
        }

        $(document).on("click", ".schedule-twitter-type .item", function(){
            self.load_form($(this), true);
        });

        //Enable Schedule
        $(document).on("change", ".enable_twitter_schedule", function(){
            _that = $(this);
            if(!_that.hasClass("checked")){
                $('.time_post').removeAttr('disabled');
                $('.btnPostNow').addClass("hide");
                $('.btnSchedulePost').removeClass("hide");
                _that.addClass('checked');
            }else{
                $('.time_post').attr('disabled', true);
                $('.btnPostNow').removeClass("hide");
                $('.btnSchedulePost').addClass("hide");
                _that.removeClass('checked');        
            }
            return false;
        }); 

        //Get Instagram Media
        $(document).on("click", ".btnGetInstagramMedia", function(){
            _that    = $(this);
            _type    = _that.data("type");
            _media   = _that.data("media");
            _caption = _that.data("caption");

            //Select tab
            $(".schedule-instagram-type .item[data-type="+_type+"]").trigger("click");

            //Set caption
            $(".post-message").data("emojioneArea").setText(_caption);

            //Add image
            if(_type == "carousel"){
                FileManager.type_select = 'multi';
            }else{
                FileManager.type_select = 'single';
            }

            for (var i = 0; i < _media.length; i++) {
                FileManager.saveFile(_media[i]);
            }

            //Hide modal
            $('#mainModal').modal('hide');
        });

        $(document).on("click", ".twitter-app .file-manager-list-images .item .close", function(){
            if($(".file-manager-list-images .item").length <= 0){
               self.defaultPreview();
            }
        });

        $(document).on("click", ".twitter-app .btnPostNow", function(){
            _that = $(this);
            self.postNow(_that);
        });
    };

    this.load_form = function(_that, _load_default_preview){
        _type = _that.data("type");
        _that.addClass("active");
        _that.siblings().removeClass("active");
        _that.siblings().find("input").removeAttr('checked');
        _that.find("input").attr('checked','checked');

        if(_type == "text"){
            $(".image-manage").hide();
            $(".twitter-text").addClass("max");
        }else{
            $(".image-manage").show();
            $(".twitter-text").removeClass("max");
        }
        $(".image-manage").attr("data-type", _that.data("type-image"));

        $(".preview-twitter").addClass("hide");
        $(".preview-twitter-"+_type).removeClass("hide");

        if(_load_default_preview){
            self.defaultPreview();
        }
    };

    this.postNow = function(_that){
        _form    = _that.closest("form");
        _action  = _form.attr("action");
        _data    = $("[name!='account[]']").serialize();
        _data    = _data + '&' + $.param({token:token});
        _item    = $(".list-account .item.active");
        _stop    = false;
        if(_item.length > 0){
            _id     = _item.first().find("input").val();
            _data   = _form.serialize();
            _data   = Main.removeParam("account%5B%5D", _data);
            _data   = _data + '&' + $.param({token:token , 'account[]' :_id});
        }else{
            if(_started == true){
                _started = false;
                Main.statusCardOverplay("hide");
                return false;
            }
        }

        Main.statusOverplay("hide");
        Main.statusCardOverplay("show");

        Main.ajax_post(_that, _action, _data, function(_result){
            Main.statusOverplay("show");
            _started = true;

            //Remove mark item
            if(_result.stop == undefined){
                _item.first().trigger("click");
                setTimeout(function(){
                    $(".btnPostNow").trigger("click");
                }, 500);
            }else{
                Main.statusCardOverplay("hide");
            }
        });
    };

    this.loadPreview = function(){
        //Review content
        if($(".post-message").length > 0){
            $(".post-message").data("emojioneArea").on("keyup", function(editor) {
                _data = editor.html();
                if(_data != ""){
                    $(".twitter-app .caption-info").html(_data);
                }else{
                    $(".twitter-app .caption-info").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                }
            });

            $(".post-message").data("emojioneArea").on("change", function(editor) {
                _data = editor.html();
                if(_data != ""){
                    $(".twitter-app .caption-info").html(_data);
                }else{
                    $(".twitter-app .caption-info").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                }
            });

            $(".post-message").data("emojioneArea").on("emojibtn.click", function(editor) {
                _data = $(".emojionearea-editor").html();
                if(_data != ""){
                    $(".twitter-app .caption-info").html(_data);
                }else{
                    $(".twitter-app .caption-info").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text w50"></div>');
                }
            });
        }

        $(document).on("click", ".all-post .list-action li a", function(){
            _that = $(this);
            _li = _that.parents("li");
            _id = _that.attr("href");
            _id = _id.replace("#", "");

            switch(_id){
                case "text":
                    $(".preview-twitter").addClass("hide");
                    $(".preview-twitter-text").removeClass("hide");
                    self.defaultPreview();
                    break;

                case "link":
                    $(".preview-twitter").addClass("hide");
                    $(".preview-twitter-link").removeClass("hide");
                    self.defaultPreview();
                    break;

                case "photo":
                    $(".preview-twitter").addClass("hide");
                    $(".preview-twitter-photo").removeClass("hide");
                    self.defaultPreview();
                    break;

                case "video":
                    $(".preview-twitter").addClass("hide");
                    $(".preview-twitter-video").removeClass("hide");
                    self.defaultPreview();
                    break;
            }
        });

        //Load Preview
        setInterval(function(){ 
            if($(".all-post .add_link").length > 0){
                $(".twitter-app .preview-twitter-link .title").html('<div class="line-no-text"></div>');
                $(".twitter-app .preview-twitter-link .desc").html('<div class="line-no-text"></div><div class="line-no-text"></div><div class="line-no-text"></div>');
                $(".twitter-app .preview-twitter-link .website").html('<div class="line-no-text w50"></div>');

                _result = $(".all-post .add_link").attr("data-result");
                if(_result != undefined && _result != ""){
                    _result = JSON.parse(_result);

                    if(_result.title != "")
                        $(".twitter-app .preview-twitter-link .title").html(_result.title);
                    if(_result.description != "")
                        $(".twitter-app .preview-twitter-link .desc").html(_result.description);
                    if(_result.host != "")
                        $(".twitter-app .preview-twitter-link .website").html(_result.host);
                }
            }

            _type  = $(".schedule-twitter-type .item.active").data("type");
            _type = ( _type == undefined )?$(".all-post .list-action .active input").val():_type;
            _media = $(".file-manager-list-images .item");
            if(_media.length > 0){
                switch(_type){
                    case "photo":
                        list_images = [];
                        $check = true;

                        $("input[name='media[]']").each(function( index ) {
                            list_images.push($(this).val());
                            if(_current_link_photos.indexOf($(this).val()) == -1 || _current_link_photos.length != $("input[name='media[]']").length){
                                $check = false;
                            }
                        });
                        if($check == false){
                            _current_link_photos = list_images;
                            _count_image = list_images.length > 4?4:list_images.length;
                            $(".preview-twitter-photo .preview-image").attr("class", "preview-image").addClass("preview-photo" + _count_image).html('');
                            for (i = 0; i < list_images.length; i++) {
                                _link_arr = list_images[i].split(".");
                                if(_link_arr[_link_arr.length - 1] != "mp4"){
                                    $(".preview-twitter-photo .preview-image").append('<div class="item" style="background-image: url('+list_images[i]+')"></div>');
                                }
                            }
                        }
                        break;

                    case "video":
                        _link     = _media.find("input").val();
                        _link_arr = _link.split(".");
                        if(_current_link != _link){
                            if(_link_arr[_link_arr.length - 1] == "mp4"){
                                $(".preview-twitter-video .preview-image").html('<video src="'+_link+'" playsinline="" autoplay="" muted="" loop=""></video>');
                                $(".preview-twitter-video .preview-image").css({"background-image": "none"});
                            }
                            _current_link = _link;
                        }
                        break;

                    case "link":
                        _link     = _media.find("input").val();
                        _link_arr = _link.split(".");
                        if(_current_link != _link){
                            if(_link_arr[_link_arr.length - 1] != "mp4"){
                                $(".preview-twitter-link .image").css({'background-image': 'url(' + list_images[0] + ')'});
                            }
                            _current_link = _link;
                        }
                        break

                    case "text":
                        break
                }
            }else{
                if($(".all-post .add_link").length > 0){
                    $(".vk-app .preview-vk-link .image").removeAttr("style");
                }
            }
        }, 1500);
    };

    this.defaultPreview = function(){
        $(".preview-twitter-photo .preview-image").attr("class", "preview-image").html('');
        $(".preview-twitter-link .image").attr("style","");
        //$(".preview-twitter-text").html('');
    };
}

Twitter= new Twitter();
$(function(){
    Twitter.init();
});
