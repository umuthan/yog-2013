/*** Wrapper module for js store ***/
var cs_local_storage = (function () {
    return {
        get: function (key) {
            if(store!=undefined){
                return store.get(key);
            }
            return false;
        },
        set: function (key, value) {
            if(store!=undefined){
                store.set(key, value);
            }
        },
        remove: function (key) {
            if(store!=undefined){
                store.remove(key);
            }
        },
        clear: function () {
            if(store!=undefined){
                store.clear(); /*** Clear all keys ***/
            }
        }
    };
})();

/*** Class for handling open and close expandable and slide elements. Use together with cs_local_storage ***/
function CsUiOpen(data){
    if(!data){
        data = {};
    }
    this.expandables = data;/*** data format should be object[slideshow_id][element_index] ***/
}
CsUiOpen.prototype.get = function(slideshow, key){
    if(this.expandables[slideshow]!=undefined){
        if(this.expandables[slideshow][key]!=undefined){
            return this.expandables[slideshow][key];
        }
    }
    return false;
}
CsUiOpen.prototype.set = function(slideshow, key, value){
    if(typeof(this.expandables[slideshow])!=='object'){
        this.expandables[slideshow] = {};
    }
    
    this.expandables[slideshow][key] = value;
}
CsUiOpen.prototype.remove = function(slideshow, key){
    if(this.expandables[slideshow]!=undefined){
        if(this.expandables[slideshow][key]!=undefined){
            delete this.expandables[slideshow][key];
        }
    }
}
CsUiOpen.prototype.getAll = function(){
    return this.expandables;
}
CsUiOpen.prototype.clear = function(){
    this.expandables = {};
}


