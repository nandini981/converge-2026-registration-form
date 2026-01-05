/**
 * Google Apps Script for CONVERGE 2026 Registration Form
 * 
 * This script handles:
 * 1. Saving registration data to Google Sheets
 * 2. Uploading resume files to Google Drive
 * 
 * IMPORTANT - GOOGLE SHEET SETUP:
 * Your Google Sheet should have these column headers in Row 1 (14 columns total):
 * Registration ID | Timestamp | Name | Email | Phone | WhatsApp | College | Course | Year | Companies | Referral | Additional Info | Resume Name | Drive Link
 * 
 * SETUP INSTRUCTIONS:
 * 1. Open your Google Sheet
 * 2. Create/verify the column headers above (14 columns total)
 * 3. Go to Extensions → Apps Script
 * 4. Delete any existing code
 * 5. Paste this entire code
 * 6. Update the DRIVE_FOLDER_ID variable (line 17) with your Drive folder ID
 * 7. Save the script (Ctrl+S)
 * 8. Click Deploy → New deployment
 * 9. Select type: Web app
 * 10. Execute as: Me
 * 11. Who has access: Anyone (IMPORTANT for CORS to work!)
 * 12. Click Deploy
 * 13. Copy the Web App URL and update it in index.html (line 776) and config.php (line 8)
 * 
 * NOTE: If you update the script code after deployment, you need to:
 * - Click Deploy → Manage deployments
 * - Click the pencil icon (edit)
 * - Select "New version"
 * - Click Deploy
 */

// YOUR GOOGLE DRIVE FOLDER ID (get it from the folder URL)
// Example: https://drive.google.com/drive/folders/1Wwv1ZnQjTFKcb9HoBWhZDIobWw45cSC4
//                                                           ^^^^^^^^^^^^^^^^^^^^^^^^^^^^ This part
var DRIVE_FOLDER_ID = '1Wwv1ZnQjTFKcb9HoBWhZDIobWw45cSC4';

/**
 * Handles GET requests (for testing the script)
 * Access the Web App URL in a browser to test
 */
function doGet(e) {
  return ContentService.createTextOutput(JSON.stringify({
    'success': true,
    'message': 'Google Apps Script is working! Use POST method to submit form data.',
    'instructions': 'This script expects POST requests with JSON data from the registration form.'
  })).setMimeType(ContentService.MimeType.JSON);
}


/**
 * Handles POST requests from the registration form
 * Note: The 'e' parameter is automatically provided by Google Apps Script when deployed as a Web App
 * If you see an error about 'e' being undefined, make sure you're calling this via HTTP POST, not running it manually
 */
function doPost(e) {
  try {
    // Check if event parameter exists (should always exist when called via HTTP POST)
    if (!e) {
      Logger.log('Error: Event parameter (e) is undefined. This usually means the function was called manually or incorrectly.');
      return ContentService.createTextOutput(JSON.stringify({
        'success': false,
        'message': 'Invalid request: event parameter is missing. This function must be called via HTTP POST request, not run manually.',
        'hint': 'Use the testDoPost() function to test, or submit the form via the web page.'
      })).setMimeType(ContentService.MimeType.JSON);
    }
    
    // Log the event for debugging
    Logger.log('Event received. Type: ' + typeof e);
    Logger.log('Event keys: ' + (e ? Object.keys(e).join(', ') : 'e is null/undefined'));
    
    // Check if postData exists
    if (!e.postData || !e.postData.contents) {
      Logger.log('Error: postData is missing. Event object: ' + JSON.stringify(e));
      Logger.log('Event parameter details: ' + (e ? JSON.stringify(e) : 'null/undefined'));
      return ContentService.createTextOutput(JSON.stringify({
        'success': false,
        'message': 'Invalid request: postData is missing. Make sure you are sending JSON data in the request body.',
        'debug': {
          'eventExists': !!e,
          'postDataExists': !!(e && e.postData),
          'contentsExists': !!(e && e.postData && e.postData.contents)
        }
      })).setMimeType(ContentService.MimeType.JSON);
    }
    
    // Parse the incoming data
    var data;
    try {
      var rawData = e.postData.contents;
      var previewLength = Math.min(200, rawData.length);
      Logger.log('Raw data received (first ' + previewLength + ' chars): ' + rawData.substring(0, previewLength));
      data = JSON.parse(rawData);
      Logger.log('Data parsed successfully. Registration ID: ' + (data.registration_id || 'N/A'));
    } catch (parseError) {
      Logger.log('JSON Parse Error: ' + parseError.toString());
      var errorPreviewLength = Math.min(500, e.postData.contents.length);
      Logger.log('Received data (first ' + errorPreviewLength + ' chars): ' + e.postData.contents.substring(0, errorPreviewLength));
      return ContentService.createTextOutput(JSON.stringify({
        'success': false,
        'message': 'Invalid JSON data: ' + parseError.toString()
      })).setMimeType(ContentService.MimeType.JSON);
    }
    
    // Get the active spreadsheet
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();
    
    // Initialize drive link
    var driveLink = 'Not uploaded';
    
    // If file data is provided, upload to Drive
    if (data.file_data && data.file_name && data.file_type) {
      try {
        // Convert base64 to blob
        var blob = Utilities.newBlob(Utilities.base64Decode(data.file_data), data.file_type, data.file_name);
        
        // Get the target folder (use folder ID from data if provided, otherwise use default)
        var folderId = data.drive_folder_id || DRIVE_FOLDER_ID;
        var folder = DriveApp.getFolderById(folderId);
        
        // Upload file to Drive
        var file = folder.createFile(blob);
        
        // Set file sharing to "Anyone with the link can view"
        file.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);
        
        // Get the file URL
        driveLink = file.getUrl();
      } catch (fileError) {
        // Log error but continue with saving to Sheets
        Logger.log('Drive upload error: ' + fileError.toString());
        driveLink = 'Upload failed: ' + fileError.toString();
      }
    }
    
    // Prepare row data for Sheets
    var rowData = [
      data.registration_id || '',
      data.timestamp || new Date().toISOString(),
      data.name || '',
      data.email || '',
      data.phone || '',
      data.whatsapp || '',
      data.college || '',
      data.course || '',
      data.year || '',
      data.companies || '',
      data.referral || 'None',
      data.additional || 'None',
      data.resume_name || 'Not uploaded',
      driveLink
    ];
    
    // Append data to the sheet
    sheet.appendRow(rowData);
    
    // Return success response
    return ContentService.createTextOutput(JSON.stringify({
      'success': true,
      'message': 'Registration saved successfully',
      'drive_link': driveLink
    })).setMimeType(ContentService.MimeType.JSON);
    
  } catch (error) {
    // Return error response
    Logger.log('Error: ' + error.toString());
    return ContentService.createTextOutput(JSON.stringify({
      'success': false,
      'message': error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * Test function - can be used to test the script manually
 * Run this function from the Apps Script editor to test
 */
function testDoPost() {
  var testData = {
    registration_id: 'CONV26-TEST-' + Date.now(),
    timestamp: new Date().toISOString(),
    name: 'Test User',
    email: 'test@example.com',
    phone: '1234567890',
    whatsapp: '1234567890',
    college: 'Test College',
    course: 'Test Course',
    year: '3rd Year',
    companies: 'Company A, Company B',
    referral: 'None',
    additional: 'Test registration',
    resume_name: 'test-resume.pdf'
  };
  
  var mockEvent = {
    postData: {
      contents: JSON.stringify(testData)
    }
  };
  
  var result = doPost(mockEvent);
  Logger.log(result.getContent());
}
