jQuery(document).ready(function ($) {

    /* -----------------------------
       MEDIA SELECTOR HANDLER
    ----------------------------- */

    function lcMediaSelector(button, hiddenField, preview) {

        let frame;

        $(button).on('click', function (e) {
            e.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: 'Select Media',
                button: { text: 'Use this media' },
                multiple: false
            });

            frame.on('select', function () {

                let attachment = frame.state().get('selection').first().toJSON();

                $(hiddenField).val(attachment.id);

                let previewUrl =
                    attachment.sizes && attachment.sizes.thumbnail
                        ? attachment.sizes.thumbnail.url
                        : attachment.url;

                $(preview)
                    .attr('src', previewUrl)
                    .show();

            });

            frame.open();

        });

    }

    function lcMediaRemove(button, hiddenField, preview) {

        $(button).on('click', function () {

            $(hiddenField).val('');
            $(preview).hide();

        });

    }


    /* -----------------------------
       INITIALIZE MEDIA FIELDS
    ----------------------------- */

    lcMediaSelector('#lc-image-selector', '#image_attachment_id', '#lc-image-preview');
    lcMediaSelector('#lc-video-selector', '#lc_video_attachment_id', '#lc-video-preview');
    lcMediaSelector('#lc-poster-image-selector', '#lc_poster_image_id', '#lc-poster-image-preview');

    lcMediaRemove('#lc-image-remove', '#image_attachment_id', '#lc-image-preview');
    lcMediaRemove('#lc-video-remove', '#lc_video_attachment_id', '#lc-video-preview');
    lcMediaRemove('#lc-poster-image-remove', '#lc_poster_image_id', '#lc-poster-image-preview');


    /* -----------------------------
       RELATED POSTS SELECTOR
    ----------------------------- */

    const lcSourceList = document.getElementById('lc-post-list');
    const lcSelectedRelatedList = document.getElementById('lc-related-list');

    const lcSelectedItemIds = new Set();

    const lcMaxSelections = 3;

    $('#lc_related_items_alert').hide();


    /* -----------------------------
       LOAD EXISTING VALUES
    ----------------------------- */

    function lc_LoadInitialSelections() {

        let hiddenVal = $('#lc_related_post_list').val();

        if (!hiddenVal) return;

        let ids = hiddenVal.split(',');

        ids.forEach(function (id) {

            id = id.trim();
            if (!id) return;

            const original = document.querySelector('#lc-post-list li[data-id="' + id + '"]');

            if (original) {

                const clone = original.cloneNode(true);

                lcSelectedRelatedList.appendChild(clone);

                original.classList.add("selected");

                lcSelectedItemIds.add(id);

            }

        });

    }


    /* -----------------------------
       ADD RELATED ITEM
    ----------------------------- */

    lcSourceList.addEventListener('click', function (event) {

        if (event.target.nodeName !== 'LI' || event.target.classList.contains("selected")) return;

        if (lcSelectedRelatedList.children.length >= lcMaxSelections) {
            $('#lc_related_items_alert').show("slow");
            return;
        }

        const item = event.target;
        const id = item.getAttribute("data-id");

        const clone = item.cloneNode(true);

        lcSelectedRelatedList.appendChild(clone);

        item.classList.add("selected");

        lcSelectedItemIds.add(id);

        lc_UpdateHiddenField();

    });


    /* -----------------------------
       REMOVE RELATED ITEM
    ----------------------------- */

    lcSelectedRelatedList.addEventListener("click", function (event) {

        if (event.target.nodeName !== 'LI') return;

        const item = event.target;
        const id = item.getAttribute("data-id");

        const original = document.querySelector('#lc-post-list li[data-id="' + id + '"]');

        if (original) {
            original.classList.remove("selected");
        }

        item.remove();

        lcSelectedItemIds.delete(id);

        lc_UpdateHiddenField();

    });


    /* -----------------------------
       UPDATE HIDDEN FIELD
    ----------------------------- */

    function lc_UpdateHiddenField() {

        let ids = [];

        $('#lc-related-list li').each(function () {
            ids.push($(this).attr('data-id'));
        });

        $("#lc_related_post_list").val(ids.join(','));

    }


    /* -----------------------------
       SORTABLE
    ----------------------------- */

    $("#lc-related-list").sortable({
        update: function () {
            lc_UpdateHiddenField();
        }
    });


    /* -----------------------------
       ALERT CLOSE
    ----------------------------- */

    $('#lc_close_alert').click(function () {
        $('#lc_related_items_alert').hide("slow");
    });


    /* -----------------------------
       INITIALIZATION
    ----------------------------- */

    lc_LoadInitialSelections();

});