jQuery(document).ready(function($){
    /*** SLIDE BOXES ***/
    (function() {
        var slideshow_id, cs_ui_open;
        
        slideshow_id = $('#cyclone-slides-metabox .cs-sortables').data('post-id');
        
        cs_ui_open = new CsUiOpen(cs_local_storage.get('cs_slide_toggles'));/*** Handle persistent slide data ***/
        
        /*** Init - Sortable slides ***/
        $('.cs-sortables').sortable({
            handle:'.cs-header',
            placeholder: "cs-slide-placeholder",
            forcePlaceholderSize:true,
            delay:100,
            /*** Update form field indexes when slide order changes ***/
            update: function(event, ui) {
                $('.cs-sortables .cs-slide').each(function(boxIndex, box){ /*** Loop thru each box ***/
                    $(box).find('input, select, textarea').each(function(i, field){ /*** Loop thru relevant form fields ***/
                        var name = $(field).attr('name');
                        if(name){
                            name = name.replace(/\[[0-9]+\]/, '['+boxIndex+']'); /*** Replace all [index] in field_key[index][name] ***/
                            $(field).attr('name',name);
                        }
                    });
                });
            }
        });
        
        /*** Init - Slide ID and title ***/
        $('.cs-sortables .cs-slide').each(function(i){
            var body;
            
            body = $(this).find('.cs-body');

            $(this).data('cs_id',i);
            
            if(cs_ui_open.get(slideshow_id ,i)=='open'){
                body.slideDown(0);
            } else {
                body.slideUp(0);
            }
        });
        
        /*** Add - Slide box from a hidden html template ***/
        $('#cyclone-slides-metabox').on('click', '.cs-add-slide', function(e){
            var id = $('.cs-sortables .cs-slide').length;
            var html = $('.cs-slide-skeleton').html();
            html = html.replace(/{id}/g, id);/*** replace all occurences of {id} to real id ***/
            
            $('.cs-sortables').append(html);
            $('.cs-sortables .cs-slide:last').find('.cs-thumbnail').hide().end().find('.cs-body').show();

            $('.cs-sortables .cs-slide').each(function(i){
                $(this).data('cs_id',i);
            });
            $('.expandable-body').each(function(i){
                $(this).data('cs_id',i);
            });
            
            $(".cycloneslider_metas_enable_slide_effects").trigger('change');
            
            e.preventDefault();
        });
        
        /*** Toggle - slide body visiblity ***/
        $('#cyclone-slides-metabox').on('click',  '.cs-header', function(e) {
            var id, box, body, cs_slide_toggles;
            
            box = $(this).parents('.cs-slide');
            body = box.find('.cs-body');
            
            id = box.data('cs_id');
            
            if(body.is(':visible')){
                body.slideUp(100);
                cs_ui_open.remove(slideshow_id , id);
            } else {
                body.slideDown(100);
                cs_ui_open.set(slideshow_id , id, 'open');/*** remember open section ***/ 
            }
            
            cs_local_storage.set('cs_slide_toggles', cs_ui_open.getAll());
        });
        
        /*** Delete - Remove slide box ***/
        $('#cyclone-slides-metabox').on('click',  '.cs-delete', function(e) {

            var box = $(this).parents('.cs-slide');
            box.fadeOut('slow', function(){ box.remove()});

            e.preventDefault();
            e.stopPropagation();
        });
        
        /*** Switcher - switch between slide types ***/
        $('#cyclone-slides-metabox').on('change', '.cs-slide-type-switcher', function(e){
            var box, body, image_box, video_box, custom_box, icon;
            
            box = $(this).parents('.cs-slide');
            body = box.find('.cs-body');
            image_box = body.find('.cs-image');
            video_box = body.find('.cs-video');
            custom_box = body.find('.cs-custom');
            icon = box.find('.cs-icon i');
            if($(this).val()=='video'){
                image_box.hide();
                video_box.show();
                custom_box.hide();
                icon.attr('class', 'icon-film');
            } else if($(this).val()=='custom'){
                image_box.hide();
                video_box.hide();
                custom_box.show();
                icon.attr('class', 'icon-font');
            } else {
                image_box.show();
                video_box.hide();
                custom_box.hide();
                icon.attr('class', 'icon-picture');
            }
            
        });
        $('.cs-slide-type-switcher').trigger('change');
        
    })();
    
    /*** EXPANDABLES ***/
    (function() {
        var slideshow_id, cs_ui_open;
        
        /*** Init ***/
        slideshow_id = $('#cyclone-slides-metabox .cs-sortables').data('post-id');
        
        cs_ui_open = new CsUiOpen(cs_local_storage.get('cs_expandables'));
        
        $('#cyclone-slides-metabox .expandable-body').each(function(i){
            $(this).data('cs_id', i);
            
            if(cs_ui_open.get(slideshow_id ,i)=='open'){
                $(this).slideDown(0);
            } else {
                $(this).slideUp(0);
            }
        });
        
        /*** Toggle - Expandable toggling ***/
        $('#cyclone-slides-metabox').on('click', '.expandable-header', function(e){
            var body, id;
            
            body = $(this).next('.expandable-body');
            id = body.data('cs_id');
            
            if(body.is(':visible')){
                body.slideUp(100);
                cs_ui_open.remove(slideshow_id , id);
                
            } else {
                body.slideDown(100);
                cs_ui_open.set(slideshow_id , id, 'open');
                
            }
            
            cs_local_storage.set('cs_expandables', cs_ui_open.getAll());
        });
    })();
    
    /*** VIDEO SLIDE ***/
    (function() {
        var slideshow_id;
        
        slideshow_id = $('#cyclone-slides-metabox .cs-sortables').data('post-id');
        
        /*** Get Video ***/
        $('#cyclone-slides-metabox').on('click', '.cs-video-get', function(e){
            var button, box, textbox_url, url, video_thumb, video_embed;
            
            button = $(this);
            box = $(this).parents('.cs-slide');
            video_thumb = box.find('.cs-video-thumb');
            textbox_url = box.find('.cs-video-url');
            url = textbox_url.val();
            if(url==''){
                return;
            }
            video_embed = box.find('.cs-video-embed');
            video_thumb.empty().show();
            textbox_url.attr('disabled','disabled');
            button.attr('disabled','disabled');
            
            $.ajax({
                type: "POST",
                url: ajaxurl, /*** Automatically added by wordpress ***/
                data: "action=cycloneslider_get_video&url="+encodeURIComponent(url),
                dataType: 'json',
                success: function(data, textStatus, XMLHttpRequest){
                    if(data.success){
                        video_thumb.html('<img src="'+data.url+'" alt="thumb">');
                        box.find('.cs-video-thumb-url').val(data.url);
                        video_embed.val(data.embed);
                        textbox_url.removeAttr('disabled');
                        button.removeAttr('disabled');
                    } else {
                        alert('Error. Make sure its a valid youtube or vimeo url.');
                        video_thumb.empty().hide();
                        textbox_url.removeAttr('disabled');
                        button.removeAttr('disabled');
                    }
                    
                }
            });
        });

        
    })();

    (function() {

        /*** hide wordpress admin stuff ***/
        $('#minor-publishing-actions').hide();
        $('#misc-publishing-actions').hide();
        $('.inline-edit-date').prev().hide();
        
        /*** Post type switcher quick fix ***/
        $('#pts_post_type').html('<option value="cycloneslider">Cycloneslider</option>');
        
        /*** Template Chooser ***/
        $('.template-choices li').click(function(){
            $('.template-choices li').removeClass('active');
            $('.template-choices li input').removeAttr('checked');
            $(this).addClass('active').find('input').attr('checked','checked');
        });
        
        /*** show/Hide Tile Properties for slideshow ***/
        $('#cyclone-slider-properties-metabox').on('change', '#cycloneslider_settings_fx', function(){
            if($(this).val()=='tileBlind' || $(this).val()=='tileSlide'){
                $('.cycloneslider-field-tile-properties').slideDown('fast');
            } else {
                $('.cycloneslider-field-tile-properties').slideUp('fast');
            }
        });
        $("#cycloneslider_settings_fx").trigger('change');
        
        /*** Show/hide Tile Properties for slides ***/
        $('#cyclone-slides-metabox').on('change', '.cycloneslider_metas_fx', function(){
            if($(this).val()=='tileBlind' || $(this).val()=='tileSlide'){
                $(this).siblings('.cycloneslider-slide-tile-properties').slideDown('fast');
            } else {
                $(this).siblings('.cycloneslider-slide-tile-properties').slideUp('fast');
            }
        });
        $(".cycloneslider_metas_fx").trigger('change');
        
        /*** enable/disable form fields and labels ***/
        $('#cyclone-slides-metabox').on('change', '.cycloneslider_metas_enable_slide_effects', function(){
            if($(this).val()==0){
                $(this).parent().find('input,select').not(this).attr('disabled','disabled');
                $(this).parent().find('label,.note').addClass('disabled');
            } else {
                $(this).parent().find('input,select').not(this).removeAttr('disabled');
                $(this).parent().find('label,.note').removeClass('disabled');
            }
        });
        $(".cycloneslider_metas_enable_slide_effects").trigger('change');
        
    })();

    (function() {
        if(typeof(wp) == "undefined" || typeof(wp.media) != "function"){
            return;
        }
        // Prepare the variable that holds our custom media manager.
        var cyclone_media_frame;
        var current_slide_box = false;
        
        // Bind to our click event in order to open up the new media experience.
        $(document.body).on('click', '.cs-media-gallery-show', function(e){
            // Prevent the default action from occuring.
            e.preventDefault();
            
            current_slide_box = $(this).parents('.cs-slide');/*** get current box ***/
            
            
            // If the frame already exists, re-open it.
            if ( cyclone_media_frame ) {
                cyclone_media_frame.open();
                return;
            }
    

            cyclone_media_frame = wp.media.frames.cyclone_media_frame = wp.media({
                className: 'media-frame cs-frame',
                frame: 'select',
                multiple: false,
                title: cycloneslider_admin_vars.title,
                library: {
                    type: 'image'
                },
                button: {
                    text:  cycloneslider_admin_vars.button
                }
            });
    
            cyclone_media_frame.on('select', function(){
                var media_attachment, slide_thumb, slide_attachment_id, img_url;
                
                // Grab our attachment selection and construct a JSON representation of the model.
                media_attachment = cyclone_media_frame.state().get('selection').first().toJSON();
                
                slide_thumb = current_slide_box.find('.cs-image-thumb');/*** find the thumb ***/
                slide_attachment_id = current_slide_box.find('.cs-image-id ');/*** find the hidden field that will hold the attachment id ***/
                
                if(undefined==media_attachment.sizes.medium){ /*** Account for smaller images where medium does not exist ***/
                    img_url = media_attachment.url;
                } else {
                    img_url = media_attachment.sizes.medium.url;
                }
                
                slide_thumb.html('<img src="'+img_url+'" alt="thumb">').show();
                slide_attachment_id.val(media_attachment.id);
                
            });
    
            // Now that everything has been set, let's open up the frame.
            cyclone_media_frame.open();
        });
    })();
});