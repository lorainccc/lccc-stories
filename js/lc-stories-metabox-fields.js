jQuery(document).ready(function($) {

    let mediaUploader;

    $('#lc-image-selector').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            let attachment = mediaUploader.state().get('selection').first().toJSON();

            $('#image_attachment_id').val(attachment.id);
            $('#lc-image-preview')
                .attr('src', attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url)
                .show();
        });

        mediaUploader.open();
    });

    $('#lc-image-remove').on('click', function() {
        $('#lc_image_attachment_id').val('');
        $('#lc-image-preview').hide();
    });

    $('#lc-video-selector').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            let attachment = mediaUploader.state().get('selection').first().toJSON();

            $('#lc_video_attachment_id').val(attachment.id);
            $('#lc-video-preview')
                .attr('src')
                .show();
        });

        mediaUploader.open();
    });

    $('#lc-video-remove').on('click', function() {
        $('#video_attachment_id').val('');
        $('#lc-video-preview').hide();
    });

    $('#lc-poster-image-selector').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Select Image',
            button: { text: 'Use this image' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            let attachment = mediaUploader.state().get('selection').first().toJSON();

            $('#lc_poster_image_id').val(attachment.id);
            $('#lc-poster-image-preview')
                .attr('src')
                .show();
        });

        mediaUploader.open();
    });

    $('#lc-poster-image-remove').on('click', function() {
        $('#lc_poster_image_id').val('');
        $('#lc-poster-image-preview').hide();
    });

    
    const lcSourceList = document.getElementById('lc-post-list');
    const lcSelectedRelatedList = document.getElementById('lc-related-list');
    const lcSelectedItemIds = new Set(); // To keep track of which items are selected
    const lcMax_Selections = 3;

    $('#lc_related_items_alert').hide();

    //Function to Move Item to Selected List
    lcSourceList.addEventListener('click', lc_CloneItem);

    function lc_CloneItem(event){
        if(event.target.nodeName === 'LI' && !event.target.classList.contains("selected")){
        if (lcSelectedRelatedList.children.length >= lcMax_Selections) {
            $('#lc_related_items_alert').show("slow");
            return;
        }
        const lcClickedItem = event.target;
        const lcItemId = lcClickedItem.getAttribute("data-id");
        
        //Clone the node to create a new, functional element in the selected item list
        const lcNewItemInSelected = lcClickedItem.cloneNode(true);
        lcSelectedRelatedList.appendChild(lcNewItemInSelected);
        
        lcClickedItem.classList.add("selected");
        
        lcSelectedItemIds.add(lcItemId);      
        }
    }

    //Allow removing items from the selected list
    lcSelectedRelatedList.addEventListener("click", lc_RemoveItem);

    function lc_RemoveItem(event){
        if(event.target.nodeName === 'LI') {
        const lcClickedRelatedItem = event.target;
        const lcRelatedItemId = lcClickedRelatedItem.getAttribute("data-id");
        
        console.log("Related Item ID: ", + lcRelatedItemId);
        
        //Remove the 'selected' class from the original item
        const lcOriginalItem = document.querySelector('#lc-post-list li[data-id="' + lcRelatedItemId + '"]');
        
        console.log(lcOriginalItem);
        
        if(lcOriginalItem){
            lcOriginalItem.classList.remove("selected");
        }
        
        //Remove the item from the related items list
        lcClickedRelatedItem.remove();
        lcSelectedItemIds.delete(lcRelatedItemId);
        }
    }  

    $(function() {
        $( "#lc-related-list" ).sortable();
    });

    $( '#lc_close_alert' ).click(function(){
        $(this).parent( '#lc_related_items_alert' ).hide("slow");   
    });

});