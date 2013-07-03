var Cobase = Cobase || {};

Cobase.LikesManager = function(el) {
    this.el = el;
};

Cobase.LikesManager.prototype = (function() {

    var foo = function() {
    };

    return {
        bindEvents: function() {
            var self = this;
            $(document).on("click", this.el, function(e){
                e.preventDefault();

                if (self.isLiked($(this))) {
                    self.onUnlike();
                }
                else {
                    self.onLike();
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
                    alert(JSON.stringify(data));
                }
            });
        },

        onUnlike: function(el) {

        },

        renderLikeMode: function() {

        },

        renderUnlikeMode: function() {

        }
    }
})();