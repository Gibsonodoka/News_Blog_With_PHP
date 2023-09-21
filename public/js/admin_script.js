
function editPost(postId) {
    // Replace with the path to your edit_post.php file
    var editUrl = 'edit_post.php?id=' + postId;

    // Open a popup/modal window
    var popupWindow = window.open(editUrl, 'Edit Post', 'width=600,height=400');
    
    // Optional: Add an event listener to detect when the popup is closed
    if (popupWindow) {
        popupWindow.addEventListener('beforeunload', function() {
            // Reload the posts page after the popup is closed
            window.location.reload();
        });
    }
}

