$('.js-thumbnail-type-gallery').each(function () {
    $(this).magnificPopup({
        delegate: '.ttg-images a',
        type: 'image',
        mainClass: 'mfp-img-mobile',
        image: {
            verticalFit: true,
            titleSrc: function (item) {
                return item.el.attr('title');
            },
            tError: ttgi18n.imageNotLoaded
        },
        tClose: ttgi18n.close,
        tLoading: ttgi18n.loading,
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1],
            tPrev: ttgi18n.previous,
            tNext: ttgi18n.next,
            tCounter: ttgi18n.counter
        },
        removalDelay: 0
    });
});
