$(document).ready(function () {
  // Initialize default state
  function loadDefaultContent() {
    if ($("#playlist li").length > 0) {
      const firstLesson = $("#playlist li").eq(0);
      const videoUrl = firstLesson.attr("movieurl");
      const pdfUrl = firstLesson.attr("pdfurl");

      // Load the default video if available
      if (videoUrl) {
        $("#videoarea").attr({ src: videoUrl }).show();
        $("#videoarea")[0].play(); // Play the video
      }

      // Load the default PDF if available
      if (pdfUrl) {
        $("#pdfviewer").html(
          `<embed src="${pdfUrl}" type="application/pdf" width="100%" height="600px">`
        ).show();
      }
    }
  }

  // Load the default content when the page loads
  loadDefaultContent();

  // Handle click event on playlist items
  $("#playlist li").on("click", function () {
    const videoUrl = $(this).attr("movieurl");
    const pdfUrl = $(this).attr("pdfurl");

    // Update video source and play if available
    if (videoUrl) {
      $("#videoarea").attr({ src: videoUrl }).show();
      $("#videoarea")[0].play();
    } else {
      $("#videoarea").hide();
    }

    // Update PDF source if available
    if (pdfUrl) {
      $("#pdfviewer").html(
        `<embed src="${pdfUrl}" type="application/pdf" width="100%" height="600px">`
      ).show();
    } else {
      $("#pdfviewer").empty().hide();
    }
  });
});
