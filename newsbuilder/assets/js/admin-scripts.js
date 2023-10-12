jQuery(document).ready(function ($) {

    selectedItems = [];
    var itemsWrapper = $('.itemsWrapper')
    // Listen for changes to the columnSpan input
    itemsWrapper.on('change', 'input[name="columnSpan"]', function () {
        var newColumnSpan = $(this).val();
        $(this).closest('.item').data('column-span', newColumnSpan);
    });

    // Listen for changes to the rowSpan input
    itemsWrapper.on('change', 'input[name="rowpan"]', function () {
        var newRowSpan = $(this).val();
        $(this).closest('.item').data('row-span', newRowSpan);
    });
    // Listen for clicks on the delete-item button
    itemsWrapper.on('click', '.delete-item', function () {
        var itemId = $(this).closest('.item').attr('id').substring(2); // Extract the ID from "m_XXX"

        selectedItems = selectedItems.filter(function (item) {
            return item.id !== itemId;
        });

        $(this).closest('.item').remove();
    });
    // Listen for changes to the background color input
    itemsWrapper.on('input', 'input[name="backgroundColor"]', function () {
        var newBackgroundColor = $(this).val();
        $(this).closest('.item').attr('style', 'background-color: ' + newBackgroundColor);
    });
    // Listen for real-time changes to the title color input
    itemsWrapper.on('input', 'input[name="titleColor"]', function () {
        var titleColor = $(this).val();
        $(this).closest('.item').find('.newsTitle').css('color', titleColor);
    });
    // Listen for real-time changes to the title color input
    itemsWrapper.on('input', 'input[name="textColor"]', function () {

        var textColor = $(this).val();
        $(this).closest('.item').find('.abstract').css('color', textColor);
    });


    itemsWrapper.on('contextmenu', '.color-input.bgcolor', function (e) {
        e.preventDefault();
        console.log('rightclick')
        var input = $('input', this);
        if (input.val() === 'transparent') {
            input.attr('type', 'color').val('#ffffff');
            input.closest('.item').css('background-color', '#ffffff')
        } else {
            input.attr('type', 'text').val('transparent');
            input.closest('.item').css('background-color', 'transparent')
            input.prop('disabled', true);
        }

    })


    // Existing event listeners for columnSpan and rowSpan
    itemsWrapper.on('change', 'input[name="columnSpan"]', function () {
        var newColumnSpan = $(this).val();
        $(this).closest('.item').attr('data-column-span', newColumnSpan);
    });

    itemsWrapper.on('change', 'input[name="rowpan"]', function () {
        var newRowSpan = $(this).val();
        $(this).closest('.item').data('row-span', newRowSpan);
    });
    // Listen for changes to the headerToggle checkbox
    itemsWrapper.on('change', '#headerToggle', function () {
        var isChecked = $(this).prop('checked');
        var itemDiv = $(this).closest('.item');
        var innerContentDiv = itemDiv.find('.inner-content');

        if (isChecked) {
            // Insert textarea if it doesn't exist
            if (innerContentDiv.find('textarea[name="header"]').length === 0) {
                innerContentDiv.prepend('<textarea class="newsTitle primary_color" name="header">Heading</textarea>');
            }
        } else {
            // Remove textarea
            innerContentDiv.find('textarea[name="header"]').remove();
        }
    });

    // Listen for changes to the headerToggle checkbox
    itemsWrapper.on('change', '#urlToggle', function () {
        var isChecked = $(this).prop('checked');
        var itemDiv = $(this).closest('.item');

        if (isChecked) {
            // Insert urlObject if it doesn't exist
            if (itemDiv.find('div.urlObject').length === 0) {
                itemDiv.append('<div class="urlObject readmore secondary_color"><input type="text" placeholder="https:// (url)" name="url" value=""/><span class="more ">Read More &gt;</span></div>');
            }
        } else {
            // Remove textarea
            itemDiv.find('div.urlObject').remove();
        }
    });

    // Listen for changes to the abstractToggle checkbox
    itemsWrapper.on('change', '#abstractToggle', function () {
        var isChecked = $(this).prop('checked');
        var itemDiv = $(this).closest('.item');
        var newsTitleTextarea = itemDiv.find('textarea.newsTitle');

        if (isChecked) {
            // Insert textarea if it doesn't exist
            if (itemDiv.find('textarea[name="abstract"]').length === 0) {
                newsTitleTextarea.after('<textarea class="abstract" name="abstract"></textarea>');
            }
        } else {
            // Remove textarea
            itemDiv.find('textarea[name="abstract"]').remove();
        }
    });

    itemsWrapper.on('click', '.dashicons', function () {
        var menu = $(this).closest('.menu-button').find('.menu');
        var item = $(this).closest('.item');

        if (menu.is(':visible')) {
            menu.slideUp({ duration: 500, easing: "swing" }); // or any other easing
            item.removeClass('highlight')
        } else {
            item.addClass('highlight')
            updatemenu(item);
            menu.slideDown({ duration: 500, easing: "swing" }); // or any other easing

        }
    });


    function styleSense() {
        $('#columns').on('input', function () {
            var numberOfColumns = $(this).val();
            if (numberOfColumns > 1) $('.itemsWrapper').css('grid-template-columns', `repeat(${numberOfColumns}, 1fr)`);
            else $('.itemsWrapper').css('grid-template-columns', `1fr`)
            $('.items.pagewide').css('grid-columns', `span ${numberOfColumns}`);
            document.documentElement.style.setProperty('--numberofcolumns', numberOfColumns);
            $('.itemsWrapper .item').each(function () {
                console.log('found item ' + $(this).data('column-span') + ' columns-span');
                if ($(this).data('column-span') > numberOfColumns) {
                    console.log('updating to ' + numberOfColumns)
                    $(this).data('column-span', numberOfColumns);
                    $('input#columnSpan' , $(this)).val(numberOfColumns);
                }
            })
        });
        $('#font_selection').on('change', function () {
            var fontSelection = $(this).val();
            $('.item .abstract, .primary_color, .secondary_color').css('font-family', fontSelection);
            document.documentElement.style.setProperty('--font-selection', fontSelection);
        });

        // Listen for changes to the color input
        $('#secondary_color').on('input', function () {
            secondaryColor = $(this).val();
            $('.secondary_color').css('background-color', secondaryColor);
            document.documentElement.style.setProperty('--secondary-color', secondaryColor);
        });
        $('#primary_color').on('input', function () {
            primaryColor = $(this).val();
            $('.primary_color').css('color', primaryColor);
            document.documentElement.style.setProperty('--primary-color', primaryColor);
        });
        $('#text_color').on('input', function () {
            textColor = $(this).val();
            $('.item .abstract').css('color', textColor);
            document.documentElement.style.setProperty('--text-color', textColor);
        });

        // Listen for changes to the title transform input
        $('#title_transform').on('change', function () {
            var titleTransform = $(this).val();
            $('.item .title').css('text-transform', titleTransform);
            document.documentElement.style.setProperty('--title-transform', titleTransform);
        });

        // Listen for changes to the page background input
        $('#page_background').on('input', function () {
            pageBackground = $(this).val();
            // $('.newsbuilder-wrapper').css('background-color', pageBackground);
            document.documentElement.style.setProperty('--page-background', pageBackground);
        });
    }

    // Function to update styles
    function updateStyles() {
        var textColor = $('#text_color').val();
        var primaryColor = $('#primary_color').val();
        var secondaryColor = $('#secondary_color').val();
        var fontSelection = $('#font_selection').val();
        var numberOfColumns = $('#columns').val();
        var titleTransform = $('#title_transform').val();
        var pageBackground = $('#page_background').val();

        $('.itemsWrapper').css('grid-template-columns', `repeat(${numberOfColumns}, 1fr)`);

        // Update styles
        document.documentElement.style.setProperty('--primary-color', primaryColor);
        document.documentElement.style.setProperty('--secondary-color', secondaryColor);
        document.documentElement.style.setProperty('--text-color', textColor);
        document.documentElement.style.setProperty('--font-selection', fontSelection);
        document.documentElement.style.setProperty('--numberofcolumns', numberOfColumns);
        console.log ('updated stylesheet  --numberofcolumns: '+ numberOfColumns)
        document.documentElement.style.setProperty('--title-transform', titleTransform);
        document.documentElement.style.setProperty('--page-background', pageBackground);

        itemsWrapper.find('textarea').each(function () {
            adjustTextareaHeight(this);
        });
    }


    // Update styles on page load  
    updateStyles();
    styleSense();
    mceinit();
    scanForItems()


    function scanForItems() {
        selectedItems = [];

        // Initialize selectedItems from already rendered items
        $('.itemsWrapper .item').each(function () {
            var item = {};

            $(this).find('.inner-content').find('input, textarea, select').each(function () {
                var name = $(this).attr('name');
                var value = $(this).val();
                item[name] = value;
            });

            selectedItems.push(item);
        });
        console.table(selectedItems)
    }



    // Initialize Select2
    $('.new.selectie').select2();
    // Initialize a variable to keep track of custom header IDs
    let customHeaderIdCounter = 0;

    // Listen for changes on the Select2 dropdown
    $('#selectie').on('select2:select', function (e) {
        const postId = e.params.data.id;
        let newItemHtml;
        let data
        if (postId === 'Header') {
            // Generate a unique ID for the custom header
            uniqueHeaderId = 'h_' + (++customHeaderIdCounter);

            newItemHtml = '<div class="item pagewide" id="' + uniqueHeaderId + '" data-column-span="all">';
            newItemHtml += '\n<div class="inner-content">       \n<div class="thumb" ></div>';
            newItemHtml += '\n<textarea class="newsTitle primary_color" name="header">Heading</textarea>';
            newItemHtml += '<textarea class="abstract" name="abstract"></textarea>';
            newItemHtml += '\n</div></div>';
            // Append new item to .itemsWrapper
            itemsWrapper.append(newItemHtml);
            var myObject = {
                header: true,
                abstract: false,
                url: false,
                columnSpan: 2,
                rowSpan: 1,
                titleColor: '#FFFFFF',
                textColor: '#000000',
                backgroundColor: '#FF0000'
            };

            insertMenu('#' + uniqueHeaderId, myObject);


            // Update the array of selected items with the new header
            data = { id: uniqueHeaderId, title: 'heading', type: 'heading' };
            selectedItems.push(data);
        } else {

            // Fetch post/page details via AJAX
            $.ajax({
                url: ajaxurl, // WordPress AJAX URL
                type: 'POST',
                data: {
                    action: 'fetch_post_details',
                    main_post_id: jQuery('#post_ID').val(), // This gets the main post ID
                    post_id: postId
                },
                success: function (response) {
                    const data = JSON.parse(response);
                    const newItemHtml = data.html;

                    // Append new item to .itemsWrapper
                    itemsWrapper.append(newItemHtml);
                    // Update the array of selected items
                    selectedItems.push(data);

                    $('.item:last-child').find('textarea').each(function () {
                        adjustTextareaHeight(this);
                    });


                }
            });
        }
    });

    $(".itemsWrapper").sortable({
        handle: ".inner-content",
        revert: 200,  // Animation duration in milliseconds
        cursor: "grabbing",
        update: function (event, ui) {
            var newOrder = $(".itemsWrapper .item").map(function () {
                return $(this).attr('id').substring(2); // Assuming the ID is in the format "m_XXX"
            }).get();

            selectedItems.sort(function (a, b) {
                return newOrder.indexOf(a.id) - newOrder.indexOf(b.id);
            });
        }

    });


    // Attach the event to a static parent (in this case, itemsWrapper)
    itemsWrapper.on('input', 'textarea', function () {
        adjustTextareaHeight(this);
    });



    $('input, .abstract', itemsWrapper).on('change', function () {
        scanForItems();
    })







    $(".item", itemsWrapper).on('blur', '.abstract', function () {
        //var itemId = $(this).closest('.item').attr('id').substring(2); // Extract the ID from "m_XXX"
        var itemId = $(this).closest('.item').find('input[name="id"]').val();
        //console.log(itemId);

        var updatedValue = $(this).val();
        var nameproperty = $(this).attr('name');
        var item = selectedItems.find(function (item) {
            return item.id === itemId;

            // console.log("ïtemid=" + item.id === itemId)
        });
        if (item) {
            item[nameproperty] = updatedValue;
            // console.table(selectedItems);
        }
    });



    $('#post').on('submit', function (e) {
        // console.table(selectedItems);
        var inlineContent = tinymce.get('main_editor').getContent();
        $('#hidden_textarea').val(inlineContent);

        scanForItems();
        selectedItemsJSON = JSON.stringify(selectedItems);
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'save_post_items',
                post_id: $('#post_ID').val(),
                post_items: selectedItemsJSON,
                security: my_script_vars.nonce
            },
            success: function (response) {
                console.log('Post items saved successfully');
            },
            error: function (error) {
                console.log('Error saving post items');
            }
        });
    });

    // Insert a "phantom" div when the Select2 dropdown is opened
    $('#select_posts_pages').on('select2:opening', function (e) {
        $('.itemsWrapper').append('<div class="phantom">Loading...</div>');
    });

    // Remove the "phantom" div when an item is selected
    $('#select_posts_pages').on('select2:select', function (e) {
        $('.phantom').slideUp({ "duration": "150" }, function () {
            $(this).remove();
        });
    });


    // Remove the "phantom" div when the dropdown is closed
    $('#select_posts_pages').on('select2:closing', function (e) {
        $('.phantom').slideUp({ "duration": "150" }, function () {
            $(this).remove();
        });
        // console.table(selectedItems);
    });

    jQuery('.item a.readmore').on('click', function (e) {
        e.preventDefault(); // Prevent the default link behavior
        var confirmation = window.confirm("Are you sure you want to go to this link?");
        if (confirmation) {
            window.location.href = jQuery(this).attr('href'); // Navigate to the link if the user confirms
        }
    });

    // Function to adjust textarea height
    function adjustTextareaHeight(textarea) {
        jQuery(textarea).css('height', 'auto');
        jQuery(textarea).css('height', (textarea.scrollHeight + 2) + 'px');
    }



});


