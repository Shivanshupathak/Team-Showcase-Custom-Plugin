
jQuery(document).ready(function($) {
    var count = 6;
    var offset = 6;
    var loadMoreButton = $('#load-more-button');

    loadMoreButton.on('click', function() {
        var button = $(this);

        $.ajax({
            url: teamShowcase.ajaxUrl,
            type: 'POST',
            data: {
                action: 'team_showcase_load_more',
                count: 3, // Display 3 posts on each click
                offset: offset,
            },
            success: function(response) {
                if (response.success) {
                    var teamMembers = $(response.data.output);

                    // Append the new members directly before the load more button
                    teamMembers.insertBefore(button);

                    // Update the offset
                    offset += 3;

                    // Show or hide the load more button
                    if (!response.data.has_more_posts) {
                        button.hide();
                    }
                }
            },
        });
    });
});

