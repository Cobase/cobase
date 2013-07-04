$(function(){
    $('#phone-navigation #phone-menu').change(function(){
        var url = $(this).val();
        if(url) {
            window.location = url;
        }
    });
/*
    $(document).on('click', 'a.like-link', function(e) {
        e.preventDefault();

        var postId = $(this).data('postid');

        $.ajax({
            url: Routing.generate('CobaseAppBundle_like_post', {'postId': postId}),
            success: function(data) {
                alert(JSON.stringify(data));
            }
        });
    });
    */
});

var fancyFilter = function(filterListSelector, gallerySelector) {
    //Filter Button Code
    $(filterListSelector + ' a').click(function() {
        $(filterListSelector + ' li').removeClass('active');
        var $this = $(this);
        var filterType = $this.data('filter');
        if(!filterType) return true;

        $this.closest('li').addClass('active');
        $(gallerySelector).isotope({ 
            filter: filterType,
        });

        return false;
    });
};