function mceinit() {
    tinymce.init({
        selector: '#main_editor',  // Change this value according to your HTML
        inline: true,
        menubar: false,
        plugins: [
            'link',
            'lists',
            'textcolor'
        ],
        toolbar: [
            'bold italic underline',
            'forecolor backcolor | alignleft aligncenter alignright alignfull | numlist bullist outdent indent'
        ],
        valid_elements: 'p[style],strong,em,span[style],a[href],ul,ol,li',
        valid_styles: {
            '*': 'font-size,font-family,color,text-decoration,text-align'
        }
    });


    jQuery('.abstract').each(function () {
        var id = jQuery(this).attr('id');
        tinymce.init({
            inline: true, selector: '#' + id,
            width: 320,
            menubar: false,
            plugins: ['link', 'textcolor'],
            toolbar: ['bold italic underline  forecolor  numlist bullist outdent indent link'],
            valid_elements: 'p[style],strong,em,span[style],a[href],ul,ol,li',
            link_default_protocol: 'https'
        });
    });

}

function insertMenu(target, object) {
    // Fetch current colors from the item
    var currentTextColor = jQuery(target).find('.abstract').css('color');
    var currentBackgroundColor = jQuery(target).css('background-color');
    var currentTitleColor = jQuery(target).find('.newsTitle').css('color');

    var formHTML = '<div class="menu-button">';
    formHTML += '<div class="dashicons dashicons-arrow-down-alt2"></div>';
    formHTML += '<div class="menu">';
    formHTML += '<button class="delete-item"><span class="dashicons dashicons-no"></span> Delete Item</button>';
    formHTML += '<div class="toggle-option"><input type="checkbox" id="headerToggle" ' + (object.header ? 'checked' : '') + '><label for="headerToggle">Header</label></div>';
    formHTML += '<div class="toggle-option"><input type="checkbox" id="abstractToggle" ' + (object.abstract ? 'checked' : '') + '><label for="abstractToggle">Abstract</label></div>';
    formHTML += '<div class="toggle-option"><input type="checkbox" id="urlToggle" ' + (object.url ? 'checked' : '') + '><label for="urlToggle">URL</label></div>';
    formHTML += '<div class="number-input"><label for="columnSpan">Column Span: </label><input type="number" min="1" max="5" name="columnSpan" id="columnSpan" value="' + object.columnSpan + '"></div>';
    formHTML += '<div class="number-input"><label for="rowSpan">Row Span: </label><input type="number" id="rowSpan" name="rowpan" value="' + object.rowSpan + '"></div>';
    formHTML += '<div class="color-input"><label for="titleColor">Title Color: </label><input type="color" id="TitleColor" name="titleColor" value="' + rgb2hex(currentTitleColor) + '"></div>';
    formHTML += '<div class="color-input"><label for="textColor">Text Color: </label><input type="color" id="textColor" name="textColor" value="' + rgb2hex(currentTextColor) + '"></div>';
    formHTML += '<div class="color-input bgcolor"><label for="bgColor">Background: </label><input type="' + (currentBackgroundColor === 'transparent' ? 'text' : 'color') + '" id="bgColor" name="backgroundColor" value="' + rgb2hex(currentBackgroundColor) + '"></div>';
    formHTML += '</div>';
    formHTML += '</div>';

    // Insert the HTML at the desired location
    jQuery(target).find('.inner-content').prepend(formHTML);
}

