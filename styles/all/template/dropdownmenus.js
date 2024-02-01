$(function()
{
    let closeTimeout = null;
    $('.dropdownmenu').on('mouseover', function(e) {
        if (closeTimeout) {
            clearTimeout(closeTimeout);
        }
        if ($('.dropdownmenu-hover').length) {
            $('.dropdownmenu-hover').removeClass('dropdownmenu-hover');
        }
        $(e.target).closest('.dropdownmenu').addClass('dropdownmenu-hover');
        if ($('.dropdowmenu-body').length) {
            $('.dropdowmenu-body').remove();
        }
        const items = JSON.parse(atob($(e.target).attr('data-items')));
        if (items.length === 0) return;
        let itemsHtml = '';
        for (let i = 0; i < items.length; i++) {
            itemsHtml += '<div class="dropdownmenu-item" title="' + items[i].dropdownmenu_description + '"><a href="' + items[i].dropdownmenu_link + '">' + items[i].dropdownmenu_title + '</a></div>';
        }
        const dropdown = $('<div class="dropdowmenu-body">' + itemsHtml + '</div>');
        dropdown.css({
            position: 'absolute',
            top: $(e.target).offset().top + $('.dropdownmenus').height(),
            left: $(e.target).offset().left
        });
        $('body').append(dropdown);
    });
    const createTimeout = function() {
        closeTimeout = setTimeout(function() {
            $('.dropdowmenu-body').fadeOut(200);
            $('.dropdownmenu-hover').removeClass('dropdownmenu-hover');
        }, 600);
    }
    $('.dropdownmenu').on('mouseout', function(e) {
        createTimeout();
    });
    $('body').on('mouseover', '.dropdowmenu-body', function(e) {
        if (closeTimeout) {
            clearTimeout(closeTimeout);
        }
    });
    $('body').on('mouseout', '.dropdowmenu-body', function(e) {
        createTimeout();
    });
    console.log('dropdownmenus.js loaded');
});
