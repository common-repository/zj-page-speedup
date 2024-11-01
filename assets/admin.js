(function ($) {
    let clickUrl = e => {
        $('.urls_container ul li').removeClass('ui-selected');
        $(e.target).addClass('ui-selected');
        let _url = $(e.target).attr('value');
        let _list = window._url_mappings[_url];
        $('input[name="plugins[]"]').prop('checked', false);
        _list.forEach(item => {
            $('input[name="plugins[]"][value="' + item + '"]').prop('checked', true);
        });

    };
    /**
     * trailersplashingit
     * @date 2022-04-27
     * @param {any} function($
     * @returns {any}
     */
    let trailersplashingit = _url => (_url[_url.length - 1] !== '/') ? _url += '/' : _url;
    window._url_mappings = JSON.parse(window._url_mappings);
    window._url_mappings = Object.assign({}, window._url_mappings);
    $(document).ready(() => {
        // button add_to_list click handler
        $('#add_to_list').on('click', function (e) {
            e.preventDefault();
            let _url = $('#url_adding').val();
            _url = decodeURI(_url);
            _url = trailersplashingit(_url);
            if (!window._url_mappings[_url]) {
                let _new_node = $('<li>', {
                    value: _url,
                    text: _url,
                    class: 'url_item',
                    click: clickUrl,
                });
                $('#urls').append(_new_node);
                window._url_mappings[_url] = [];
                _new_node.click();
            }
        });
        $('#btn_remove_url').on('click', function (e) {
            e.preventDefault();
            let selectedNode = $('.urls_container ul li.ui-selected');
            let _url = selectedNode.attr('value');
            selectedNode.remove();
            delete window._url_mappings[_url];
        });
        $('#btn_uncheck_all').on('click', function (e) {
            e.preventDefault();
            $('input[name="plugins[]"]').prop('checked', false);
        });
        $('input[type="checkbox"][class="check_plugins"]').click(function (e) {
            let _plugin = $(e.target).val();
            let _url = $('.urls_container ul li.ui-selected').attr('value');
            if (_url) {
                let _list = [];
                $('input[name="plugins[]"]:checked').each(function () {
                    _list.push(this.value);
                });
                window._url_mappings[_url] = _list;
            }
        });

        $('#speedup-form').on('submit', function (e) {
            $("<input />").attr("type", "hidden")
                .attr("name", "zjps_speedup_url_plugins_list")
                .attr("value", JSON.stringify(window._url_mappings))
                .appendTo("#speedup-form");
            return true;
        });

        Object.keys(window._url_mappings).sort().forEach(function (item) {
            item = decodeURI(item);
            $('#urls').append($('<li>', {
                value: item,
                text: item,
                class: 'url_item',
            }));
        });
        $('ul li').click(clickUrl);
        $('#urls li:first-child').click();
    });
})(jQuery);
