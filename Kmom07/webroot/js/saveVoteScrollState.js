(function() {
    if (localStorage.getItem("votePageScroll") !== null) {
        var scroll = localStorage.getItem("votePageScroll");
        window.scrollTo(0, scroll);
        localStorage.setItem("votePageScroll", null);
    }

    window.onscroll = function(ev) {
        var scroll = document.documentElement.scrollTop;
        localStorage.setItem("votePageScroll", scroll);
    };
}());
