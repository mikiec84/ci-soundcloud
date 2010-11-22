$(function () {
    $('a').hover(function() {
        if ($(this).attr('rel') === 'external') {
            $(this).attr('target', '_blank');
        }
    });

    $('#add_tag').click(function (e) {
        var value = $('input[name=tag]').val();

        if (value) {
            $('#tags').append(
                $('<li></li>', {
                    text: value
                })
            );

            $('input[name=tag]').val('');
        }
    });

    $('#tags').delegate('li', 'click', function () {
        $(this).remove();
    });

    $('#add_track').submit(function (e) {
        var $tags = $('#tags li'),
            i = 0,
            s = $tags.length,
            tags = [];

        if ($tags.length > 0) {
            for (; i < s; i += 1) {
                tags.push($tags.eq(i).text());
            }

            $(this).append($('<input />', {
                name: 'tags',
                value: tags.join(' '),
                type: 'hidden'
            }));
        }
    });
});