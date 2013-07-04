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
        },

        isLiked: function(el) {
            return $(el).data('liked') && $(el).data('liked') == true;
        },

        onLike: function(el) {
            var postId = $(el).data('postid');
            var self = this;
            $.ajax({
                url: Routing.generate('CobaseAppBundle_like_post', {'postId': postId}),
                success: function(data) {
                    if (data.success) {
                        self.renderUnlikeMode(el);
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

            var likeCount = parseInt($(el).next('span.like-count').html());

            $(el).next('span.like-count').html((likeCount - 1));
        },

        renderUnlikeMode: function(el) {
            $(el).html("Unlike");
            $(el).data('liked', true);

            var likeCount = parseInt($(el).next('span.like-count').html());

            $(el).next('span.like-count').html((likeCount + 1));
        }
    }
})();