function updatemenu(target) {
    try {
        var setting = {};
        var abstractElement = jQuery(target).find('.abstract');

        currentTextColor = abstractElement.length ? abstractElement.css('color') : null;
        currentBackgroundColor = jQuery(target).css('background-color');
        currentTitleColor = jQuery(target).find('.newsTitle').css('color');


        if (currentTitleColor) {
            jQuery('input[name="titleColor"]', target).val(rgb2hex(currentTitleColor));
        }

        if (currentTextColor) {
            jQuery('input[name="textColor"]', target).val(rgb2hex(currentTextColor));
        }

        if (currentBackgroundColor) {
            if (currentBackgroundColor == 'rgba(0, 0, 0, 0)') {
                jQuery('input[name="backgroundColor"]', target).attr('type', 'text').val('transparent');

            } else {
                jQuery('input[name="backgroundColor"]', target).attr('type', 'color').val(rgb2hex(currentBackgroundColor));
            }
        }

        // New logic to update the Column Span input field
        var currentColumnSpan = jQuery(target).data('column-span');
        var activeColumnSpan = jQuery('input#columns').val();

        if (currentColumnSpan === "all") {
            // If it spans all columns, set the input value accordingly

            jQuery('input[name="columnSpan"]', target).val(activeColumnSpan);
            jQuery(target).data('column-span', activeColumnSpan);
        } else {
            // Otherwise, set it to the numeric value
            jQuery('input[name="columnSpan"]', target).val(currentColumnSpan);
        }
    } catch (error) {
        console.error("An error occurred in updatemenu: ", error);
    }
}


function rgb2hex(rgb) {
    if (rgb === 'transparent' || rgb === 'rgba(0, 0, 0, 0)') {
        return 'transparent';
    }
    rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
    return (rgb && rgb.length === 4) ? "#" +
        ("0" + parseInt(rgb[1], 10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[2], 10).toString(16)).slice(-2) +
        ("0" + parseInt(rgb[3], 10).toString(16)).slice(-2) : '';
}
