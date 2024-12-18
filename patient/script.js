
$(document).ready(function () {
  const uploadContainer = $('#upload-container');
  const fileInput = $('#file-input');
  const fileList = $('#file-list');

  // Handle drag and drop events
  uploadContainer.on('dragover', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).addClass('drag-over');
  });

  uploadContainer.on('dragleave', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass('drag-over');
  });

  uploadContainer.on('drop', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).removeClass('drag-over');

      const files = e.originalEvent.dataTransfer.files;
      handleFiles(files);
  });

  // Handle file input (browse from computer)
  $('#browse-btn').on('click', function () {
      fileInput.click(); // Trigger file input click
  });

  fileInput.on('change', function () {
      const files = fileInput[0].files;
      handleFiles(files);
  });

  // Function to handle files
  function handleFiles(files) {
      fileList.empty(); // Clear previous file list
      for (let i = 0; i < files.length; i++) {
          const file = files[i];
          if (file.type === "application/pdf") {
              fileList.append(`<p>${file.name}</p> <br> `); // Display file name
          } else {
              alert('Only PDF files are allowed!');
          }
      }
  }
  $(".submittestbtn").on("click", function () {
    const fileInput = $("#file-input")[0];
    const file = fileInput.files[0];

    if (!file) {
        alert("Please select a PDF file to upload.");
        return;
    }else if($("#apptTest").val() == null){
        alert("You do not have an appointment set yet.");
        return;
    }
    console.log($("#apptTest"))
    // Create a FormData object to send the file and additional data
    const formData = new FormData();
    formData.append("test_file", file);
    formData.append("appt_id", $("#testapptvalue").val());
    formData.append("test_type", $("#test-type").val());
    formData.append("test_date", $("#start-date").val());
console.log($("#testapptvalue").val())
    // AJAX request to upload the file
    $.ajax({
        url: "uploadtestpdf.php", // Replace with the actual PHP script path
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            alert(response); // Show server response
        },
        error: function (xhr, status, error) {
            console.error("Upload failed:", error);
            alert("An error occurred while uploading the test.");
        }
    });
});
});

