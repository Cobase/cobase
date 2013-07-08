var Cobase = Cobase || {};

Cobase.LikesManager = function(el) {
    this.el = el;
};

Cobase.LikesManager.prototype = (function() {

    return {
        bindEvents: function() {
            var self = this;

            $(document).on("click", this.el, function(e){
                e.preventDefault();

                if (self.isLiked($(this))) {
                    self.onUnlike($(this));
                }
                else {
                    self.onLike($(this));
                }
            });

            $(document).on("hover", '.like-count', function(e){
                if (!$(this).attr('data-likes-fetched')) {
                    self.initLikes($(this).data('postid'), this)
                }
            });

            $(document).on("click", '.like-count', function(e){
                e.preventDefault();
            });
        },

        isLiked: function(el) {
            return $(el).data('liked') && $(el).data('liked') == true;
        },

        initLikes: function(postId, element) {
            var self = this;

            $.ajax({
               'url':  Routing.generate('CobaseAppBundle_get_post_likes', {'postId': postId}),
                success: function(data) {
                    if (data.success) {

                        var likes = '';
                        var i = 0;
                        for (i = 0; i < data.likes.length; i++) {
                            likes += '<a href="' + Routing.generate('CobaseAppBundle_user_view', {'username': data.likes[i].username }) + '">' + data.likes[i].name + "</a>";

                            if (i < data.likes.length - 1) {
                                likes += ', ';
                            }
                        }

                        $(element).attr('data-original-title', likes);
                        $(element).attr('data-likes-fetched', "true");
                        $(element).popover("show");
                    }
                }
            });
        },

        onLike: function(el) {
            var postId = $(el).data('postid');
            var self = this;
            $.ajax({
                url: Routing.generate('CobaseAppBundle_like_post', {'postId': postId}),
                success: function(data) {
                    if (data.success) {
                        self.renderUnlikeMode(el);
                        $(el).parent().find('.like-count').removeAttr('data-likes-fetched');
                    }
                    else {
                        alert(data.failure.message);
                    }
                }
            });
        },

        onUnlike: function(el) {
            var postId = $(el).data('postid');
            var self = this;
            $.ajax({
                url: Routing.generate('CobaseAppBundle_unlike_post', {'postId': postId}),
                success: function(data) {
                    if (data.success) {
                        self.renderLikeMode(el);
                        $(el).parent().find('.like-count').removeAttr('data-likes-fetched');
                    }
                    else {
                        alert(data.failure.message);
                    }
                }
            });
        },

        renderLikeMode: function(el) {
            $(el).html("Like");
            $(el).data('liked', false);

            var likeCount = parseInt($(el).next().find('.like-count').html());
            $(el).next().find('.like-count').html((likeCount - 1));

            var $thumb = $(el).parent().find('i.thumb');

            $($thumb).removeClass('icon-thumbs-down');
            $($thumb).addClass('icon-thumbs-up');
        },

        renderUnlikeMode: function(el) {
            $(el).html("Unlike");
            $(el).data('liked', true);

            var likeCount = parseInt($(el).next().find('.like-count').html());
            $(el).next().find('.like-count').html((likeCount + 1));

            var $thumb = $(el).parent().find('i.thumb');

            $($thumb).removeClass('icon-thumbs-up');
            $($thumb).addClass('icon-thumbs-down');
        }
    }
})();