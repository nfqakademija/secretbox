/*
gfortInsta
Description: Instagram Feed by Graphicfort
Version: 1.0
Author: Graphicfort
Author URL: http://graphicfort.com
*/
/*global $*/
(function ($) {

    'use strict';

    $.fn.gfortInsta = function (options) {

        var settings,
            el = $(this),
            imagesLimit,
            imageItem = '',
            imagesWrapper = '<div class="instagram-feed-block-container">';

        settings = $.extend({
            limit: 6,
            userID: '',
            linkClass: '',
            accessToken: '',
            textSlogan: ''
        }, options);

        el.append('<span class="insta-loading"><i class="fa fa-spinner fa-pulse fa-fw fa-2x"></i></span>');

        $.ajax({
            type: 'GET',
            dataType: 'jsonp',
            url: 'https://api.instagram.com/v1/users/' + settings.userID + '/media/recent/?access_token=' + settings.accessToken,
            success: function (response) {

                imageItem = '<ul>';

                for (imagesLimit = 0; imagesLimit < settings.limit && imagesLimit < response.data.length; imagesLimit += 1) {
                    imageItem += '<li><a class="gfortInsta-item ' + settings.linkClass + '" href="' + response.data[imagesLimit].link + '" target="_blank"><img src="' + response.data[imagesLimit].images.thumbnail.url + '" alt="Instagram Image" /></a></li>';
                }

                imagesWrapper += '<h6><span>' + settings.textSlogan + '</span><a href="https://www.instagram.com/' + response.data[0].user.username + '" target="_blank" class="gfort-insta-username">@' + response.data[0].user.username + '</a>' + '</h6>' + imageItem + '</ul></div>';

                return el.append(imagesWrapper);

            }
        }).complete(function () {
            el.find('.insta-loading').remove();
        });

    };

}($));